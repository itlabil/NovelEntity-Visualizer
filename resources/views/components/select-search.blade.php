@props([
    'name', 
    'label' => null, 
    'options' => [], 
    'selected' => null, 
    'placeholder' => 'Select Option',
    'error' => null
])

<div class="w-full mt-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <div x-data x-init="
        $nextTick(() => {
            $($refs.select).select2({
                placeholder: '{{ $placeholder }}',
                allowClear: true,
                width: '100%'
            }).on('change', function (e) {
                // Jika kamu pakai Livewire, tambahkan baris ini:
                // $dispatch('input', e.target.value);
            });
        })
    ">
        <select 
            x-ref="select" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            {{ $attributes->merge(['class' => 'select2-input block w-full']) }}
        >
            <option value=""></option> {{-- Penting untuk placeholder --}}
            @foreach($options as $id => $value)
                <option value="{{ $id }}" {{ $selected == $id ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    @if($error)
        <p class="text-red-500 text-xs mt-2">{{ $error }}</p>
    @endif
</div>

@once
    @push('styles')
        <style>
            /* Merapikan Select2 agar serasi dengan Tailwind */
            .select2-container--default .select2-selection--single {
                border-color: #D1D5DB !important;
                height: 42px !important;
                padding: 5px !important;
                border-radius: 0.5rem !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px !important;
            }
            .select2-dropdown {
                border-color: #D1D5DB !important;
                border-radius: 0.5rem !important;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            }
        </style>
    @endpush
@endonce