@extends('layouts.app')
@section('title', 'Complete Your Profile')
@section('content')
<h3 class="mb-1">Complete Your Profile</h3>
<p class="text-muted mb-4">Welcome, {{ explode(' ', $user->name)[0] }}! Fill in the details for your designation — everything is saved in one step. You won't be able to use the rest of the system until this is submitted and approved.</p>

<div class="card p-4" style="max-width:860px;">
    <form method="POST" action="{{ route('account-completion.store') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf

        <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing:0.05em;">Designation</h6>
        <div class="mb-4">
            <select name="designation" id="designation" class="form-select" required onchange="syncRequiredFields()">
                <option value="">Select your designation</option>
                <option value="dean" {{ old('designation') === 'dean' ? 'selected' : '' }}>Dean</option>
                <option value="coordinator" {{ old('designation') === 'coordinator' ? 'selected' : '' }}>OJT Coordinator</option>
                <option value="company" {{ old('designation') === 'company' ? 'selected' : '' }}>Office / Company</option>
            </select>
            <small class="text-muted" id="designationHint">Only the fields required by your designation need to be filled in.</small>
        </div>

        {{-- OJT Coordinator --}}
        <div id="coordinatorFields">
            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing:0.05em;">Coordinator Information</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id') }}" data-req="coordinator">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Prefix Title <span class="text-muted">(optional)</span></label>
                    <input type="text" name="prefix_title" class="form-control" placeholder="e.g. Dr., Engr." value="{{ old('prefix_title') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Suffix Title <span class="text-muted">(optional)</span></label>
                    <input type="text" name="suffix_title" class="form-control" placeholder="e.g. Jr., PhD" value="{{ old('suffix_title') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department / College</label>
                    <select name="department" class="form-select" data-req="coordinator">
                        <option value="">Select department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Institutional Email <span class="text-muted">(optional)</span></label>
                    <input type="email" name="institutional_email" class="form-control" value="{{ old('institutional_email') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select gender</option>
                        <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Prefer not to say" {{ old('gender') === 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Civil Status</label>
                    <select name="civil_status" class="form-select">
                        <option value="">Select status</option>
                        @foreach(['Single', 'Married', 'Widowed', 'Separated'] as $cs)
                            <option value="{{ $cs }}" {{ old('civil_status') === $cs ? 'selected' : '' }}>{{ $cs }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="coordinator_mobile_number" class="form-control" value="{{ old('coordinator_mobile_number') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Date Hired</label>
                    <input type="date" name="date_hired" class="form-control" value="{{ old('date_hired') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Photo <span class="text-muted">(optional)</span></label>
                    <input type="file" name="coordinator_photo" accept="image/*" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Resume / CV <span class="text-muted">(optional)</span></label>
                    <input type="file" name="coordinator_resume" accept=".pdf,.doc,.docx" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Qualification <span class="text-muted">(optional)</span></label>
                    <textarea name="qualification" rows="2" class="form-control" placeholder="e.g. MA in Education, Licensed Professional Teacher">{{ old('qualification') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Specialization <span class="text-muted">(optional)</span></label>
                    <input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}">
                </div>
            </div>
        </div>

        <hr class="my-4">

        {{-- Office / Company --}}
        <div id="companyFields">
            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing:0.05em;">Office / Company Details</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Affiliation Type</label>
                    <select name="affiliation_type" id="affiliationType" class="form-select" data-req="company" onchange="toggleAffiliationFields()">
                        <option value="">Select affiliation type</option>
                        <option value="inside_campus" {{ old('affiliation_type') === 'inside_campus' ? 'selected' : '' }}>Inside Campus</option>
                        <option value="outside_campus" {{ old('affiliation_type') === 'outside_campus' ? 'selected' : '' }}>Outside Campus</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Job Role / Position</label>
                    <select name="job_role" id="jobRole" class="form-select" data-req="company" onchange="toggleJobRoleOther()">
                        <option value="">Select job role</option>
                        <option value="Manager" {{ old('job_role') === 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Supervisor" {{ old('job_role') === 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="Others" {{ old('job_role') === 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
                </div>
            </div>
            <div id="jobRoleOtherField" class="mb-3 d-none">
                <label class="form-label">Please specify</label>
                <input type="text" name="job_role_other" id="jobRoleOther" class="form-control" value="{{ old('job_role_other') }}">
            </div>
            <div id="insideCampusField" class="mb-3 d-none">
                <label class="form-label">Office Name</label>
                <div class="input-group">
                    <span class="input-group-text">NORSU-BSC</span>
                    <input type="text" name="office_name" id="officeName" class="form-control" list="officeSuggestions" placeholder="e.g. MIS OFFICE" value="{{ old('office_name') }}">
                </div>
                <datalist id="officeSuggestions">
                    @foreach($officeSuggestions as $suggestion)
                        <option value="{{ $suggestion }}"></option>
                    @endforeach
                </datalist>
                <small class="text-muted">Start typing for suggestions, or enter your own office name.</small>
            </div>
            <div id="outsideCampusField" class="mb-3 d-none">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" id="companyName" class="form-control" placeholder="e.g. Dumaguete IT Solutions Inc." value="{{ old('company_name') }}">
            </div>

            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number') }}" data-req="company">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Office Landline <span class="text-muted">(optional)</span></label>
                    <input type="text" name="office_landline" class="form-control" value="{{ old('office_landline') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ID / Badge Number <span class="text-muted">(optional)</span></label>
                    <input type="text" name="id_badge_number" class="form-control" value="{{ old('id_badge_number') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alternate Email <span class="text-muted">(optional)</span></label>
                    <input type="email" name="alternate_email" class="form-control" value="{{ old('alternate_email') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Photo <span class="text-muted">(optional)</span></label>
                <input type="file" name="company_photo" accept="image/*" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-2">Submit for Approval</button>
    </form>
</div>

<p class="mt-3">
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link p-0 text-decoration-none">Not ready? Log out and finish this later</button>
    </form>
</p>

<script>
// All sections stay visible; the designation only controls which
// fields are REQUIRED. Fields are tagged data-req="coordinator|company".
function syncRequiredFields() {
    const designation = document.getElementById('designation').value;
    document.querySelectorAll('[data-req]').forEach(el => {
        el.required = el.dataset.req === designation;
    });

    const hints = {
        dean: 'Dean accounts are approved directly by the System Admin — no further details needed below.',
        coordinator: 'Fill in the Coordinator Information section. OJT Coordinator accounts are approved by the Dean.',
        company: 'Fill in the Office / Company Details section. Office/Company accounts are approved by the Dean.',
    };
    document.getElementById('designationHint').textContent = hints[designation]
        || 'Only the fields required by your designation need to be filled in.';
}

function toggleAffiliationFields() {
    const type = document.getElementById('affiliationType').value;
    const insideField = document.getElementById('insideCampusField');
    const outsideField = document.getElementById('outsideCampusField');

    insideField.classList.add('d-none');
    outsideField.classList.add('d-none');
    document.getElementById('officeName').required = false;
    document.getElementById('companyName').required = false;

    if (type === 'inside_campus') {
        insideField.classList.remove('d-none');
        document.getElementById('officeName').required = true;
    } else if (type === 'outside_campus') {
        outsideField.classList.remove('d-none');
        document.getElementById('companyName').required = true;
    }
}

function toggleJobRoleOther() {
    const jobRole = document.getElementById('jobRole').value;
    const otherField = document.getElementById('jobRoleOtherField');
    const otherInput = document.getElementById('jobRoleOther');

    if (jobRole === 'Others') {
        otherField.classList.remove('d-none');
        otherInput.required = true;
    } else {
        otherField.classList.add('d-none');
        otherInput.required = false;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    syncRequiredFields();
    toggleAffiliationFields();
    toggleJobRoleOther();
});
</script>
@endsection
