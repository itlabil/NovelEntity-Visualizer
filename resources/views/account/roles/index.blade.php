@extends('layouts.account')

@section('title', 'Role')

@section('content')
@include('fitur._datatable')

<!-- Header -->
<div class="mb-8" x-data>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Role Management</h2>
            <p class="text-gray-600 mt-1 italic">Manage your roles and their information</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
        <!-- Table Header -->
        <div class="px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    @can('roles.delete')
                        <button id="btnBulkDelete" style="display: none;" class="inline-flex text-sm items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm mr-2">
                            <x-icons.trash class="mr-1" width="18" height="18" />
                            Delete Selected (<span id="selectedCount">0</span>)
                        </button>
                    @endcan
                </div>

                @can('roles.create')
                    <a href="{{ route('account.roles.create') }}"  class="inline-flex text-sm items-center px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <x-icons.plus class="mr-1" width="20" height="20" />
                        Add New
                    </a>
                @endcan
            </div>
        </div>
        <!-- Table Content -->
        <div class="overflow-x-auto w-full p-6">
            @include('account.roles._data')
        </div>

    </div>

    <x-modal-delete />
</div>

@endsection

