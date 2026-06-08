@extends('layouts.account')

@section('title', 'Edit Entity')

@section('content')
<div x-data="{ entityType: '{{ old('type', 'character') }}' }" class="mb-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl sm:text-xl font-bold text-gray-900">Edit Entity: <span class="text-primary">{{ $entity->main_name }}</span></h2>
            <p class="text-gray-600 mt-1 text-xs italic">Memperbarui informasi objek kata kunci ini.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-5 border border-gray-100">
        <div class="px-6 py-6">

            <form method="POST" action="{{ route('account.entities.update', $entity->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-5">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Type</label>
                            <select name="type" x-model="entityType" class="block w-full border border-gray-300 @error('type') border-red-500 @enderror rounded-lg px-3 py-2.5 text-sm focus:ring-primary focus:border-primary font-medium bg-gray-50/50">
                                <option value="character" {{ old('type', $entity->type) == 'character' ? 'selected' : '' }}>👤 Character</option>
                                <option value="item" {{ old('type', $entity->type) == 'item' ? 'selected' : '' }}>⚔️ Item</option>
                                <option value="place" {{ old('type', $entity->type) == 'place' ? 'selected' : '' }}>🏰 Place</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="entityType === 'character'" x-transition>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Gender</label>
                            <select name="gender" class="block w-full border border-gray-300 @error('gender') border-red-500 @enderror rounded-lg px-3 py-2.5 text-sm focus:ring-primary focus:border-primary font-medium bg-gray-50/50">
                                <option value="male" {{ old('gender', $entity->gender) == 'male' ? 'selected' : '' }}>♂ Male</option>
                                <option value="female" {{ old('gender', $entity->gender) == 'female' ? 'selected' : '' }}>♀ Female</option>
                                <option value="other" {{ old('gender', $entity->gender) == 'other' ? 'selected' : '' }}>🔄 Other</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Name Alias (Separated by Commas)</label>
                        <input type="text" name="display_aliases" value="{{ old('display_aliases', $entity->display_aliases) }}" placeholder="Ex: Mok Gyeongun, Gyeongun, Asura Demon" class="block w-full border border-gray-300 @error('display_aliases') border-red-500 @enderror rounded-lg px-3 py-2.5 text-sm focus:ring-primary focus:border-primary font-medium">
                        <span class="text-[10px] text-gray-400 mt-1 block leading-relaxed">Important: Use commas. The first name before comma will automatically be designated as the <strong>Primary Name</strong>.</span>
                        @error('display_aliases')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">URL Image</label>
                        <input type="text" name="image_url" value="{{ old('image_url', $entity->image_url) }}" placeholder="Ex: https://example.com/image.jpg" class="block w-full border border-gray-300 @error('image_url') border-red-500 @enderror rounded-lg px-3 py-2.5 text-sm focus:ring-primary focus:border-primary font-medium">
                        @error('image_url')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Description (Indonesian)</label>
                        <textarea name="desc_id" rows="3" placeholder="Tulis ringkasan info entitas dalam Bahasa Indonesia..." class="w-full bg-gray-50 border border-gray-200 @error('desc_id') border-red-500 @enderror text-sm rounded-lg px-3 py-2.5 outline-none focus:border-gray-900 font-medium placeholder-gray-400 transition-all">{{ old('desc_id', $entity->translations()->where('locale', 'id')->value('description')) }}</textarea>
                        @error('desc_id')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Description (English)</label>
                        <textarea name="desc_en" rows="3" placeholder="Write entity description summary in English..." class="w-full bg-gray-50 border border-gray-200 @error('desc_en') border-red-500 @enderror text-sm rounded-lg px-3 py-2.5 outline-none focus:border-gray-900 font-medium placeholder-gray-400 transition-all">{{ old('desc_en', $entity->translations()->where('locale', 'en')->value('description')) }}</textarea>
                        @error('desc_en')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('account.novels.show', $novel->id) }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-800 font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
                    <button type="submit" class="px-5 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/95 shadow-sm font-bold cursor-pointer">Save Entity</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection