document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkDeleteForm = document.getElementById('bulk-delete-form');
    const bulkDeleteIds = document.getElementById('bulk-delete-ids');
    const confirmBulkDeleteBtn = document.getElementById('confirm-bulk-delete-btn');
    const selectedProductCount = document.getElementById('selectedProductCount');

    // Handle select all checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkDeleteButton();
        });
    }

    // Handle individual checkboxes
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkDeleteButton();
            // Update select all checkbox state
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = Array.from(rowCheckboxes).every(cb => cb.checked);
            }
        });
    });

    // Update bulk delete button visibility
    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.style.display = checkedBoxes.length > 0 ? 'flex' : 'none';
        }
    }

    // Handle bulk delete button click
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (selectedIds.length > 0) {
                if (selectedProductCount) {
                    selectedProductCount.textContent = selectedIds.length;
                }
                // Show confirmation modal
                const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
                bulkDeleteModal.show();
            }
        });
    }

    // Handle confirm bulk delete
    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (selectedIds.length > 0) {
                bulkDeleteIds.value = selectedIds.join(',');
                bulkDeleteForm.submit();
            }
        });
    }
}); 