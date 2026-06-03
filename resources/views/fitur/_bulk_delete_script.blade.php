function initBulkDelete(config) {
    const {
        table,            // Variabel datatable (Wajib)
        route,            // URL post bulk delete (Wajib)
        checkboxClass = '.category-checkbox', 
        selectAllId = '#selectAll',
        bulkBtnId = '#btnBulkDelete'
    } = config;

    // 1. Logika untuk Select All
    $(selectAllId).on('click', function() {
        $(checkboxClass).prop('checked', this.checked);
        toggleBulkDeleteBtn();
    });

    // 2. Logika ketika Checkbox satuan diklik (Delegasi ke tbody)
    $('tbody').on('change', checkboxClass, function() {
        if ($(checkboxClass + ':checked').length == $(checkboxClass).length && $(checkboxClass).length > 0) {
            $(selectAllId).prop('checked', true);
        } else {
            $(selectAllId).prop('checked', false);
        }
        toggleBulkDeleteBtn();
    });

    // 3. Reset Checkbox setiap kali pindah halaman / search / reload
    table.on('draw', function() {
        $(selectAllId).prop('checked', false);
        toggleBulkDeleteBtn();
    });

    // 4. Fungsi memunculkan/menyembunyikan tombol
    function toggleBulkDeleteBtn() {
        var checkedCount = $(checkboxClass + ':checked').length;
        $('#selectedCount').text(checkedCount); 

        if (checkedCount > 0) {
            $(bulkBtnId).fadeIn();
        } else {
            $(bulkBtnId).fadeOut();
        }
    }

    // 5. Eksekusi Delete
    $(bulkBtnId).on('click', function(e) {
        e.preventDefault();
        var selectedIds = [];
        
        $(checkboxClass + ':checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will delete " + selectedIds.length + " items!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(route, {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ids: selectedIds
                    }, function(response) {
                        if(response.status === 'success') {
                            Swal.fire('Deleted!', response.message, 'success');
                            table.ajax.reload(null, false); // Reload tanpa reset pagination
                        }
                    }).fail(function() {
                        Swal.fire('Error!', 'System error occurred.', 'error');
                    });
                }
            });
        }
    });
}