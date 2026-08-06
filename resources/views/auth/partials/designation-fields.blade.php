{{-- Non-Student designation + conditional Office/Company fields.
     Shared by the manual Register form and the Google onboarding form. --}}
<div class="mb-3">
    <label class="form-label">Designation</label>
    <select name="designation" id="designation" class="form-select" required onchange="toggleDesignationFields()">
        <option value="">Select your designation</option>
        <option value="dean" {{ old('designation') === 'dean' ? 'selected' : '' }}>Dean</option>
        <option value="coordinator" {{ old('designation') === 'coordinator' ? 'selected' : '' }}>OJT Coordinator</option>
        <option value="company" {{ old('designation') === 'company' ? 'selected' : '' }}>Office / Company</option>
    </select>
    <small class="text-muted" id="designationHint"></small>
</div>

<div id="companyDesignationFields" class="d-none">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Affiliation Type</label>
            <select name="affiliation_type" id="affiliationType" class="form-select" onchange="toggleAffiliationFields()">
                <option value="">Select affiliation type</option>
                <option value="inside_campus" {{ old('affiliation_type') === 'inside_campus' ? 'selected' : '' }}>Inside Campus</option>
                <option value="outside_campus" {{ old('affiliation_type') === 'outside_campus' ? 'selected' : '' }}>Outside Campus</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Job Role / Position</label>
            <select name="job_role" id="jobRole" class="form-select" onchange="toggleJobRoleOther()">
                <option value="">Select job role</option>
                <option value="Manager" {{ old('job_role') === 'Manager' ? 'selected' : '' }}>Manager</option>
                <option value="Supervisor" {{ old('job_role') === 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                <option value="Others" {{ old('job_role') === 'Others' ? 'selected' : '' }}>Others</option>
            </select>
        </div>
    </div>

    <div id="jobRoleOtherField" class="mb-3 d-none">
        <label class="form-label">Please specify</label>
        <input type="text" name="job_role_other" id="jobRoleOther" class="form-control" autocomplete="off" value="{{ old('job_role_other') }}">
    </div>

    <div id="insideCampusField" class="mb-3 d-none">
        <label class="form-label">Office Name</label>
        <div class="input-group">
            <span class="input-group-text">NORSU-BSC</span>
            <input type="text" name="office_name" id="officeName" class="form-control" autocomplete="off" list="officeSuggestions" placeholder="e.g. MIS OFFICE" value="{{ old('office_name') }}">
        </div>
        <datalist id="officeSuggestions">
            @foreach($officeSuggestions as $suggestion)
                <option value="{{ $suggestion }}">
            @endforeach
        </datalist>
        <small class="text-muted">Start typing for suggestions, or enter your own office name.</small>
    </div>

    <div id="outsideCampusField" class="mb-3 d-none">
        <label class="form-label">Company Name</label>
        <input type="text" name="company_name" id="companyName" class="form-control" autocomplete="off" placeholder="e.g. Dumaguete IT Solutions Inc." value="{{ old('company_name') }}">
    </div>
</div>

<script>
function toggleDesignationFields() {
    const designation = document.getElementById('designation').value;
    const companyFields = document.getElementById('companyDesignationFields');
    const hint = document.getElementById('designationHint');
    const companyInputs = companyFields.querySelectorAll('select, input');

    if (designation === 'company') {
        companyFields.classList.remove('d-none');
    } else {
        companyFields.classList.add('d-none');
        companyInputs.forEach(el => { el.required = false; });
        document.getElementById('affiliationType').value = '';
        document.getElementById('jobRole').value = '';
        toggleAffiliationFields();
        toggleJobRoleOther();
    }

    const hints = {
        dean: 'Dean accounts are approved directly by the System Admin.',
        coordinator: 'OJT Coordinator accounts are approved by the Dean.',
        company: 'Office/Company accounts are approved by the Dean.',
    };
    hint.textContent = hints[designation] || '';

    if (designation === 'company') {
        document.getElementById('affiliationType').required = true;
        document.getElementById('jobRole').required = true;
    }
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
    toggleDesignationFields();
    toggleAffiliationFields();
    toggleJobRoleOther();
});
</script>
