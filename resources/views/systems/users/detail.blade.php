@include('headers.head')

<style>
  body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
  }

  .container {
    max-width: 100%;
    padding: 15px;
    margin: 0 auto;
  }

  .text {
    color: #333333;
    line-height: 1.4;
  }

  .text.xxlarge {
    font-size: 28px;
    font-weight: 700;
  }

  .text.xlarge {
    font-size: 22px;
    font-weight: 600;
  }

  .text.large {
    font-size: 18px;
    font-weight: 500;
  }

  .text.normal {
    font-size: 16px;
  }

  .text.small {
    font-size: 14px;
  }

  .text.xsmall {
    font-size: 12px;
  }

  .text.xxsmall {
    font-size: 11px;
  }

  .text.secondary {
    color: #6c757d;
  }

  .text.primary {
    color: #007bff;
  }

  .text.bold {
    font-weight: 700;
  }

  .text.semibold {
    font-weight: 600;
  }

  .card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    overflow: hidden;
  }

  .card:last-child {
    margin-bottom: 0;
  }


  .card-body {
    padding: 20px;
  }

  .profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 30px 20px;
    position: relative;
  }

  .profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid white;
    margin: 0 auto 15px;
    background-size: cover;
    background-position: center;
    background-color: #e9ecef;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  }

  .profile-avatar.no-image {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRTlFQ0VGIi8+CjxwYXRoIGQ9Ik01MCA1MEMzNi4xOTI5IDUwIDI1IDM4LjgwNzEgMjUgMjVDMjUgMTEuMTkyOSAzNi4xOTI5IDAgNTAgMEM2My44MDcxIDAgNzUgMTEuMTkyOSA3NSAyNUM3NSAzOC44MDcxIDYzLjgwNzEgNTAgNTAgNTBaTTIwIDc1QzIwIDYxLjE5MjkgMzEuMTkyOSA1MCA0NSA1MEg1NUM2OC44MDcxIDUwIDgwIDYxLjE5MjkgODAgNzVWODBIMjBWNzVaIiBmaWxsPSIjNkM3NTdEIi8+Cjwvc3ZnPgo=');
  }

  .badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .badge.active {
    background-color: #d4edda;
    color: #155724;
  }

  .badge.pending {
    background-color: #fff3cd;
    color: #856404;
  }

  .info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f3f4;
  }

  .info-row:last-child {
    border-bottom: none;
  }

  .info-label {
    font-weight: 600;
    color: #495057;
    flex: 0 0 35%;
  }

  .info-value {
    flex: 1;
    text-align: right;
    color: #212529;
  }

  .section-title {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f3f4;
  }

  .section-icon {
    width: 24px;
    height: 24px;
    margin-right: 10px;
    background-color: #007bff;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #dee2e6, transparent);
    margin: 15px 0;
  }

  .empty-state {
    text-align: center;
    padding: 30px;
    color: #6c757d;
  }

  .empty-state svg {
    width: 60px;
    height: 60px;
    margin-bottom: 15px;
    opacity: 0.5;
  }

  @media (max-width: 480px) {
    .container {
      padding: 10px;
    }

    .card-body {
      padding: 15px;
    }

    .profile-header {
      padding: 25px 15px;
    }

    .profile-avatar {
      width: 80px;
      height: 80px;
    }

    .info-label {
      flex: 0 0 40%;
      font-size: 14px;
    }

    .info-value {
      font-size: 14px;
    }
  }
</style>

