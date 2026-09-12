@include('headers.head')

<div id="appCapsule" class="bg-white w-100" style="min-height: 100vh;">

  <!-- Profile Header Card -->
  <div class="profile-header-card">
    <div class="profile-bg-gradient"></div>
    <div class="profile-content">
      <div class="profile-avatar">
        @if ($data->photo_id)
          <img src="{{ route('file', $data->photo_id) }}" alt="photo" class="avatar-img">
        @else
          <div class="avatar-placeholder">
            {{ strtoupper(substr($data->fullname, 0, 2)) }}
          </div>
        @endif
        <div class="status-indicator {{ $data->last_contract ? 'active' : 'inactive' }}"></div>
      </div>
      <h4 class="profile-name">{{ $data->fullname ?? '-' }}</h4>
      <p class="profile-nik">{{ $data->nik ?? '-' }}</p>
      <div class="profile-badges">
        <span class="badge-custom badge-primary">
          <i class="bi bi-briefcase"></i>
          {{ $data->placement->name ?? 'No Placement' }}
        </span>
        <span class="badge-custom badge-success">
          <i class="bi bi-calendar-check"></i>
          {{ $data->leave_saldo ?? 0 }} Days Leave
        </span>
      </div>
    </div>
  </div>

  <div class="content-wrapper">

    <!-- Personal Information -->
    <div class="section-card">
      <div class="section-header">
        <i class="bi bi-person"></i>
        <h5>Personal Information</h5>
      </div>
      <div class="section-content">
        <div class="info-row">
          <span class="info-key">Nickname</span>
          <span class="info-val">{{ $data->nickname ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Email</span>
          <span class="info-val">{{ $data->email ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Phone</span>
          <span class="info-val">{{ $data->phone ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Gender</span>
          <span class="info-val">{{ $data->gender->name ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Birth Place</span>
          <span class="info-val">{{ $data->birth_place ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Birth Date</span>
          <span class="info-val">{{ $data->birth_date ? date('d M Y', strtotime($data->birth_date)) : '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Marital Status</span>
          <span class="info-val">{{ $data->marital->name ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Religion</span>
          <span class="info-val">{{ $data->religion->name ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Join Date</span>
          <span class="info-val">{{ $data->join_date ? date('d F Y', strtotime($data->join_date)) : '-' }}</span>
        </div>
      </div>
    </div>

    <!-- Organization -->
    <div class="section-card">
      <div class="section-header">
        <i class="bi bi-building"></i>
        <h5>Organization</h5>
      </div>
      <div class="section-content">
        <div class="info-row">
          <span class="info-key">Organization</span>
          <span class="info-val">{{ $data->organization->name ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Company</span>
          <span class="info-val">{{ $data->company->name ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Division</span>
          <span class="info-val">{{ $data->division->name ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-key">Office</span>
          <span class="info-val">{{ $data->office->name ?? '-' }}</span>
        </div>
        @if ($data->shift_start_time && $data->shift_end_time)
          <div class="info-row">
            <span class="info-key">Shift Time</span>
            <span class="info-val">{{ $data->shift_start_time }} - {{ $data->shift_end_time }}</span>
          </div>
        @endif
      </div>
    </div>

    <!-- Address -->
    <div class="section-card">
      <div class="section-header">
        <i class="bi bi-geo-alt"></i>
        <h5>Address</h5>
      </div>
      <div class="section-content">
        <div class="address-block">
          <div class="address-label">
            <i class="bi bi-house"></i>
            Current Address
          </div>
          <p class="address-text">{{ $data->address ?? '-' }}</p>
          <p class="address-detail">{{ $data->address_city->city ?? '-' }},
            {{ $data->address_province->province ?? '-' }}
          </p>
        </div>
        <div class="address-divider"></div>
        <div class="address-block">
          <div class="address-label">
            <i class="bi bi-house-door"></i>
            Permanent Address
          </div>
          <p class="address-text">{{ $data->address_permanent ?? '-' }}</p>
          <p class="address-detail">{{ $data->address_permanent_city->city ?? '-' }},
            {{ $data->address_permanent_province->province ?? '-' }}</p>
        </div>
      </div>
    </div>

    <!-- Bank Information -->
    <div class="section-card">
      <div class="section-header">
        <i class="bi bi-credit-card"></i>
        <h5>Bank Information</h5>
      </div>
      <div class="section-content">
        <div class="bank-card">
          <div class="bank-logo">
            <i class="bi bi-wallet2"></i>
          </div>
          <div class="bank-details">
            <div class="bank-name">{{ $data->bank->name ?? '-' }}</div>
            <div class="bank-account">{{ $data->bank_account ?? '-' }}</div>
            <div class="bank-holder">{{ $data->bank_alias ?? '-' }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Emergency Contact -->
    @if ($data->emergency_contact_name)
      <div class="section-card">
        <div class="section-header">
          <i class="bi bi-exclamation-circle"></i>
          <h5>Emergency Contact</h5>
        </div>
        <div class="section-content">
          <div class="emergency-card">
            <div class="emergency-icon">
              <i class="bi bi-telephone"></i>
            </div>
            <div class="emergency-info">
              <div class="emergency-name">{{ $data->emergency_contact_name ?? '-' }}</div>
              <div class="emergency-relation">{{ $data->emergency_relation->name ?? '-' }}</div>
              <div class="emergency-phone">{{ $data->emergency_contact_phone ?? '-' }}</div>
              @if ($data->emergency_contact_address)
                <div class="emergency-address">{{ $data->emergency_contact_address }}</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- Contract -->
    @if ($data->last_contract)
      <div class="section-card">
        <div class="section-header">
          <i class="bi bi-file-text"></i>
          <h5>Current Contract</h5>
        </div>
        <div class="section-content">
          <div class="contract-timeline">
            <div class="timeline-item">
              <div class="timeline-marker start"></div>
              <div class="timeline-content">
                <span class="timeline-label">Start Date</span>
                <span
                  class="timeline-date">{{ $data->last_contract->start_date ? date('d M Y', strtotime($data->last_contract->start_date)) : '-' }}</span>
              </div>
            </div>
            <div class="timeline-item">
              <div class="timeline-marker end"></div>
              <div class="timeline-content">
                <span class="timeline-label">End Date</span>
                <span
                  class="timeline-date">{{ $data->last_contract->end_date ? date('d M Y', strtotime($data->last_contract->end_date)) : '-' }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- Career -->
    @if ($data->last_career)
      <div class="section-card">
        <div class="section-header">
          <i class="bi bi-trophy"></i>
          <h5>Current Career</h5>
        </div>
        <div class="section-content">
          <div class="career-card">
            <div class="career-icon">
              <i class="bi bi-star"></i>
            </div>
            <div class="career-details">
              <div class="career-position">{{ $data->last_career->career->name ?? '-' }}</div>
              <div class="career-level">{{ $data->last_career->placement->name ?? '-' }}</div>
              <div class="career-date">Since
                {{ $data->last_career->created_at ? date('M Y', strtotime($data->last_career->created_at)) : '-' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- Family -->
    @if ($data->families && count($data->families) > 0)
      <div class="section-card">
        <div class="section-header">
          <i class="bi bi-people"></i>
          <h5>Family Members</h5>
        </div>
        <div class="section-content">
          @foreach ($data->families as $family)
            <div class="family-item {{ !$loop->last ? 'mb-3' : '' }}">
              <div class="family-avatar">
                {{ strtoupper(substr($family->name, 0, 1)) }}
              </div>
              <div class="family-info">
                <div class="family-name">{{ $family->name ?? '-' }}</div>
                <div class="family-relation">{{ $family->relation->name ?? '-' }}</div>
                <div class="family-detail">
                  <span class="family-occupation">{{ $family->occupation->name ?? '-' }}</span> -
                  <span class="family-phone">{{ $family->phone ?? '-' }}</span>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Education -->
    @if ($data->educations && count($data->educations) > 0)
      <div class="section-card">
        <div class="section-header">
          <i class="bi bi-mortarboard"></i>
          <h5>Education</h5>
        </div>
        <div class="section-content">
          @foreach ($data->educations as $education)
            <div class="education-item {{ !$loop->last ? 'mb-3' : '' }}">
              <div class="education-level">{{ $education->education->name ?? '-' }}</div>
              <div class="education-institution">{{ $education->institution ?? '-' }}</div>
              <div class="education-detail">
                <span>{{ $education->major->name ?? '-' }}</span> -
                <span class="education-year">{{ $education->graduate ?? '-' }}</span>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Job Experience -->
    @if ($data->job_experiences && count($data->job_experiences) > 0)
      <div class="section-card">
        <div class="section-header">
          <i class="bi bi-briefcase"></i>
          <h5>Work Experience</h5>
        </div>
        <div class="section-content">
          @foreach ($data->job_experiences as $experience)
            <div class="experience-item {{ !$loop->last ? 'mb-3' : '' }}">
              <div class="experience-position">{{ $experience->job_title ?? '-' }}</div>
              <div class="experience-leaving">{{ $experience->reason_leaving ?? '-' }}</div>
              <div class="experience-period">
                <i class="bi bi-calendar3"></i>
                {{ $experience->start_date ? date('M Y', strtotime($experience->start_date)) : '-' }} -
                {{ $experience->end_date ? date('M Y', strtotime($experience->end_date)) : 'Present' }}
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Training -->
    @if ($data->trainings && count($data->trainings) > 0)
      <div class="section-card">
        <div class="section-header">
          <i class="bi bi-award"></i>
          <h5>Training & Certification</h5>
        </div>
        <div class="section-content">
          @foreach ($data->trainings as $training)
            <div class="training-item {{ !$loop->last ? 'mb-3' : '' }}">
              <div class="training-icon">
                <i class="bi bi-patch-check"></i>
              </div>
              <div class="training-info">
                <div class="training-title">{{ $training->title ?? '-' }}</div>
                <div class="training-location">{{ $training->location ?? '-' }}</div>
                <div class="training-date">
                  {{ $training->start_date ? date('d M Y', strtotime($training->start_date)) : '-' }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>

  <!-- Footer -->
  <div class="footer-info">
    <p>Last updated: {{ $data->updated_at ? date('d M Y, H:i', strtotime($data->updated_at)) : '-' }}</p>
  </div>

</div>

<style>
  /* Global Styles */
  #appCapsule {
    padding: 0;
    background: #fff;
  }

  i.bi {
    display: flex;
    align-items: center;
  }

  .content-wrapper {
    padding: 0 16px 20px;
    background: #f9fafb;
  }

  /* Profile Header */
  .profile-header-card {
    position: relative;
    padding: 30px 20px 30px;
    margin-bottom: 40px;
    overflow: hidden;
    background: #fff;
  }

  .profile-bg-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 300px;
    background: linear-gradient(135deg, #6a8ef7 0%, #435ebe 100%);
    z-index: 0;
  }

  .profile-content {
    position: relative;
    z-index: 1;
    text-align: center;
  }

  .profile-avatar {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 15px;
  }

  .avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }

  .avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #6a8ef7 0%, #435ebe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 32px;
    font-weight: bold;
    border: 4px solid #fff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }

  .status-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  }

  .status-indicator.active {
    background: #10b981;
  }

  .status-indicator.inactive {
    background: #ef4444;
  }

  .profile-name {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    margin: 0 0 5px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .profile-nik {
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
    margin: 0 0 15px;
    letter-spacing: 0.5px;
  }

  .profile-badges {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
  }

  .badge-custom {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
  }

  .badge-custom i {
    font-size: 14px;
  }

  .badge-primary {
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
  }

  .badge-success {
    background: rgba(16, 185, 129, 0.9);
    color: #fff;
  }

  /* Section Card */
  .section-card {
    background: #fff;
    border-radius: 16px;
    margin-bottom: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  }

  .section-header {
    background: linear-gradient(135deg, #6a8ef7 0%, #435ebe 100%);
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .section-header i {
    font-size: 20px;
    color: #fff;
  }

  .section-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
  }

  .section-content {
    padding: 16px 18px;
  }

  /* Info Rows */
  .info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
  }

  .info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .info-row:first-child {
    padding-top: 0;
  }

  .info-key {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }

  .info-val {
    font-size: 13px;
    color: #1f2937;
    font-weight: 600;
    text-align: right;
    max-width: 60%;
    word-break: break-word;
  }

  /* Address */
  .address-block {
    padding: 12px 0;
  }

  .address-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6a8ef7;
    font-weight: 600;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .address-label i {
    font-size: 16px;
  }

  .address-text {
    font-size: 14px;
    color: #1f2937;
    margin: 0 0 4px;
    line-height: 1.5;
  }

  .address-detail {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
  }

  .address-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
    margin: 12px 0;
  }

  /* Bank Card */
  .bank-card {
    background: linear-gradient(135deg, #6a8ef7 0%, #435ebe 100%);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    color: #fff;
  }

  .bank-logo {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    padding-right: 8px;
    flex-shrink: 0;
  }

  .bank-details {
    flex: 1;
  }

  .bank-name {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 6px;
  }

  .bank-account {
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 4px;
  }

  .bank-holder {
    font-size: 13px;
    opacity: 0.9;
  }

  /* Emergency Card */
  .emergency-card {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-radius: 12px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    color: #fff;
  }

  .emergency-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
    padding-right: 8px;
  }

  .emergency-info {
    flex: 1;
  }

  .emergency-name {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 4px;
  }

  .emergency-relation {
    font-size: 13px;
    opacity: 0.9;
    margin-bottom: 8px;
  }

  .emergency-phone {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 6px;
    letter-spacing: 0.5px;
  }

  .emergency-address {
    font-size: 12px;
    opacity: 0.85;
    line-height: 1.4;
  }

  /* Contract Timeline */
  .contract-timeline {
    display: flex;
    align-items: center;
    padding: 16px 0;
  }

  .timeline-item {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
  }

  .timeline-marker {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid #6a8ef7;
    flex-shrink: 0;
  }

  .timeline-marker.start {
    background: #10b981;
    border-color: #10b981;
  }

  .timeline-marker.end {
    background: #6a8ef7;
  }

  .timeline-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }

  .timeline-label {
    font-size: 11px;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
  }

  .timeline-date {
    font-size: 13px;
    color: #1f2937;
    font-weight: 600;
  }

  /* Career Card */
  .career-card {
    background: linear-gradient(135deg, #6a8ef7 0%, #435ebe 100%);
    border-radius: 12px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    color: #fff;
  }

  .career-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
    padding-right: 8px;
  }

  .career-details {
    flex: 1;
  }

  .career-position {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 4px;
  }

  .career-level {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 6px;
  }

  .career-date {
    font-size: 12px;
    opacity: 0.85;
  }

  /* Family Item */
  .family-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 10px;
  }

  .family-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6a8ef7 0%, #435ebe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    font-weight: 700;
    flex-shrink: 0;
  }

  .family-info {
    flex: 1;
  }

  .family-name {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 2px;
  }

  .family-relation {
    font-size: 12px;
    color: #6a8ef7;
    font-weight: 500;
    margin-bottom: 2px;
  }

  .family-detail {
    display: flex;
    align-items: center;
    font-size: 12px;
    color: #6b7280;
    gap: 4px;
  }

  /* Education Item */
  .education-item {
    padding: 14px;
    background: #f9fafb;
    border-radius: 10px;
    border-left: 4px solid #6a8ef7;
  }

  .education-level {
    font-size: 15px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 4px;
  }

  .education-institution {
    font-size: 13px;
    color: #6a8ef7;
    font-weight: 600;
    margin-bottom: 6px;
  }

  .education-detail {
    display: flex;
    align-items: center;
    font-size: 12px;
    color: #6b7280;
    gap: 4px;
  }

  /* Experience Item */
  .experience-item {
    padding: 14px;
    background: #f9fafb;
    border-radius: 10px;
    border-left: 4px solid #6a8ef7;
  }

  .experience-position {
    font-size: 15px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 4px;
  }

  .experience-leaving {
    font-size: 10px;
    color: #6a8ef7;
    font-weight: 600;
    margin-bottom: 6px;
  }

  .experience-period {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6b7280;
  }

  .experience-period i {
    font-size: 14px;
  }

  /* Training Item */
  .training-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 10px;
  }

  .training-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #6a8ef7 0%, #435ebe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    flex-shrink: 0;
    padding-right: 8px;
  }

  .training-info {
    flex: 1;
  }

  .training-title {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 3px;
  }

  .training-location {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 3px;
  }

  .training-date {
    font-size: 11px;
    color: #9ca3af;
  }

  /* Footer */
  .footer-info {
    text-align: center;
    padding: 0px 20px 20px 20px;
    background: #f9fafb;
    color: #6b7280;
    font-size: 12px;
  }

  .footer-info p {
    margin: 0;
  }

  /* Animations */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .section-card {
    animation: fadeInUp 0.4s ease-out;
  }

  .section-card:nth-child(1) {
    animation-delay: 0.05s;
  }

  .section-card:nth-child(2) {
    animation-delay: 0.1s;
  }

  .section-card:nth-child(3) {
    animation-delay: 0.15s;
  }

  .section-card:nth-child(4) {
    animation-delay: 0.2s;
  }

  .section-card:nth-child(5) {
    animation-delay: 0.25s;
  }

  /* Responsive adjustments */
  @media (max-width: 360px) {
    .quick-info-grid {
      gap: 10px;
    }

    .info-card {
      padding: 12px;
    }

    .info-icon {
      width: 38px;
      height: 38px;
      font-size: 18px;
    }

    .profile-name {
      font-size: 20px;
    }
  }
</style>
