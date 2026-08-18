document.addEventListener('DOMContentLoaded', function () {
    const ownershipSelect = document.getElementById('ownershipSelect');
    const docDeedOfSale = document.getElementById('docDeedOfSale');

    function updateConditionalDocs() {
        docDeedOfSale.classList.toggle('active', ownershipSelect.value === 'Not Registered to Applicant');
    }

    ownershipSelect.addEventListener('change', updateConditionalDocs);
    updateConditionalDocs();
});
