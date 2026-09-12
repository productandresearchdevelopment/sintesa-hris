<?php

namespace App\Controllers\Front\Leaves;

use App\Http\Controllers\Controller;
use App\Libraries\ExportExcel;
use App\Libraries\FileUpload;
use App\Models\GlobalData;
use App\Models\Leave as ModelsLeave;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Leave extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $params = [
            'user' => $user,
            'types' => GlobalData::where('group', 'leave_type')->get(),
            'currentEmployee' => $request->user()->employee
        ];

        $view = isMobile() ? '_front.leave.mobile' : '_front.leave.index';
        return view($view, $params);
    }

    public function data(Request $request)
    {
        $user = $request->user();
        $roleName = strtolower(optional($user->role)->name);
        $isHR = $roleName === 'hrga';
        $isSuper = in_array($roleName, ['developer', 'superadmin']);
        $canApproveRoute = $user->hasRoute('leave.approve') || $isSuper;

        $search = $request->input('search');
        $leaveTypeFilter = $request->input('leave_type');
        $statusFilter = $request->input('status');
        $viewFilter = $request->input('view');

        $query = $this->baseLeaveQuery();
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('type_id', 'like', "%{$search}%")
                    ->orWhere('start_date', 'like', "%{$search}%")
                    ->orWhere('end_date', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('type', fn($typeQ) => $typeQ->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('employee', fn($empQ) => $empQ->where('fullname', 'like', "%{$search}%"));
            });
        }

        if (!empty($leaveTypeFilter) && $leaveTypeFilter !== 'all') {
            $query->where('type_id', $leaveTypeFilter);
        }

        if (!empty($statusFilter) && $statusFilter !== 'all') {
            switch ($statusFilter) {
                case 'pending':
                    $query->whereNull('cancel_at')
                        ->whereNull('approved1_status')
                        ->whereNull('approved2_status')
                        ->whereNull('allowed_status');
                    break;
                case 'approved':
                    $query->where('allowed_status', 1);
                    break;
                case 'process':
                    $query->where('approved2_status', 1)
                        ->where('allowed_status', 0);
                    break;
                case 'checked':
                    $query->where('approved1_status', 1)
                        ->where('approved2_status', 0)
                        ->where('allowed_status', 0);
                    break;
                case 'rejected':
                    $query->where(function ($q) {
                        $q->where('approved1_status', 0)
                            ->orWhere('approved2_status', 0)
                            ->orWhere('allowed_status', 0);
                    });
                    break;
                case 'cancelled':
                    $query->whereNotNull('cancel_at');
                    break;
            }
        }

        if ($viewFilter === 'ongoing') {
            $query->where('end_date', '>=', now());
        } else {
            $query->where('end_date', '<', now());
        }

        $leaves = $query->get();

        $processedLeaves = $leaves->map(function ($leave) use ($user, $isHR, $isSuper, $canApproveRoute) {
            $employee = optional($leave->employee);
            $org = optional($employee->organization);
            $auth1 = optional($org->authorized1);
            $auth2 = optional($org->authorized2);
            $userOrgId = optional($user->employee)->org_id ?? optional($user->organization)->id;

            $auth1Id = $auth1->id ?? (is_scalar($org->authorized1) ? $org->authorized1 : null);
            $auth2Id = $auth2->id ?? (is_scalar($org->authorized2) ? $org->authorized2 : null);
            $singleEvaluator = $auth1Id && $auth2Id && $auth1Id === $auth2Id;

            if ($singleEvaluator) {
                $canEvaluate = (($auth1Id && $userOrgId && $auth1Id === $userOrgId) || $isSuper) && $canApproveRoute;
                $leave->setAttribute('single_evaluator', true);
                $leave->setAttribute('can_evaluate', $canEvaluate);
                $leave->setAttribute('can_approve_1', false);
                $leave->setAttribute('can_approve_2', false);
            } else {
                $canApprove1 = (($auth1Id && $userOrgId && $auth1Id === $userOrgId) || $isSuper) && $canApproveRoute;
                $canApprove2 = (($auth2Id && $userOrgId && $auth2Id === $userOrgId) || $isSuper) && $canApproveRoute;

                $leave->setAttribute('single_evaluator', false);
                $leave->setAttribute('can_evaluate', false);
                $leave->setAttribute('can_approve_1', $canApprove1);
                $leave->setAttribute('can_approve_2', $canApprove2);
            }

            $leave->setAttribute('is_super', $isSuper);
            $leave->setAttribute('is_user_leave', ($leave->created_by == $user->id));
            $leave->setAttribute('can_allow', $isHR || $isSuper);
            $leave->setAttribute('can_cancel', $leave->created_by == $user->id);

            $leave->setAttribute('can_view', false);
            if ($org && $userOrgId) {
                $orgPathIds = explode('/', trim($org->path, '/'));
                if (in_array($userOrgId, $orgPathIds)) {
                    $leave->setAttribute('can_view', true);
                }
            }
            if ($isSuper || $isHR) {
                $leave->setAttribute('can_view', true);
            }

            $leave->setAttribute(
                'profile_picture',
                $leave->createdBy?->photo_id
                    ? route('file', $leave->createdBy->photo_id)
                    : asset('images/nouser copy.png')
            );

            return $leave;
        });

        $processedLeaves = $processedLeaves->filter(function ($leave) {
            return $leave->is_user_leave
                || $leave->can_evaluate
                || $leave->can_approve_1
                || $leave->can_approve_2
                || $leave->can_allow
                || $leave->can_cancel
                || $leave->can_view;
        })->values();

        return response()->json(['data' => $processedLeaves]);
    }

    public function push(Request $request, $id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type_id' => 'required|integer|exists:iq_global_data,id',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'nullable|string',
                'duration' => 'nullable|numeric',
                'file_id' => 'nullable|file',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 400);
            }

            DB::beginTransaction();

            $employee = $request->user()->employee;
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee record not found for current user.'
                ], 404);
            }

            $currentLeaveSaldo = (float) $employee->leave_saldo;
            $typeId = $request->input('type_id');
            $leaveType = GlobalData::find($typeId);
            $flagReduce = $leaveType && isset($leaveType->property->flag_reduce_balance) && $leaveType->property->flag_reduce_balance === true;

            $duration = (float) $request->input('duration', 0);

            if ($flagReduce) {
                $projectedSaldo = $currentLeaveSaldo - $duration;
                if ($projectedSaldo < -5) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient leave balance. Current balance is {$currentLeaveSaldo} days, requested duration is {$duration} days, resulting in {$projectedSaldo} days balance which exceeds the allowed limit of -5 days.",
                    ], 400);
                }
            }

            if ($id) {
                $leave = ModelsLeave::find($id);
                if (!$leave) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Leave request not found.'
                    ], 404);
                }

                if ($leave->cancel_at) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot edit a leave request that has already been cancelled.'
                    ], 400);
                }

                if ($leave->allowed_status === 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot edit a leave request that has already been allowed by HR.'
                    ], 400);
                }

                $adjustedLeaveSaldo = $leave->leave_saldo;

                if ($request->has('file_id') && $request->file('file_id')) {
                    if ($leave->file_id) {
                        FileUpload::removeFileById($leave->file_id);
                    }

                    $file = FileUpload::upload('file_id', 'leave');
                    $request->merge(['file_id' => $file]);
                }

                $leave->update([
                    'type_id' => $request->input('type_id'),
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'duration' => $request->input('duration'),
                    'description' => $request->input('description'),
                    'leave_saldo' => $adjustedLeaveSaldo,
                    'file_id' => $request->input('file_id') ?? $leave->file_id,
                ]);
            } else {
                $newLeaveSaldo = $currentLeaveSaldo;

                if ($request->has('file_id') && $request->file('file_id')) {
                    $file = FileUpload::upload('file_id', 'leave');
                    $request->merge(['file_id' => $file]);
                }

                $leave = ModelsLeave::create([
                    'employ_id' => $employee->id,
                    'type_id' => $request->input('type_id'),
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'duration' => $request->input('duration'),
                    'description' => $request->input('description'),
                    'leave_saldo' => $newLeaveSaldo,
                    'file_id' => $request->input('file_id') ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave ' . ($id ? 'updated' : 'created') . ' successfully',
                'data' => $leave
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing leave request: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function get(Request $request, $id = null)
    {
        $leave = ModelsLeave::with([
            'type',
            'file',
            'approver1',
            'approver2',
            'allowed',
            'createdBy',
            'employee',
            'employee.organization',
            'employee.organization.authorized1',
            'employee.organization.authorized2'
        ])->find($id);

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave request not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $leave,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: Notes field is required for approval.',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();

        try {
            $leave = ModelsLeave::find($id);
            if (!$leave) {
                return response()->json(['success' => false, 'message' => 'Leave request not found.'], 404);
            }

            if ($leave->cancel_at) {
                return response()->json(['success' => false, 'message' => 'Cannot approve a leave request that has already been cancelled.'], 400);
            }

            $user = $request->user();
            $roleName = strtolower(optional($user->role)->name);
            $isHR = $roleName === 'hrga';
            $isSuper = in_array($roleName, ['developer', 'superadmin']);

            $notes = $request->input('notes');
            $employee = $leave->employee;

            $leaveType = $leave->type;
            $flagReduce = $leaveType && isset($leaveType->property->flag_reduce_balance) && $leaveType->property->flag_reduce_balance === true;

            $orgId = optional($user->employee)->org_id ?? optional($user->organization)->id;
            $auth1Id = optional($leave->employee->organization)->authorized1;
            $auth2Id = optional($leave->employee->organization)->authorized2;
            $isSingleEvaluator = $auth1Id && $auth2Id && $auth1Id === $auth2Id;

            if ($leave->approved1_status === 1 && $leave->approved2_status === 1) {
                if ($isHR || $isSuper) {
                    if ($leave->allowed_status === 1) {
                        return response()->json(['success' => false, 'message' => 'This leave request has already been allowed by HR.'], 400);
                    }

                    if ($flagReduce) {
                        $newLeaveSaldo = $employee->leave_saldo - $leave->duration;
                        if ($newLeaveSaldo < -5) {
                            return response()->json([
                                'success' => false,
                                'message' => "Insufficient leave balance. Remaining balance ({$newLeaveSaldo} days) exceeds minimum limit of -5 days."
                            ], 400);
                        }
                        $employee->update(['leave_saldo' => $newLeaveSaldo]);
                        $leave->update(['leave_saldo' => $newLeaveSaldo]);
                    }
                    $this->updateLeaveStatus($leave, 'allowed', 1, $user, $notes);
                } else {
                    return response()->json(['success' => false, 'message' => 'This leave request has already been evaluated by supervisor/manager and is awaiting final HR allowance.'], 400);
                }
            } elseif ($isSingleEvaluator) {
                if ($auth1Id !== $orgId && !$isSuper) {
                    return response()->json(['success' => false, 'message' => 'You are not authorized as the evaluator for this employee.'], 403);
                }
                $this->updateLeaveStatus($leave, 'approved1', 1, $user, $notes);
                $this->updateLeaveStatus($leave, 'approved2', 1, $user, $notes);
            } elseif (($auth1Id === $orgId || $isSuper) && $leave->approved1_status === null) {
                $this->updateLeaveStatus($leave, 'approved1', 1, $user, $notes);
            } elseif (($auth2Id === $orgId || $isSuper) && $leave->approved2_status === null) {
                if ($leave->approved1_status === null && !$isSuper) {
                    return response()->json(['success' => false, 'message' => 'Step 1 approval by direct supervisor is still pending.'], 400);
                }
                $this->updateLeaveStatus($leave, 'approved2', 1, $user, $notes);
            } elseif ($isHR || $isSuper) {
                if ($leave->allowed_status === 1) {
                    return response()->json(['success' => false, 'message' => 'This leave request has already been allowed by HR.'], 400);
                }

                if (!$isSuper) {
                    if ($auth1Id && $leave->approved1_status !== 1) {
                        return response()->json(['success' => false, 'message' => 'Cannot allow leave: Step 1 approval by supervisor is still pending.'], 400);
                    }
                    if ($auth2Id && $leave->approved2_status !== 1) {
                        return response()->json(['success' => false, 'message' => 'Cannot allow leave: Step 2 approval by manager is still pending.'], 400);
                    }
                }

                if ($flagReduce) {
                    $newLeaveSaldo = $employee->leave_saldo - $leave->duration;
                    if ($newLeaveSaldo < -5) {
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient leave balance. Remaining balance ({$newLeaveSaldo} days) exceeds minimum limit of -5 days."
                        ], 400);
                    }
                    $employee->update(['leave_saldo' => $newLeaveSaldo]);
                    $leave->update(['leave_saldo' => $newLeaveSaldo]);
                }
                $this->updateLeaveStatus($leave, 'allowed', 1, $user, $notes);
            } else {
                if ($auth1Id === $orgId && $leave->approved1_status !== null) {
                    return response()->json(['success' => false, 'message' => 'You have already approved Step 1 for this leave request.'], 400);
                }
                if ($auth2Id === $orgId && $leave->approved2_status !== null) {
                    return response()->json(['success' => false, 'message' => 'You have already approved Step 2 for this leave request.'], 400);
                }
                return response()->json(['success' => false, 'message' => 'You are not authorized to approve this leave request or the current step is not assigned to you.'], 403);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Leave approved successfully', 'data' => $leave]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error approving leave: ' . $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: Notes field is required for rejection.',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();

        try {
            $leave = ModelsLeave::find($id);
            if (!$leave) {
                return response()->json(['success' => false, 'message' => 'Leave request not found.'], 404);
            }

            if ($leave->cancel_at) {
                return response()->json(['success' => false, 'message' => 'Cannot reject a leave request that has already been cancelled.'], 400);
            }

            $user = $request->user();
            $roleName = strtolower(optional($user->role)->name);
            $isHR = $roleName === 'hrga';
            $isSuper = in_array($roleName, ['developer', 'superadmin']);

            $notes = $request->input('notes');
            $employee = $leave->employee;

            $leaveType = $leave->type;
            $flagReduce = $leaveType && isset($leaveType->property->flag_reduce_balance) && $leaveType->property->flag_reduce_balance === true;

            $orgId = optional($user->employee)->org_id ?? optional($user->organization)->id;
            $auth1Id = optional($leave->employee->organization)->authorized1;
            $auth2Id = optional($leave->employee->organization)->authorized2;
            $isSingleEvaluator = $auth1Id && $auth2Id && $auth1Id === $auth2Id;

            if ($leave->approved1_status === 1 && $leave->approved2_status === 1) {
                if ($isHR || $isSuper) {
                    if ($leave->allowed_status === 1 && $flagReduce) {
                        $employee->update(['leave_saldo' => $employee->leave_saldo + $leave->duration]);
                        $leave->update(['leave_saldo' => $employee->leave_saldo + $leave->duration]);
                    }
                    $this->updateLeaveStatus($leave, 'allowed', 0, $user, $notes);
                } else {
                    return response()->json(['success' => false, 'message' => 'This leave request has already been evaluated by supervisor/manager. Rejection requires HR/Superadmin.'], 403);
                }
            } elseif ($isSingleEvaluator) {
                if ($auth1Id !== $orgId && !$isSuper) {
                    return response()->json(['success' => false, 'message' => 'You are not authorized to reject this leave request.'], 403);
                }
                $this->updateLeaveStatus($leave, 'approved1', 0, $user, $notes);
                $this->updateLeaveStatus($leave, 'approved2', 0, $user, $notes);
            } elseif (($auth1Id === $orgId || $isSuper) && $leave->approved1_status === null) {
                $this->updateLeaveStatus($leave, 'approved1', 0, $user, $notes);
            } elseif (($auth2Id === $orgId || $isSuper) && $leave->approved2_status === null) {
                $this->updateLeaveStatus($leave, 'approved2', 0, $user, $notes);
            } elseif ($isHR || $isSuper) {
                if ($leave->allowed_status === null) {
                    $this->updateLeaveStatus($leave, 'allowed', 0, $user, $notes);
                } else {
                    if ($leave->allowed_status === 1 && $flagReduce) {
                        $employee->update(['leave_saldo' => $employee->leave_saldo + $leave->duration]);
                        $leave->update(['leave_saldo' => $employee->leave_saldo + $leave->duration]);
                    }
                    $this->updateLeaveStatus($leave, 'allowed', 0, $user, $notes);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'You are not authorized to reject this leave request or it has already been processed.'], 403);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Leave rejected successfully', 'data' => $leave]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to reject leave: ' . $e->getMessage()], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: Notes field is required for cancellation.',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $leave = ModelsLeave::find($id);

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found.'
                ], 404);
            }

            $user = $request->user();
            $roleName = strtolower(optional($user->role)->name);
            $isSuper = in_array($roleName, ['developer', 'superadmin']);

            if ($leave->created_by != $user->id && !$isSuper) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You can only cancel your own leave requests.'
                ], 403);
            }

            if ($leave->cancel_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'This leave request has already been cancelled.'
                ], 400);
            }

            if ($leave->allowed_status === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel a leave request that has already been approved by HR.'
                ], 400);
            }

            $leave->update([
                'cancel_at' => now(),
                'cancel_note' => $request->input('notes')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave request cancelled successfully',
                'data' => $leave
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel leave: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        ini_set('memory_limit', '64048M');
        ini_set('max_execution_time', '300');

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $user = $request->user();

        $title = [['LIST LEAVE REQUEST', 'h2']];
        if ($startDate && $endDate && $startDate !== 'null' && $endDate !== 'null') {
            $title[] = ["{$startDate} - {$endDate}", 'h4'];
        } elseif ($startDate && $startDate !== 'null') {
            $title[] = [$startDate, 'h4'];
        } elseif ($endDate && $endDate !== 'null') {
            $title[] = [$endDate, 'h4'];
        }

        $query = $this->baseLeaveQuery();
        if (!empty($startDate) && $startDate !== 'null') {
            $query->whereDate('start_date', '>=', $startDate);
        }
        if (!empty($endDate) && $endDate !== 'null') {
            $query->whereDate('end_date', '<=', $endDate);
        }

        $roleName = strtolower(optional($user->role)->name);
        if (!in_array($roleName, ['hrga', 'developer', 'superadmin'])) {
            $userOrgId = $user->organization->id ?? null;
            if ($userOrgId) {
                $allOrgs = Organization::all()->keyBy('id');

                $getChildOrgIds = function ($orgId) use ($allOrgs, &$getChildOrgIds) {
                    $children = $allOrgs->where('parent_id', $orgId);
                    $ids = [$orgId];
                    foreach ($children as $child) {
                        $ids = array_merge($ids, $getChildOrgIds($child->id));
                    }
                    return $ids;
                };

                $accessibleOrgIds = $getChildOrgIds($userOrgId);
                $query->whereHas('employee.organization', function ($q) use ($accessibleOrgIds) {
                    $q->whereIn('id', $accessibleOrgIds);
                });
            } else {
                $query->whereRaw('1=0');
            }
        }

        $leaves = $query->get();

        $columns = [
            ['text' => 'STATUS', 'dataIndex' => 'status', 'width' => 120, 'align' => 'center'],
            ['text' => 'LEAVE TYPE', 'dataIndex' => 'leave_type', 'width' => 150],
            ['text' => 'NIK', 'dataIndex' => 'nik', 'width' => 120, 'align' => 'center'],
            ['text' => 'REQUEST BY', 'dataIndex' => 'employee_name', 'width' => 180],
            ['text' => 'POSITION', 'dataIndex' => 'position', 'width' => 150],
            ['text' => 'WORKPLACE', 'dataIndex' => 'workplace', 'width' => 150],
            ['text' => 'SUPERVISOR', 'dataIndex' => 'supervisor', 'width' => 180],
            ['text' => 'BALANCE LEAVE', 'dataIndex' => 'balance_leave', 'width' => 120, 'align' => 'center'],
            ['text' => 'START DATE', 'dataIndex' => 'start_date', 'width' => 150, 'type' => 'date', 'align' => 'center'],
            ['text' => 'FINISH DATE', 'dataIndex' => 'end_date', 'width' => 150, 'type' => 'date', 'align' => 'center'],
            ['text' => 'CHECKED BY', 'dataIndex' => 'checked_by', 'width' => 150],
            ['text' => 'APPROVED BY', 'dataIndex' => 'approved_by', 'width' => 150],
            ['text' => 'REJECT BY', 'dataIndex' => 'rejected_by', 'width' => 150],
            ['text' => 'CLARIFIED BY', 'dataIndex' => 'clarified_by', 'width' => 150],
            ['text' => 'DESCRIPTION', 'dataIndex' => 'description', 'width' => 250],
        ];

        $formattedData = $leaves->map(function ($item) {
            if (!empty($item['cancel_at'])) {
                $status = 'CANCELLED';
            } elseif ($item['allowed_status'] == 1) {
                $status = 'APPROVED';
            } elseif ($item['allowed_status'] === 0 || $item['approved1_status'] === 0 || $item['approved2_status'] === 0) {
                $status = 'REJECTED';
            } elseif ($item['approved2_status'] == 1) {
                $status = 'PROCESS';
            } elseif ($item['approved1_status'] == 1) {
                $status = 'CHECKED';
            } else {
                $status = 'PENDING';
            }

            $employee = $item['employee'] ?? [];
            $organization = $employee['organization'] ?? [];
            $office = $employee['office'] ?? [];
            $approver1 = $item['approver1']['name'] ?? '-';
            $approver2 = $item['approver2']['name'] ?? '-';
            $allowed = $item['allowed']['name'] ?? '-';
            $supervisor = $organization['authorized1']['name'] ?? '-';
            $position = $organization['name'] ?? '-';
            $workplace = $office['name'] ?? '-';

            $rejectedBy = '-';
            if ($item['approved1_status'] === 0) {
                $rejectedBy = $approver1;
            } elseif ($item['approved2_status'] === 0) {
                $rejectedBy = $approver2;
            } elseif ($item['allowed_status'] === 0) {
                $rejectedBy = $allowed;
            }

            return (object)[
                'status'         => $status,
                'leave_type'     => $item['type']['name'] ?? '-',
                'nik'            => $employee['nik'] ?? '-',
                'employee_name'  => $employee['fullname'] ?? '-',
                'position'       => $position,
                'workplace'      => $workplace,
                'supervisor'     => $supervisor,
                'balance_leave'  => $employee['leave_saldo'] ?? 0,
                'start_date'     => $item['start_date'] ?? '-',
                'end_date'       => $item['end_date'] ?? '-',
                'checked_by'     => $approver1,
                'approved_by'    => $approver2,
                'rejected_by'    => $rejectedBy,
                'clarified_by'   => $allowed,
                'description'    => $item['description'] ?? '-',
                'cancel_at'      => $item['cancel_at'] ?? null,
                'cancel_note'    => $item['cancel_note'] ?? null,
            ];
        })->toArray();

        $params = [
            'title'    => $title,
            'columns'  => $columns,
            'data'     => $formattedData,
            'filename' => 'Leave Request-' . date('YmdHi'),
            'footer'   => [config('app.name') . ' (' . date('d F Y H:i:s') . ')'],
        ];

        return ExportExcel::export($params);
    }

    protected function updateLeaveStatus($leave, $stage, $status, $user, $notes)
    {
        $leave->update([
            "{$stage}_status" => $status,
            "{$stage}_at" => now(),
            "{$stage}_by" => $user->id,
            "{$stage}_note" => $notes,
        ]);
    }

    private function baseLeaveQuery()
    {
        return ModelsLeave::with([
            'employee.office',
            'type',
            'file',
            'approver1',
            'approver2',
            'allowed',
            'createdBy',
            'employee',
            'employee.organization',
            'employee.organization.authorized1',
            'employee.organization.authorized2'
        ]);
    }
}
