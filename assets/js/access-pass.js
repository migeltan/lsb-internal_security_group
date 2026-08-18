document.addEventListener('DOMContentLoaded', function () {

    // ---------- Family Background repeater ----------
    const familyContainer = document.getElementById('familyEntries');
    const addFamilyBtn = document.getElementById('addFamilyMember');
    let familyIndex = 0;

    function addFamilyEntry() {
        const i = familyIndex++;
        const wrapper = document.createElement('div');
        wrapper.className = 'repeater-entry';
        wrapper.innerHTML = `
            <button type="button" class="btn-close remove-entry" aria-label="Remove"></button>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Relationship</label>
                    <select name="family[${i}][relationship]" class="form-select">
                        <option value="">Select&hellip;</option>
                        <option>Father</option>
                        <option>Mother</option>
                        <option>Spouse</option>
                        <option>Child/Dependent</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="family[${i}][name]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Occupation</label>
                    <input type="text" name="family[${i}][occupation]" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Other Information</label>
                    <input type="text" name="family[${i}][other_information]" class="form-control">
                </div>
            </div>
        `;
        wrapper.querySelector('.remove-entry').addEventListener('click', () => wrapper.remove());
        familyContainer.appendChild(wrapper);
    }

    addFamilyBtn.addEventListener('click', addFamilyEntry);
    addFamilyEntry(); // start with one blank entry

    // ---------- Educational Background repeater ----------
    const eduContainer = document.getElementById('educationEntries');
    const addEduBtn = document.getElementById('addEducation');
    let eduIndex = 0;

    function addEducationEntry() {
        const i = eduIndex++;
        const wrapper = document.createElement('div');
        wrapper.className = 'repeater-entry';
        wrapper.innerHTML = `
            <button type="button" class="btn-close remove-entry" aria-label="Remove"></button>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">School / Institution</label>
                    <input type="text" name="education[${i}][school]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Degree / Course</label>
                    <input type="text" name="education[${i}][degree]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Year Graduated</label>
                    <input type="text" name="education[${i}][year_graduated]" class="form-control" placeholder="YYYY">
                </div>
                <div class="col-12">
                    <label class="form-label">Other Information</label>
                    <input type="text" name="education[${i}][other_information]" class="form-control">
                </div>
            </div>
        `;
        wrapper.querySelector('.remove-entry').addEventListener('click', () => wrapper.remove());
        eduContainer.appendChild(wrapper);
    }

    addEduBtn.addEventListener('click', addEducationEntry);
    addEducationEntry(); // start with one blank entry

    // ---------- Conditional supporting documents ----------
    const applicantType = document.getElementById('applicantType');
    const docNbi = document.getElementById('docNbi');
    const docConsultancy = document.getElementById('docConsultancy');

    function updateConditionalDocs() {
        const val = applicantType.value;
        docNbi.classList.toggle('active', val === 'Non-Plantilla');
        docConsultancy.classList.toggle('active', val === 'Consultant');
    }

    applicantType.addEventListener('change', updateConditionalDocs);
    updateConditionalDocs();
});
