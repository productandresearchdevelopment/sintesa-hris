<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appraisal Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      background-color: #f8f9fa;
      margin: 20px;
      padding: 10px;
    }

    .header {
      text-align: center;
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 20px;
      padding-bottom: 5px;
      border-bottom: 2px solid #aaa;
      color: #333;
    }

    .section {
      background: #fff;
      padding: 10px;
      border-radius: 6px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      margin-bottom: 15px;
    }

    .section-title {
      font-size: 13px;
      font-weight: bold;
      color: #555;
      border-bottom: 1px solid #ddd;
      padding-bottom: 3px;
      margin-bottom: 8px;
    }

    .info p {
      margin: 2px 0;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table,
    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 6px;
      text-align: center;
    }

    .table th {
      background-color: #eee;
      font-weight: bold;
    }

    .table td {
      background-color: #fafafa;
    }

    .table tfoot th {
      text-align: left;
    }
  </style>
</head>

<body>

  <div class="header">Appraisal Report</div>

  {{-- Employee & Evaluators --}}
  @php
    $auth1Id = $employee->organization->authorized1_id ?? ($employee->organization->authorized1->id ?? ($employee->organization->authorized1 ?? null));
    $auth2Id = $employee->organization->authorized2_id ?? ($employee->organization->authorized2->id ?? ($employee->organization->authorized2 ?? null));
    $isTwoEvaluators = $auth1Id && $auth2Id && (string)$auth1Id !== (string)$auth2Id;

    if ($isTwoEvaluators) {
      $infoSections = [
          'Employee Information' => $employee,
          'Evaluator 1 Information' => $appraisal['evaluator1'] ?? [],
          'Evaluator 2 Information' => $appraisal['evaluator2'] ?? [],
      ];
    } else {
      $infoSections = [
          'Employee Information' => $employee,
          'Evaluator Information' => $appraisal['evaluator1'] ?? ($appraisal['evaluator2'] ?? []),
      ];
    }
  @endphp

  @foreach ($infoSections as $title => $data)
    <div class="section">
      <div class="section-title">{{ $title }}</div>
      <div class="info">
        <p><strong>Full Name:</strong> {{ $data['fullname'] ?? '-' }}</p>
        <p><strong>NIK:</strong> {{ $data['nik'] ?? '-' }}</p>
        <p><strong>Birth Place & Date:</strong> {{ $data['birth_place'] ?? '-' }}, {{ $data['birth_date'] ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $data['email'] ?? '-' }}</p>
        <p><strong>Phone:</strong> {{ $data['phone'] ?? '-' }}</p>
        <p><strong>Organization:</strong> {{ $data['organization']['name'] ?? '-' }}</p>
        <p><strong>Join Date:</strong> {{ $data['join_date'] ?? '-' }}</p>
        <p><strong>Address:</strong> {{ $data['address'] ?? '-' }}</p>
      </div>
    </div>
  @endforeach

  {{-- Appraisal Info --}}
  <div class="section">
    <div class="section-title">Appraisal Information</div>
    <div class="info">
      <p><strong>Period:</strong> {{ $appraisal['period'] ?? '-' }}</p>
      <p><strong>Semester:</strong> {{ $appraisal['smester'] ?? '-' }}</p>
    </div>
  </div>

  {{-- Appraisal Summary --}}
  <div class="section">
    <div class="section-title">Appraisal Summary</div>
    <table class="table">
      <thead>
        <tr>
          <th>Category</th>
          <th>Weight</th>
          @if ($isTwoEvaluators)
            <th>Evaluator 1</th>
            <th>Evaluator 2</th>
          @else
            <th>Evaluator</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @php
          $categories = [
              'Technical Ability' => ['tech_weight', 'tech_eval1_point', 'tech_eval2_point'],
              'Behavior & Work' => ['behavior_weight', 'behavior_eval1_point', 'behavior_eval2_point'],
              'Leadership' => ['leadership_weight', 'leadership_eval1_point', 'leadership_eval2_point'],
          ];
        @endphp

        @foreach ($categories as $label => [$weightKey, $eval1Key, $eval2Key])
          @if (($appraisal[$weightKey] ?? 0) > 0 || isset($appraisal[$eval1Key]) || isset($appraisal[$eval2Key]))
            <tr>
              <td>{{ $label }}</td>
              <td>{{ isset($appraisal[$weightKey]) ? $appraisal[$weightKey] . '%' : '-' }}</td>
              @if ($isTwoEvaluators)
                <td>{{ $appraisal[$eval1Key] ?? '-' }}</td>
                <td>{{ $appraisal[$eval2Key] ?? '-' }}</td>
              @else
                <td>{{ $appraisal[$eval1Key] ?? ($appraisal[$eval2Key] ?? '-') }}</td>
              @endif
            </tr>
          @endif
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th colspan="{{ $isTwoEvaluators ? 3 : 2 }}" style="text-align: center">Final Score</th>
          <td>{{ $appraisal['final_score'] ?? 0 }}</td>
        </tr>
        <tr>
          <th colspan="{{ $isTwoEvaluators ? 3 : 2 }}" style="text-align: center">Final Grade</th>
          <td>{{ $appraisal['final_grade'] ?? 'N/A' }}</td>
        </tr>
      </tfoot>
    </table>
  </div>

  {{-- Appraisal Detail Placeholder --}}
  <div class="section">
    <div class="section-title">Appraisal Detail</div>

    @php
      $appraisalDetails = [
          'Technical Ability & Work Result' => [
              'category_id' => 1,
              'label' => 'KPI',
              'show_target' => true,
          ],
          'Behavior & Work' => [
              'category_id' => 2,
              'label' => 'Dimension',
              'show_target' => true,
          ],
          'Leadership' => [
              'category_id' => 3,
              'label' => 'Dimension',
              'show_target' => false,
          ],
      ];
    @endphp

    @foreach ($appraisalDetails as $title => $config)
      @php
        $items = $questions
            ->filter(fn($item) => $item['question']['category_id'] == $config['category_id'])
            ->sortByDesc(fn($item) => strtotime($item['question']['created_at']));

        $previousGroup = null;
      @endphp

      <div style="margin-bottom: 12px;">
        <h4 style="background-color: #eee; padding: 6px 10px; border-radius: 4px; margin: 0">{{ $title }}
        </h4>
        <table class="table">
          <thead>
            <tr>
              <th rowspan="2">{{ $config['label'] }}</th>
              @if ($config['show_target'])
                <th rowspan="2">Target</th>
              @endif
              <th rowspan="2">Formula</th>
              <th rowspan="2">Weight</th>
              @if ($isTwoEvaluators)
                <th colspan="2">Evaluator 1</th>
                <th colspan="2">Evaluator 2</th>
              @else
                <th colspan="2">Evaluator</th>
              @endif
            </tr>
            <tr>
              @if ($isTwoEvaluators)
                <th>Point</th>
                <th>Total</th>
                <th>Point</th>
                <th>Total</th>
              @else
                <th>Point</th>
                <th>Total</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @forelse ($items as $item)
              @php
                $groupKpi = $item['question']['group_kpi'] ?? '-';
                $showGroup = $groupKpi !== $previousGroup;
                $previousGroup = $groupKpi;

                $weight = (float) ($item['question']['weight'] ?? 0);
                $eval1 = $item['evaluator1_point'] !== null && $item['evaluator1_point'] !== '' ? (float)$item['evaluator1_point'] : null;
                $eval2 = $item['evaluator2_point'] !== null && $item['evaluator2_point'] !== '' ? (float)$item['evaluator2_point'] : null;
                $singlePoint = $eval1 ?? ($eval2 ?? 0);
                $singleTotal = number_format(($weight * $singlePoint) / 100, 2);
                $total1 = $eval1 !== null ? number_format(($weight * $eval1) / 100, 2) : '-';
                $total2 = $eval2 !== null ? number_format(($weight * $eval2) / 100, 2) : '-';
              @endphp
              <tr>
                <td style="padding: 8px;">{{ $showGroup ? $groupKpi : '' }}</td>
                @if ($config['show_target'])
                  <td
                    style="{{ $item['question']['question'] ? 'text-align: left' : 'text-align: center' }} padding: 8px;">
                    {{ $item['question']['question'] ?? '-' }}</td>
                @endif
                <td style="text-align: left; padding: 8px;">{!! nl2br(e($item['question']['formula_description'] ?? '-')) !!}</td>
                <td style="padding: 8px;">{{ $weight }}</td>
                @if ($isTwoEvaluators)
                  <td style="padding: 8px;">{{ $eval1 ?? '-' }}</td>
                  <td style="padding: 8px;">{{ $total1 }}</td>
                  <td style="padding: 8px;">{{ $eval2 ?? '-' }}</td>
                  <td style="padding: 8px;">{{ $total2 }}</td>
                @else
                  <td style="padding: 8px;">{{ $singlePoint }}</td>
                  <td style="padding: 8px;">{{ $singleTotal }}</td>
                @endif
              </tr>
            @empty
              <tr>
                <td colspan="{{ ($config['show_target'] ? 4 : 3) + ($isTwoEvaluators ? 4 : 2) }}">No data available.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    @endforeach

  </div>

</body>

</html>
