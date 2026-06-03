@props(['label', 'status'])
<div class="flex items-center justify-center space-x-2 p-2 rounded-lg {{ $status ? 'bg-green-200' : 'bg-gray-200' }}">
    <div class="w-2 h-2 rounded-full {{ $status ? 'bg-green-500' : 'bg-gray-500' }}"></div>
    <span class="text-[11px] font-bold {{ $status ? 'text-green-700' : 'text-gray-700' }}">{{ $label }}</span>
</div>