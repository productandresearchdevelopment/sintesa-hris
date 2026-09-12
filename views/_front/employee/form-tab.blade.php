<div class="tab-pane fade show active pt-4" id="general" role="tabpanel" aria-labelledby="general-tab">
  <form id="general-form">
    @csrf

    <div class="row mb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="nik" class="form-label text-muted">NIK</label>
        <input type="text" class="form-control" id="nik" name="nik">
      </div>

      <div class="col-md-6">
        <label for="fullname" class="form-label text-muted">Full Name</label>
        <input type="text" class="form-control" id="fullname" name="fullname">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="join_date" class="form-label text-muted">Join Date</label>
        <input type="date" class="form-control" id="join_date" name="join_date">
      </div>

      <div class="col-md-6">
        <label for="nickname" class="form-label text-muted">Nickname</label>
        <input type="text" class="form-control" id="nickname" name="nickname">
      </div>
    </div>

    <div class="mb-3">
      <label for="company_id" class="form-label text-muted">Company</label>
      <select class="form-select" id="company_id" name="company_id">
        <option value="">Select Company</option>
        @foreach ($companies as $company)
          <option value="{{ $company->id }}">{{ $company->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="org_id" class="form-label text-muted">Organization</label>
      <span id="org_id" class="form-control overflow-x-auto" name="org_id"></span>
    </div>

    <div class="row my-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="placement_id" class="form-label text-muted">Placement</label>
        <select class="form-select" id="placement_id" name="placement_id">
          <option value="">Select Placement</option>
          @foreach ($placements as $placement)
            <option value="{{ $placement->id }}">{{ $placement->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label for="division_id" class="form-label text-muted">Division</label>
        <select class="form-select" id="division_id" name="division_id">
          <option value="">Select Division</option>
          @foreach ($divisions as $division)
            <option value="{{ $division->id }}">{{ $division->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4 mb-3 mb-md-0">
        <label for="phone" class="form-label text-muted">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone">
      </div>

      <div class="col-md-4 mb-3 mb-md-0">
        <label for="email" class="form-label text-muted">Email</label>
        <input type="email" class="form-control" id="email" name="email">
      </div>

      <div class="col-md-4">
        <label for="leave_saldo" class="form-label text-muted">Leave Saldo</label>
        <input type="number" class="form-control" id="leave_saldo" name="leave_saldo">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="birth_place" class="form-label text-muted">Birth Place</label>
        <input type="text" class="form-control" id="birth_place" name="birth_place">
      </div>

      <div class="col-md-6">
        <label for="birth_date" class="form-label text-muted">Birth Date</label>
        <input type="date" class="form-control" id="birth_date" name="birth_date">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4 mb-3 mb-md-0">
        <label for="gender_id" class="form-label text-muted">Gender</label>
        <select class="form-select" id="gender_id" name="gender_id">
          <option value="">Select Gender</option>
          @foreach ($genders as $gender)
            <option value="{{ $gender->id }}">{{ $gender->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4 mb-3 mb-md-0">
        <label for="marital_id" class="form-label text-muted">Marital Status</label>
        <select class="form-select" id="marital_id" name="marital_id">
          <option value="">Select Marital Status</option>
          @foreach ($maritals as $marital)
            <option value="{{ $marital->id }}">{{ $marital->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label for="religion_id" class="form-label text-muted">Religion</label>
        <select class="form-select" id="religion_id" name="religion_id">
          <option value="">Select Religion</option>
          @foreach ($religions as $religion)
            <option value="{{ $religion->id }}">{{ $religion->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label for="address" class="form-label text-muted">Current Address</label>
      <textarea class="form-control" id="address" rows="3" name="address"></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="address_city_id" class="form-label text-muted">Current City</label>
        <select name="address_city_id" id="address_city_id" class="form-select">
          <option value="">Select City</option>
          @foreach ($cities as $city)
            <option value="{{ $city->id }}">{{ $city->city }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label for="address_province_id" class="form-label text-muted">Current Province</label>
        <select name="address_province_id" id="address_province_id" class="form-select">
          <option value="">Select Province</option>
          @foreach ($cities as $city)
            <option value="{{ $city->id }}">{{ $city->province }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label for="address_permanent" class="form-label text-muted">Permanent Address</label>
      <textarea name="address_permanent" class="form-control" id="address_permanent" rows="3"></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="address_permanent_city_id" class="form-label text-muted">Permanent City</label>
        <select name="address_permanent_city_id" id="address_permanent_city_id" class="form-select">
          <option value="">Select City</option>
          @foreach ($cities as $city)
            <option value="{{ $city->id }}">{{ $city->city }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label for="address_permanent_province_id" class="form-label text-muted">Permanent Province</label>
        <select name="address_permanent_province_id" id="address_permanent_province_id" class="form-select">
          <option value="">Select Province</option>
          @foreach ($cities as $city)
            <option value="{{ $city->id }}">{{ $city->province }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="bank_id" class="form-label text-muted">Bank</label>
        <select name="bank_id" id="bank_id" class="form-select">
          <option value="">Select Bank</option>
          @foreach ($banks as $bank)
            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label for="bank_account" class="form-label text-muted">Bank Account</label>
        <input type="text" class="form-control" id="bank_account" name="bank_account">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="emergency_relation_id" class="form-label text-muted">Emergency Relation</label>
        <select name="emergency_relation_id" id="emergency_relation_id" class="form-select">
          <option value="">Select Relation</option>
          @foreach ($emergency_relations as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label for="emergency_contact_name" class="form-label text-muted">Emergency Contact Name</label>
        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <label for="emergency_contact_phone" class="form-label text-muted">Emergency Contact Phone</label>
        <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone">
      </div>
      <div class="col-md-6">
        <label for="emergency_contact_address" class="form-label text-muted">Emergency Contact Address</label>
        <input type="text" class="form-control" id="emergency_contact_address" name="emergency_contact_address">
      </div>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-primary" id="btn-request-change">
        {{-- @if ($user->role->name === 'HRGA')
            Edit Profile
          @else --}}
        Request Change
        {{-- @endif --}}
      </button>
    </div>
  </form>
</div>
