function initBulkUpdatePay(config) {
    const {
        table,            
        route,            
        checkboxClass = '.student-checkbox', 
        bulkBtnId = '#btnBulkUpdatePay'
    } = config;

    // Fungsi memunculkan/menyembunyikan tombol (Sinkron dengan Delete)
    function toggleBulkPayBtn() {
        var checkedCount = $(checkboxClass + ':checked').length;
        if (checkedCount > 0) {
            $(bulkBtnId).fadeIn();
        } else {
            $(bulkBtnId).fadeOut();
        }
    }

    // Pantau perubahan checkbox (Gunakan delegasi body agar tidak bentrok)
    $(document).on('change', checkboxClass + ', #selectAll', function() {
        toggleBulkPayBtn();
    });

    // Reset saat table draw
    table.on('draw', function() {
        toggleBulkPayBtn();
    });

    // Eksekusi Update Payment
    $(bulkBtnId).on('click', function(e) {
        e.preventDefault();
        var selectedIds = [];
        
        $(checkboxClass + ':checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            Swal.fire({
                title: 'Update Payment Status?',
                text: "Toggle payment status for " + selectedIds.length + " students?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(route, {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ids: selectedIds
                    }, function(response) {
                        if(response.status === 'success') {
                            Swal.fire('Updated!', response.message, 'success');
                            table.ajax.reload(null, false); 
                        }
                    }).fail(function() {
                        Swal.fire('Error!', 'System error occurred.', 'error');
                    });
                }
            });
        }
    });
}