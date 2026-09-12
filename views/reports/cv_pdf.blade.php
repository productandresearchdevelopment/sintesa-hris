<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Employee CV - {{ $data->fullname }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #1a1a1a;
      background: #fff;
      padding: 24px 28px;
      line-height: 1.4;
    }

    .header {
      display: flex;
      align-items: center;
      gap: 20px;
      padding-bottom: 16px;
      border-bottom: 3px solid #1a3a5c;
      margin-bottom: 20px;
    }

    .header-photo {
      flex-shrink: 0;
    }

    .profile-photo {
      width: 90px;
      height: 90px;
      border-radius: 6px;
      object-fit: cover;
      border: 2px solid #1a3a5c;
    }

    .photo-placeholder {
      width: 90px;
      height: 90px;
      border-radius: 6px;
      background: #e8eef5;
      border: 2px solid #1a3a5c;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: #1a3a5c;
      font-weight: bold;
    }

    .header-info {
      flex: 1;
    }

    .header-name {
      font-size: 22px;
      font-weight: bold;
      color: #1a3a5c;
      margin-bottom: 3px;
    }

    .header-nickname {
      font-size: 13px;
      color: #555;
      margin-bottom: 6px;
    }

    .header-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 6px 18px;
      font-size: 10.5px;
      color: #444;
      margin-bottom: 12px;
    }

    .header-badge {
      display: inline-block;
      padding: 2px 10px;
      border-radius: 3px;
      font-size: 10px;
      font-weight: bold;
      letter-spacing: 0.5px;
      max-width: 80px;
    }

    .badge-active {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .badge-inactive {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .section {
      margin-bottom: 16px;
      break-inside: avoid;
    }

    .section-header {
      background: #1a3a5c;
      color: #fff;
      font-size: 11px;
      font-weight: bold;
      letter-spacing: 0.8px;
      padding: 5px 10px;
      text-transform: uppercase;
      margin-bottom: 0;
    }

    .kv-table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #c8d3df;
    }

    .kv-table tr:nth-child(even) {
      background: #f5f8fc;
    }

    .kv-table td {
      padding: 5px 9px;
      border-bottom: 1px solid #dde4ed;
      vertical-align: top;
    }

    .kv-table td.kv-label {
      font-weight: bold;
      color: #1a3a5c;
      width: 30%;
      border-right: 1px solid #dde4ed;
      white-space: nowrap;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #c8d3df;
    }

    .data-table thead tr {
      background: #2c5282;
      color: #fff;
    }

    .data-table thead th {
      padding: 5px 8px;
      font-size: 10px;
      font-weight: bold;
      text-align: left;
      letter-spacing: 0.4px;
      border-right: 1px solid #3d6899;
      white-space: nowrap;
    }

    .data-table thead th:last-child {
      border-right: none;
    }

    .data-table tbody tr:nth-child(even) {
      background: #f0f5fb;
    }

    .data-table tbody tr:nth-child(odd) {
      background: #fff;
    }

    .data-table tbody td {
      padding: 5px 8px;
      border-bottom: 1px solid #dde4ed;
      border-right: 1px solid #dde4ed;
      vertical-align: top;
      font-size: 10.5px;
    }

    .data-table tbody td:last-child {
      border-right: none;
    }

    .data-table tbody td.td-no {
      text-align: center;
      width: 28px;
      color: #555;
      font-weight: bold;
    }

    .data-table tbody td.td-center {
      text-align: center;
    }

    .data-table tbody tr:last-child td {
      border-bottom: none;
    }

    .two-col {
      display: table;
      width: 100%;
      border-collapse: collapse;
    }

    .two-col-left,
    .two-col-right {
      display: table-cell;
      width: 50%;
      vertical-align: top;
      padding-right: 8px;
    }

    .two-col-right {
      padding-right: 0;
      padding-left: 8px;
    }

    .status-yes {
      color: #155724;
      font-weight: bold;
    }

    .status-no {
      color: #721c24;
    }

    .empty-row td {
      text-align: center;
      color: #999;
      font-style: italic;
      padding: 10px;
    }

    .footer {
      margin-top: 24px;
      padding-top: 10px;
      border-top: 1px solid #dde4ed;
      font-size: 9.5px;
      color: #888;
      display: flex;
      justify-content: space-between;
    }
  </style>
</head>

<body>

  @php
    $now = now();
    $terminate = ['RESIGN', 'TERMINATE', 'RETIREMENT'];
    $lastContract = $data->last_contract ?? null;
    $contractStatus = $lastContract?->status?->name ?? null;
    $contractStatusUpper = strtoupper($contractStatus ?? '');
    $endDate = $lastContract?->end_date ? \Carbon\Carbon::parse($lastContract->end_date) : null;

    if (!$lastContract || !$contractStatus) {
        $isActive = false;
    } elseif (in_array($contractStatusUpper, $terminate)) {
        $isActive = false;
    } elseif (in_array($contractStatusUpper, ['PERMANENT', 'FREELANCE'])) {
        $isActive = true;
    } elseif ($endDate) {
        $isActive = $endDate->greaterThanOrEqualTo($now);
    } else {
        $isActive = false;
    }

    $initials = collect(explode(' ', $data->fullname))
        ->take(2)
        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
        ->implode('');
  @endphp

  <div class="header">
    <div class="header-photo">
      @if ($photoUrl && file_exists($photoUrl))
        <img src="{{ $photoUrl }}" alt="Photo" class="profile-photo">
      @else
        <div class="photo-placeholder">{{ $initials }}</div>
      @endif
    </div>
    <div class="header-info">
      <div class="header-name">{{ $data->fullname }}</div>
      @if ($data->nickname)
        <div class="header-nickname">"{{ $data->nickname }}"</div>
      @endif
      <div class="header-meta">
        <span class="header-badge {{ $isActive ? 'badge-active' : 'badge-inactive' }}">
          {{ $isActive ? 'ACTIVE' : 'INACTIVE' }}
        </span>
      </div>
    </div>

    <div class="two-col" style="margin-bottom:16px;">
      <div class="two-col-left">
        <div class="section">
          <div class="section-header">Personal Information</div>
          <table class="kv-table">
            <tr>
              <td class="kv-label">NIK</td>
              <td>{{ $data->nik ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Birth Place & Date</td>
              <td>{{ $data->birth_place ?? '-' }},
                {{ $data->birth_date ? \Carbon\Carbon::parse($data->birth_date)->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Phone</td>
              <td>{{ $data->phone ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Email</td>
              <td>{{ $data->email ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Gender</td>
              <td>{{ $data->gender?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Marital Status</td>
              <td>{{ $data->marital?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Religion</td>
              <td>{{ $data->religion?->name ?? '-' }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="two-col-right">
        <div class="section">
          <div class="section-header">Address Information</div>
          <table class="kv-table">
            <tr>
              <td class="kv-label">Current Address</td>
              <td>{{ $data->address ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">City</td>
              <td>{{ $data->address_city?->city ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Province</td>
              <td>{{ $data->address_province?->province ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Permanent Address</td>
              <td>{{ $data->address_permanent ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Permanent City</td>
              <td>{{ $data->address_permanent_city?->city ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Permanent Province</td>
              <td>{{ $data->address_permanent_province?->province ?? '-' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="two-col" style="margin-bottom:16px;">
      <div class="two-col-left">
        <div class="section">
          <div class="section-header">Employment Information</div>
          <table class="kv-table">
            <tr>
              <td class="kv-label">Company</td>
              <td>{{ $data->company?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Organization</td>
              <td>{{ $data->organization?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Division</td>
              <td>{{ $data->division?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Placement</td>
              <td>{{ $data->placement?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Join Date</td>
              <td>{{ $data->join_date ? \Carbon\Carbon::parse($data->join_date)->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Leave Balance</td>
              <td>{{ $data->leave_saldo ?? '-' }} days</td>
            </tr>
            <tr>
              <td class="kv-label">Last Career</td>
              <td>{{ $data->last_career?->career?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Contract Status</td>
              <td>{{ $data->last_contract?->status?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Contract End</td>
              <td>
                {{ $data->last_contract?->end_date ? \Carbon\Carbon::parse($data->last_contract->end_date)->format('d F Y') : '-' }}
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="two-col-right">
        <div class="section">
          <div class="section-header">Bank Information</div>
          <table class="kv-table">
            <tr>
              <td class="kv-label">Bank</td>
              <td>{{ $data->bank?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Account Number</td>
              <td>{{ $data->bank_account ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Account Alias</td>
              <td>{{ $data->bank_alias ?? ($data->bank?->alias ?? '-') }}</td>
            </tr>
          </table>
        </div>

        <div class="section" style="margin-top:10px;">
          <div class="section-header">Emergency Contact</div>
          <table class="kv-table">
            <tr>
              <td class="kv-label">Relation</td>
              <td>{{ $data->emergency_relation?->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Name</td>
              <td>{{ $data->emergency_contact_name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Phone</td>
              <td>{{ $data->emergency_contact_phone ?? '-' }}</td>
            </tr>
            <tr>
              <td class="kv-label">Address</td>
              <td>{{ $data->emergency_contact_address ?? '-' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">Contracts</div>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width:28px;">No.</th>
            <th>Contract Status</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($data->contracts as $index => $contract)
            <tr>
              <td class="td-no">{{ $index + 1 }}</td>
              <td>{{ $contract->status?->name ?? '-' }}</td>
              <td class="td-center">
                {{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d M Y') : '-' }}</td>
              <td class="td-center">
                {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d M Y') : '-' }}</td>
              <td>{{ $contract->description ?? '-' }}</td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="5">No contract data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="section">
      <div class="section-header">Citizens / Identity Documents</div>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width:28px;">No.</th>
            <th>Type</th>
            <th>Value / Number</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($data->citizens as $index => $citizen)
            <tr>
              <td class="td-no">{{ $index + 1 }}</td>
              <td>{{ $citizen->citizen?->name ?? '-' }}</td>
              <td>{{ $citizen->value ?? '-' }}</td>
              <td>{{ $citizen->description ?? '-' }}</td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="4">No citizen data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="section">
      <div class="section-header">Education</div>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width:28px;">No.</th>
            <th>Level</th>
            <th>Major</th>
            <th>Institution</th>
            <th>Graduate Year</th>
            <th style="width:50px;">GPA</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($data->educations as $index => $education)
            <tr>
              <td class="td-no">{{ $index + 1 }}</td>
              <td>{{ $education->education?->name ?? '-' }}</td>
              <td>{{ $education->major?->name ?? '-' }}</td>
              <td>{{ $education->institution ?? '-' }}</td>
              <td class="td-center">
                {{ $education->graduate ? \Carbon\Carbon::parse($education->graduate)->format('Y') : '-' }}</td>
              <td class="td-center">{{ $education->ipk ?? '-' }}</td>
              <td>{{ $education->description ?? '-' }}</td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="7">No education data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="section">
      <div class="section-header">Family</div>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width:28px;">No.</th>
            <th>Relation</th>
            <th>Name</th>
            <th>NIK</th>
            <th>Birth Date</th>
            <th>Occupation</th>
            <th>Occ. Description</th>
            <th>Address</th>
            <th>Phone</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($data->families as $index => $family)
            <tr>
              <td class="td-no">{{ $index + 1 }}</td>
              <td>{{ $family->relation?->name ?? '-' }}</td>
              <td>{{ $family->name ?? '-' }}</td>
              <td class="td-center">{{ $family->nik ?? '-' }}</td>
              <td class="td-center">
                {{ isset($family->birth_date) ? \Carbon\Carbon::parse($family->birth_date)->format('d M Y') : '-' }}
              </td>
              <td>{{ $family->occupation?->name ?? '-' }}</td>
              <td>{{ $family->occupation_description ?? '-' }}</td>
              <td>{{ $family->address ?? '-' }}</td>
              <td class="td-center">{{ $family->phone ?? '-' }}</td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="9">No family data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="section">
      <div class="section-header">Job Experience</div>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width:28px;">No.</th>
            <th>Company</th>
            <th>Job Title</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Salary</th>
            <th>Job Description</th>
            <th>Reason Leaving</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($data->job_experiences as $index => $experience)
            <tr>
              <td class="td-no">{{ $index + 1 }}</td>
              <td>{{ $experience->name ?? '-' }}</td>
              <td>{{ $experience->job_title ?? '-' }}</td>
              <td class="td-center">
                {{ $experience->start_date ? \Carbon\Carbon::parse($experience->start_date)->format('d M Y') : '-' }}
              </td>
              <td class="td-center">
                {{ $experience->end_date ? \Carbon\Carbon::parse($experience->end_date)->format('d M Y') : '-' }}</td>
              <td class="td-center">
                {{ $experience->salary ? number_format($experience->salary, 0, ',', '.') : '-' }}</td>
              <td>{{ $experience->job_description ?? '-' }}</td>
              <td>{{ $experience->reason_leaving ?? '-' }}</td>
              <td>{{ $experience->description ?? '-' }}</td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="9">No job experience data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="section">
      <div class="section-header">Career History</div>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width:28px;">No.</th>
            <th>Career / Position</th>
            <th>Placement</th>
            <th>Organization</th>
            <th>Date</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($data->careers as $index => $career)
            <tr>
              <td class="td-no">{{ $index + 1 }}</td>
              <td>{{ $career->career?->name ?? '-' }}</td>
              <td>{{ $career->placement?->name ?? '-' }}</td>
              <td>{{ $career->organization?->name ?? '-' }}</td>
              <td class="td-center">
                {{ $career->date ? \Carbon\Carbon::parse($career->date)->format('d M Y') : '-' }}</td>
              <td>{{ $career->description ?? '-' }}</td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="6">No career data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="section">
      <div class="section-header">Training</div>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width:28px;">No.</th>
            <th>Title</th>
            <th>Location</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Description</th>
            <th style="width:55px;">Internal</th>
            <th style="width:70px;">Certification</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($data->trainings as $index => $training)
            <tr>
              <td class="td-no">{{ $index + 1 }}</td>
              <td>{{ $training->title ?? '-' }}</td>
              <td>{{ $training->location ?? '-' }}</td>
              <td class="td-center">
                {{ $training->start_date ? \Carbon\Carbon::parse($training->start_date)->format('d M Y') : '-' }}</td>
              <td class="td-center">
                {{ $training->end_date ? \Carbon\Carbon::parse($training->end_date)->format('d M Y') : '-' }}</td>
              <td>{{ $training->description ?? '-' }}</td>
              <td class="td-center {{ $training->is_internal ? 'status-yes' : 'status-no' }}">
                {{ $training->is_internal ? 'Yes' : 'No' }}
              </td>
              <td class="td-center {{ $training->is_certification ? 'status-yes' : 'status-no' }}">
                {{ $training->is_certification ? 'Yes' : 'No' }}
              </td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="8">No training data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="footer">
      <span>{{ config('app.name') }}</span>
      <span>Generated: {{ now()->format('d F Y, H:i:s') }}</span>
    </div>

</body>

</html>
