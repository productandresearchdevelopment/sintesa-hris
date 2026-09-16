<?php

namespace App\Controllers\Front\Attendances;

use App\Http\Controllers\Controller;
use App\Libraries\ExportExcel;
use App\Libraries\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance as IqAttendance;
use App\Models\Employees\Employee as IqEmployee;
use App\Models\Office;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class Attendance extends Controller
{
    public function index()
    {
        $employee = IqEmployee::where('id', Auth::user()->employ_id)->first();
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found');
        }

        $today = Carbon::now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endDate = $today;

        $workingDays = [];
        $period = CarbonPeriod::create($startOfMonth, $endDate);
        foreach ($period as $date) {
            $workingDays[] = $date->format('Y-m-d');
        }

        $attendances = IqAttendance::where('employee_id', $employee->id)
            ->whereIn('date', $workingDays)
            ->get()
            ->keyBy('date');

        $onTime = $attendances->where('status', 'on_time')->count();
        $late = $attendances->where('status', 'late')->count();
        $presentDays = $attendances->count();
        $absent = count($workingDays) - $presentDays;

        $totalWorkingHours = 0;
        foreach ($attendances as $att) {
            $work = $this->calculateWorkHours($att->date, $att->clock_in_time, $att->clock_out_time);
            $totalWorkingHours += $work['hours'];
        }

        $monthStats = [
            'on_time' => $onTime,
            'late' => $late,
            'absent' => $absent,
            'working' => $totalWorkingHours
        ];

        $todayAttendance = IqAttendance::where('employee_id', $employee->id)
            ->whereDate('date', $today->format('Y-m-d'))
            ->first();

        $recentLogs = IqAttendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->orderBy('clock_in_time', 'desc')
            ->limit(10)
            ->get();

        $formattedLogs = [];
        foreach ($recentLogs as $log) {
            if ($log->clock_in_time) {
                $formattedLogs[] = [
                    'type' => 'Clock In',
                    'date' => Carbon::parse($log->date)->format('j M Y'),
                    'time' => Carbon::parse($log->clock_in_time)->format('h:i A'),
                    'icon' => 'bi-box-arrow-in-right'
                ];
            }

            if ($log->clock_out_time) {
                $formattedLogs[] = [
                    'type' => 'Clock Out',
                    'date' => Carbon::parse($log->date)->format('j M Y'),
                    'time' => Carbon::parse($log->clock_out_time)->format('h:i A'),
                    'icon' => 'bi-box-arrow-right'
                ];
            }
        }

        $formattedLogs = array_slice($formattedLogs, 0, 10);

        $view = isMobile() ? '_front.attendance.mobile' : '_front.attendance.index';
        return view($view, compact(
            'employee',
            'todayAttendance',
            'monthStats',
            'formattedLogs'
        ));
    }

    public function check($type)
    {
        if (!in_array($type, ['in', 'out'])) {
            return redirect()->route('attendance.index')->with('error', 'Invalid attendance type');
        }

        $employee = IqEmployee::with(['office'])->where('id', Auth::user()->employ_id)->first();

        $today = Carbon::now()->format('Y-m-d');
        $todayAttendance = IqAttendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($type == 'in' && $todayAttendance && $todayAttendance->clock_in_time) {
            return redirect()->route('attendance.index')
                ->with('error', 'You have already clocked in today');
        }

        if ($type == 'out' && (!$todayAttendance || !$todayAttendance->clock_in_time)) {
            return redirect()->route('attendance.index')
                ->with('error', 'You need to clock in before you can clock out');
        }

        if ($type == 'out' && $todayAttendance && $todayAttendance->clock_out_time) {
            return redirect()->route('attendance.index')
                ->with('error', 'You have already clocked out today');
        }

        $view = isMobile() ? '_front.attendance.check-mobile' : '_front.attendance.check';
        return view($view, compact('employee', 'type'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:iq_employ,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required',
            'distance_from_office' => 'required|numeric',
            'type' => 'required|in:in,out',
            'work_from' => 'required|in:office,anywhere'
        ]);

        $employee = IqEmployee::find($request->employee_id);

        if ($employee->id != Auth::user()->employ_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $office = Office::find($employee->office_id);
        $now = Carbon::now();
        $today = $now->toDateString();

        if (
            $request->work_from === 'office' &&
            $request->distance_from_office > $office->max_distance_allowed
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar jangkauan lokasi kantor.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $photo = null;

            if ($request->hasFile('photo')) {
                $photo = FileUpload::upload('photo', 'attendance');
            }

            $status = 'on_time';
            if ($request->type === 'in') {
                $expectedTimeStr = $employee->shift_start_time ?? '09:00:00';
                $expectedTime = Carbon::createFromFormat('H:i:s', $expectedTimeStr)
                    ->setDateFrom($now);
                $lateThreshold = $expectedTime->copy()->addMinutes(10);
                if ($now->gt($lateThreshold)) {
                    $status = 'late';
                }
            }

            $attendance = IqAttendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance && $request->type === 'in') {
                $attendance = new IqAttendance();
                $attendance->employee_id = $employee->id;
                $attendance->date = $today;
                $attendance->clock_in_time = $now;
                $attendance->clock_in_lat = $request->latitude;
                $attendance->clock_in_lng = $request->longitude;
                $attendance->clock_in_photo = $photo;
                $attendance->distance_in_from_office =  $request->distance_from_office;
                $attendance->status = $status;
                $attendance->work_from = $request->work_from;
                $attendance->save();
            } elseif ($attendance && $request->type === 'out') {
                $attendance->clock_out_time = $now;
                $attendance->clock_out_lat = $request->latitude;
                $attendance->clock_out_lng = $request->longitude;
                $attendance->clock_out_photo = $photo;
                $attendance->distance_out_from_office =  $request->distance_from_office;
                $attendance->save();
            } else {
                throw new \Exception('Invalid attendance action');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $request->type === 'in' ? 'Successfully clocked in' : 'Successfully clocked out',
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function buildReportQuery(Request $request, $employee, $isHR)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $endDate = Carbon::parse($request->end_date)->format('Y-m-d');
        } else {
            $month = $request->month ?? Carbon::now()->month;
            $year = $request->year ?? Carbon::now()->year;
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
        }

        $userRole = strtolower(optional(optional(Auth::user())->role)->name ?? '');
        $isSuperUser = in_array($userRole, ['developer', 'superadmin', 'administrator']);
        $userCompany = optional($employee)->company_id ?? optional(optional(Auth::user())->employee)->company_id ?? optional(Auth::user())->company_id;

        $query = IqAttendance::whereBetween('date', [$startDate, $endDate])
            ->with(['employee', 'employee.organization']);

        if (!$isSuperUser && $userCompany) {
            $query->whereHas('employee', function ($q) use ($userCompany) {
                $q->where('company_id', $userCompany);
            });
        }

        if (!$isHR) {
            $userOrgId = $employee ? $employee->org_id : null;
            if ($userOrgId) {
                $allowedOrgIds = array_merge([$userOrgId], $this->getAllChildOrganizations($userOrgId));
                $query->whereHas('employee', function ($q) use ($allowedOrgIds) {
                    $q->whereIn('org_id', $allowedOrgIds);
                });
            } else {
                $query->whereRaw('1=0');
            }
        }

        if ($request->filled('org_id')) {
            $selectedOrgId = $request->org_id;
            $targetOrgIds = array_merge([$selectedOrgId], $this->getAllChildOrganizations($selectedOrgId));
            $query->whereHas('employee', function ($q) use ($targetOrgIds) {
                $q->whereIn('org_id', $targetOrgIds);
            });
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('employee_name')) {
            $searchName = $request->employee_name;
            $query->whereHas('employee', function ($q) use ($searchName) {
                $q->where('fullname', 'like', "%{$searchName}%");
            });
        }

        // Sort by date DESC, then earliest clock in time ASC (non-null first)
        $query->orderBy('date', 'desc')
            ->orderByRaw('CASE WHEN clock_in_time IS NULL THEN 1 ELSE 0 END ASC')
            ->orderBy('clock_in_time', 'asc');

        return [
            'query' => $query,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }

    private function getOrganizationTreeOptions($allowedOrgIds = null, $userCompany = null)
    {
        $orgQuery = \App\Models\Organization::orderBy('name');
        if ($userCompany !== null) {
            $orgQuery->where('company_id', $userCompany);
        }
        if ($allowedOrgIds !== null) {
            $orgQuery->whereIn('id', $allowedOrgIds);
        }
        $allOrgs = $orgQuery->get();
        $tree = [];

        $buildTree = function ($parentId, $depth = 0) use (&$buildTree, $allOrgs, &$tree) {
            $children = $allOrgs->filter(function ($org) use ($parentId) {
                return $org->parent_id == $parentId;
            })->values();

            $totalChildren = count($children);
            foreach ($children as $index => $child) {
                $isLast = ($index === $totalChildren - 1);
                $indent = str_repeat("\u{00A0}\u{00A0}\u{00A0}\u{00A0}", $depth);
                $branch = $isLast ? '└─ ' : '├─ ';

                $tree[] = [
                    'id' => $child->id,
                    'name' => $indent . $branch . $child->name,
                    'raw_name' => $child->name,
                ];
                $buildTree($child->id, $depth + 1);
            }
        };

        $allIds = $allOrgs->pluck('id')->toArray();
        $rootOrgs = $allOrgs->filter(function ($org) use ($allIds) {
            return is_null($org->parent_id) || !in_array($org->parent_id, $allIds);
        })->values();

        foreach ($rootOrgs as $org) {
            $tree[] = [
                'id' => $org->id,
                'name' => $org->name,
                'raw_name' => $org->name,
            ];
            $buildTree($org->id, 1);
        }

        $uniqueTree = [];
        $visited = [];
        foreach ($tree as $item) {
            if (!in_array($item['id'], $visited)) {
                $visited[] = $item['id'];
                $uniqueTree[] = $item;
            }
        }

        return $uniqueTree;
    }

    public function report(Request $request)
    {
        $employee = IqEmployee::where('id', Auth::user()->employ_id)->first();
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found');
        }

        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;

        $userRole = strtolower(optional(optional(Auth::user())->role)->name ?? '');
        $isSuperUser = in_array($userRole, ['developer', 'superadmin', 'administrator']);
        $isHR = in_array($userRole, ['hrga', 'developer', 'superadmin', 'administrator']);
        $userCompany = optional($employee)->company_id ?? optional(optional(Auth::user())->employee)->company_id ?? optional(Auth::user())->company_id;

        $allowedOrgIds = null;
        if (!$isHR && $employee) {
            $allowedOrgIds = array_merge([$employee->org_id], $this->getAllChildOrganizations($employee->org_id));
        }

        $orgTree = $this->getOrganizationTreeOptions($allowedOrgIds, !$isSuperUser ? $userCompany : null);

        $empQuery = IqEmployee::orderBy('fullname');
        if (!$isSuperUser && $userCompany) {
            $empQuery->where('company_id', $userCompany);
        }
        if (!$isHR && $allowedOrgIds !== null) {
            $empQuery->whereIn('org_id', $allowedOrgIds);
        }
        $employeesList = $empQuery->get(['id', 'fullname']);

        $reportData = $this->buildReportQuery($request, $employee, $isHR);
        $startDate = $reportData['startDate'];
        $endDate = $reportData['endDate'];
        $logs = $reportData['query']->get();

        $selectedOrgId = $request->org_id;
        $selectedEmployeeId = $request->employee_id;
        $employeeNameSearch = $request->employee_name;

        $formattedLogs = [];

        foreach ($logs as $log) {
            $work = $this->calculateWorkHours($log->date, $log->clock_in_time, $log->clock_out_time);

            $formattedLogs[] = [
                'id' => $log->id,
                'employee_name' => optional($log->employee)->fullname ?? 'Unknown',
                'date' => Carbon::parse($log->date)->format('j M Y'),
                'day' => Carbon::parse($log->date)->format('l'),
                'clock_in' => $log->clock_in_time ? Carbon::parse($log->clock_in_time)->format('H:i:s') : '-',
                'clock_out' => $log->clock_out_time ? Carbon::parse($log->clock_out_time)->format('H:i:s') : '-',
                'raw_clock_in' => $log->clock_in_time ? Carbon::parse($log->clock_in_time)->format('H:i') : '',
                'raw_clock_out' => $log->clock_out_time ? Carbon::parse($log->clock_out_time)->format('H:i') : '',
                'work_hours' => $work['text'],
                'status' => ucfirst(str_replace('_', ' ', $log->status)),
                'status_class' => $this->getStatusClass($log->status),
                'clock_in_photo' => $log->clock_in_photo ? (filter_var($log->clock_in_photo, FILTER_VALIDATE_URL) || str_starts_with($log->clock_in_photo, '/') ? $log->clock_in_photo : (fileUri($log->clock_in_photo) ?: route('file', $log->clock_in_photo))) : null,
                'clock_out_photo' => $log->clock_out_photo ? (filter_var($log->clock_out_photo, FILTER_VALIDATE_URL) || str_starts_with($log->clock_out_photo, '/') ? $log->clock_out_photo : (fileUri($log->clock_out_photo) ?: route('file', $log->clock_out_photo))) : null,
                'clock_in_lat' => $log->clock_in_lat ?? '-',
                'clock_in_lng' => $log->clock_in_lng ?? '-',
                'clock_out_lat' => $log->clock_out_lat ?? '-',
                'clock_out_lng' => $log->clock_out_lng ?? '-',
            ];
        }

        $view = isMobile() ? '_front.attendance.report-mobile' : '_front.attendance.report';
        return view($view, compact(
            'employee',
            'formattedLogs',
            'month',
            'year',
            'startDate',
            'endDate',
            'selectedOrgId',
            'selectedEmployeeId',
            'employeeNameSearch',
            'orgTree',
            'employeesList',
            'isHR'
        ));
    }

    public function exportExcel(Request $request)
    {
        ini_set('memory_limit', '64048M');
        ini_set('max_execution_time', '300');

        $employee = IqEmployee::where('id', Auth::user()->employ_id)->first();
        $userRole = strtolower(optional(Auth::user()->role)->name);
        $isHR = in_array($userRole, ['hrga', 'developer', 'superadmin', 'administrator']);

        $reportData = $this->buildReportQuery($request, $employee, $isHR);
        $startDate = $reportData['startDate'];
        $endDate = $reportData['endDate'];

        $title = [
            ['Attendance Report', 'h2'],
            ['START DATE : ' . Carbon::parse($startDate)->format('d F Y'), 'h5'],
            ['END DATE : ' . Carbon::parse($endDate)->format('d F Y'), 'h5']
        ];

        $logs = $reportData['query']->get()->map(function ($log) {
            $log->employee_name = optional($log->employee)->fullname ?? 'Unknown';
            $log->day = Carbon::parse($log->date)->format('l');
            $log->date_formatted = Carbon::parse($log->date)->format('j M Y');
            $work = $this->calculateWorkHours($log->date, $log->clock_in_time, $log->clock_out_time);
            $log->work_hours = $work['text'];
            $log->clock_in_lat = $log->clock_in_lat ?? '-';
            $log->clock_in_lng = $log->clock_in_lng ?? '-';
            $log->clock_in_time_formatted = $log->clock_in_time ? Carbon::parse($log->clock_in_time)->format('H:i:s') : '-';
            $log->clock_out_lat = $log->clock_out_lat ?? '-';
            $log->clock_out_lng = $log->clock_out_lng ?? '-';
            $log->clock_out_time_formatted = $log->clock_out_time ? Carbon::parse($log->clock_out_time)->format('H:i:s') : '-';
            $log->clock_in_link = $log->clock_in_photo ? fileUri($log->clock_in_photo) : '-';
            $log->clock_out_link = $log->clock_out_photo ? fileUri($log->clock_out_photo) : '-';
            $log->status_formatted = ucfirst(str_replace('_', ' ', $log->status));
            return $log;
        });

        $columns = [
            ['text' => 'Employee Name', 'dataIndex' => 'employee_name', 'width' => 250, 'align' => 'left'],
            ['text' => 'Day', 'dataIndex' => 'day', 'width' => 150, 'align' => 'center'],
            ['text' => 'Date', 'dataIndex' => 'date_formatted', 'width' => 150, 'align' => 'center'],
            [
                'text' => 'Clock In',
                'columns' => [
                    ['text' => 'Latitude', 'dataIndex' => 'clock_in_lat', 'width' => 150, 'align' => 'center'],
                    ['text' => 'Longitude', 'dataIndex' => 'clock_in_lng', 'width' => 150, 'align' => 'center'],
                    ['text' => 'Time', 'dataIndex' => 'clock_in_time_formatted', 'width' => 150, 'align' => 'center'],
                ]
            ],
            [
                'text' => 'Clock Out',
                'columns' => [
                    ['text' => 'Latitude', 'dataIndex' => 'clock_out_lat', 'width' => 150, 'align' => 'center'],
                    ['text' => 'Longitude', 'dataIndex' => 'clock_out_lng', 'width' => 150, 'align' => 'center'],
                    ['text' => 'Time', 'dataIndex' => 'clock_out_time_formatted', 'width' => 150, 'align' => 'center'],
                ]
            ],
            ['text' => 'Work Hours', 'dataIndex' => 'work_hours', 'width' => 200, 'align' => 'center'],
            ['text' => 'Status', 'dataIndex' => 'status_formatted', 'width' => 200, 'align' => 'center'],
            ['text' => 'Clock In Photo', 'dataIndex' => 'clock_in_link', 'width' => 300, 'align' => 'left'],
            ['text' => 'Clock Out Photo', 'dataIndex' => 'clock_out_link', 'width' => 300, 'align' => 'left']
        ];

        $filename = "Attendance-Report-{$startDate}-{$endDate}";

        $params = [
            'title' => $title,
            'columns' => $columns,
            'data' => $logs,
            'filename' => $filename,
            'footer' => [config('app.name') . ' | ' . date('Y')],
        ];

        return ExportExcel::export($params);
    }

    private function getAllChildOrganizations($parentId, &$visited = [])
    {
        if (in_array($parentId, $visited)) {
            return [];
        }

        $visited[] = $parentId;
        $childs = \App\Models\Organization::where('parent_id', $parentId)->pluck('id')->toArray();

        foreach ($childs as $childId) {
            $childs = array_merge($childs, $this->getAllChildOrganizations($childId, $visited));
        }

        return array_unique($childs);
    }


    private function calculateWorkHours($date, $clockIn, $clockOut)
    {
        if (!$clockIn || !$clockOut) {
            return ['text' => '-', 'hours' => 0];
        }

        $clockInTime = Carbon::hasFormat($clockIn, 'Y-m-d H:i:s') ? Carbon::parse($clockIn) : Carbon::parse("$date $clockIn");
        $clockOutTime = Carbon::hasFormat($clockOut, 'Y-m-d H:i:s') ? Carbon::parse($clockOut) : Carbon::parse("$date $clockOut");

        if ($clockOutTime->lt($clockInTime)) {
            $clockOutTime->addDay();
        }

        $diff = $clockOutTime->diff($clockInTime);
        $hoursDecimal = $clockOutTime->diffInHours($clockInTime) +
            ($clockOutTime->diffInMinutes($clockInTime) % 60) / 60;

        return [
            'text' => $diff->format('%h jam %i menit'),
            'hours' => round($hoursDecimal, 2)
        ];
    }

    private function getStatusClass($status)
    {
        switch ($status) {
            case 'on_time':
                return 'bg-success';
            case 'late':
                return 'bg-warning';
            case 'absent':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    public function update(Request $request)
    {
        $userRole = strtolower(optional(Auth::user()->role)->name);
        if (!in_array($userRole, ['hrga', 'developer', 'superadmin', 'administrator'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $request->validate([
            'id' => 'required|exists:iq_attendance,id',
            'clock_in' => 'nullable|string',
            'clock_out' => 'nullable|string',
        ]);

        $attendance = IqAttendance::findOrFail($request->id);
        $employee = IqEmployee::findOrFail($attendance->employee_id);

        DB::beginTransaction();
        try {
            if ($request->filled('clock_in')) {
                $attendance->clock_in_time = $attendance->date . ' ' . $request->clock_in . ':00';

                $status = 'on_time';
                $expectedTimeStr = $employee->shift_start_time ?? '09:00:00';
                $expectedTime = Carbon::createFromFormat('H:i:s', $expectedTimeStr)
                    ->setDateFrom(Carbon::parse($attendance->date));
                $lateThreshold = $expectedTime->copy()->addMinutes(10);

                $clockInCarbon = Carbon::parse($attendance->clock_in_time);
                if ($clockInCarbon->gt($lateThreshold)) {
                    $status = 'late';
                }
                $attendance->status = $status;
            } else {
                $attendance->clock_in_time = null;
                $attendance->status = 'absent';
            }

            if ($request->filled('clock_out')) {
                $attendance->clock_out_time = $attendance->date . ' ' . $request->clock_out . ':00';
            } else {
                $attendance->clock_out_time = null;
            }

            $attendance->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $userRole = strtolower(optional(Auth::user()->role)->name);
        if (!in_array($userRole, ['hrga', 'developer', 'superadmin', 'administrator'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $request->validate([
            'employee_id' => 'required|exists:iq_employ,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|string',
            'clock_out' => 'nullable|string',
        ], [
            'employee_id.required' => 'Karyawan wajib dipilih',
            'employee_id.exists' => 'Karyawan tidak ditemukan',
            'date.required' => 'Tanggal wajib diisi',
            'date.date' => 'Format tanggal tidak valid',
        ]);

        $employee = IqEmployee::findOrFail($request->employee_id);
        $dateStr = Carbon::parse($request->date)->format('Y-m-d');

        $existing = IqAttendance::where('employee_id', $employee->id)
            ->whereDate('date', $dateStr)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Data absensi untuk karyawan ini pada tanggal tersebut sudah ada.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $attendance = new IqAttendance();
            $attendance->employee_id = $employee->id;
            $attendance->date = $dateStr;

            if ($request->filled('clock_in')) {
                $attendance->clock_in_time = $dateStr . ' ' . $request->clock_in . ':00';

                $status = 'on_time';
                $expectedTimeStr = $employee->shift_start_time ?? '09:00:00';
                $expectedTime = Carbon::createFromFormat('H:i:s', $expectedTimeStr)
                    ->setDateFrom(Carbon::parse($dateStr));
                $lateThreshold = $expectedTime->copy()->addMinutes(10);

                $clockInCarbon = Carbon::parse($attendance->clock_in_time);
                if ($clockInCarbon->gt($lateThreshold)) {
                    $status = 'late';
                }
                $attendance->status = $status;
            } else {
                $attendance->clock_in_time = null;
                $attendance->status = 'absent';
            }

            if ($request->filled('clock_out')) {
                $attendance->clock_out_time = $dateStr . ' ' . $request->clock_out . ':00';
            } else {
                $attendance->clock_out_time = null;
            }

            $attendance->work_from = 'office';
            $attendance->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance record created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
