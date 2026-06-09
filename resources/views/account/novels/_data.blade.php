<x-datatable-styles />

<table class="min-w-full divide-y divide-gray-200 yajra-datatable">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center space-x-1">
                    <span>Novel</span>
                </div>
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center space-x-1">
                    <span>Type</span>
                </div>
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center space-x-1">
                    <span>Status</span>
                </div>
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center space-x-1">
                    <span>Author</span>
                </div>
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-px whitespace-nowrap">Action</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200"></tbody>
</table>

@push('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('account.novels.index') }}",
            order: [[1, 'desc']], // Tetap urutkan berdasarkan tahun
            dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
            columns: [
                // Ganti DT_RowIndex dengan checkbox
                { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'px-6 py-4 whitespace-nowrap' },
                { data: 'title', name: 'title', className: 'px-6 py-4'},
                { data: 'type', name: 'type', className: 'px-6 py-4'},
                { data: 'status', name: 'status', className: 'px-6 py-4'},
                { data: 'author', name: 'author', className: 'px-6 py-4'},
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'px-6 py-4 w-px whitespace-nowrap'},
            ]
        });

        @include('fitur._bulk_delete_script')
        
        initBulkDelete({
            table: table, 
            route: "{{ route('account.novels.bulkDelete') }}",
            checkboxClass: '.novel-checkbox'
        });

    });
</script>
@endpush