<div class="container">
  <!-- Profile Header -->
  <div class="card">
    <div class="profile-header">
      <div class="profile-avatar {{ !$data->photo_id ? 'no-image' : '' }}"
        @if ($data->photo_id) style="background-image: url('{{ route('file', $data->photo_id) }}')" @endif>
      </div>
      <div class="text xlarge bold text-white" style="margin-bottom: 8px;">{{ $data->name }}</div>
      <div class="text normal text-white" style="opacity: 0.9;">{{ $data->email }}</div>
      <div style="margin-top: 15px;">
        @if ($data->email_validation_at)
          <span class="badge active">Email Verified</span>
        @else
          <span class="badge pending">Email Pending</span>
        @endif
      </div>
    </div>
  </div>

  <!-- Basic Information -->
  <div class="card">
    <div class="card-body">
      <div class="section-title">
        <div class="section-icon">
          <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
            <path
              d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 4V6H9V4L3 7V9H21ZM3 19V21H21V19L15 22H9L3 19Z" />
          </svg>
        </div>
        <div class="text large semibold">Basic Information</div>
      </div>

      <div class="info-row">
        <div class="info-label">User ID</div>
        <div class="info-value text small">{{ substr($data->id, 0, 8) }}...</div>
      </div>

      <div class="info-row">
        <div class="info-label">Username</div>
        <div class="info-value">{{ $data->username }}</div>
      </div>

      <div class="info-row">
        <div class="info-label">Full Name</div>
        <div class="info-value">{{ $data->name }}</div>
      </div>

      <div class="info-row">
        <div class="info-label">Email</div>
        <div class="info-value">{{ $data->email }}</div>
      </div>

      @if ($data->phone)
        <div class="info-row">
          <div class="info-label">Phone</div>
          <div class="info-value">{{ $data->phone }}</div>
        </div>
      @endif

      @if ($data->address)
        <div class="info-row">
          <div class="info-label">Address</div>
          <div class="info-value">{{ $data->address }}</div>
        </div>
      @endif
    </div>
  </div>

  <!-- System Information -->
  <div class="card">
    <div class="card-body">
      <div class="section-title">
        <div class="section-icon">
          <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
            <path
              d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.22,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.68 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z" />
          </svg>
        </div>
        <div class="text large semibold">System Information</div>
      </div>

      <div class="info-row">
        <div class="info-label">Role ID</div>
        <div class="info-value">{{ $data->role_id }}</div>
      </div>

      <div class="info-row">
        <div class="info-label">Organization ID</div>
        <div class="info-value">{{ $data->organization_id }}</div>
      </div>

      <div class="info-row">
        <div class="info-label">Employee ID</div>
        <div class="info-value text small">{{ substr($data->employ_id, 0, 8) }}...</div>
      </div>

      <div class="info-row">
        <div class="info-label">Notifications</div>
        <div class="info-value">
          @if ($data->receive_notif)
            <span class="badge active">Enabled</span>
          @else
            <span class="badge pending">Disabled</span>
          @endif
        </div>
      </div>

      @if ($data->last_active)
        <div class="info-row">
          <div class="info-label">Last Active</div>
          <div class="info-value text small">{{ date('d/m/Y H:i', strtotime($data->last_active)) }}</div>
        </div>
      @endif

      @if ($data->last_ip)
        <div class="info-row">
          <div class="info-label">Last IP</div>
          <div class="info-value text small">{{ $data->last_ip }}</div>
        </div>
      @endif
    </div>
  </div>

  <!-- Account Status -->
  <div class="card">
    <div class="card-body">
      <div class="section-title">
        <div class="section-icon">
          <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
            <path
              d="M9,12L11,14L15,10M20,6A2,2 0 0,1 22,8V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V8A2,2 0 0,1 4,6H6V4A2,2 0 0,1 8,2H16A2,2 0 0,1 18,4V6H20M16,6V4H8V6H16Z" />
          </svg>
        </div>
        <div class="text large semibold">Account Status</div>
      </div>

      <div class="info-row">
        <div class="info-label">Email Status</div>
        <div class="info-value">
          @if ($data->email_validation_at)
            <span class="text primary semibold">Verified</span>
            <div class="text xsmall secondary">{{ date('d/m/Y H:i', strtotime($data->email_validation_at)) }}</div>
          @else
            <span class="text secondary">Not Verified</span>
            @if ($data->email_validation_sent_at)
              <div class="text xsmall secondary">Code sent:
                {{ date('d/m/Y H:i', strtotime($data->email_validation_sent_at)) }}</div>
            @endif
          @endif
        </div>
      </div>

      <div class="info-row">
        <div class="info-label">Account Created</div>
        <div class="info-value text small">{{ date('d/m/Y H:i', strtotime($data->created_at)) }}</div>
      </div>

      <div class="info-row">
        <div class="info-label">Last Updated</div>
        <div class="info-value text small">{{ date('d/m/Y H:i', strtotime($data->updated_at)) }}</div>
      </div>

      @if ($data->description)
        <div class="divider"></div>
        <div class="info-row">
          <div class="info-label">Description</div>
          <div class="info-value">{{ $data->description }}</div>
        </div>
      @endif
    </div>
  </div>
</div>
