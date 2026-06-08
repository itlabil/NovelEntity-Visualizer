@extends('layouts.account')

@section('title', 'Edit Novel')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Edit Novel</h2>
            <p class="text-gray-600 mt-1 italic">Update novel data</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5">
        <div class="px-6 py-6 border-b border-gray-200">

            <form method="POST" action="{{ route('account.novels.update', $novel->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ old('title', $novel->title) }}" placeholder="Enter novel title" class="mt-1 block w-full border border-gray-300 @error('title') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                        @error('title')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border border-gray-300 @error('status') border-red-500 @enderror rounded-lg px-3 py-2 focus:ring-primary focus:border-primary">
                            <option value="approved" {{ old('status', $novel->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ old('status', $novel->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ old('status', $novel->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-start">
                    <a href="{{ route('account.novels.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                    <button type="submit" class="ml-2 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection