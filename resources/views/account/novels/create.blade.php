@extends('layouts.account')

@section('title', 'Add Novel')

@section('content')

<!-- Header -->
<div x-data class="mb-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Add Novel</h2>
            <p class="text-gray-600 mt-1 italic">Add new novel data</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
        <!-- Table Header -->
        <div class="px-6 py-6 border-b border-gray-200">

            <form method="POST" action="{{ route('account.novels.store') }}">
                @csrf

                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter novel title" class="mt-1 block w-full border border-gray-300 @error('title') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                        @error('title')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-start">
                    <a href="{{ route('account.novels.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                    <button type="submit" class="ml-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90">Save</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection