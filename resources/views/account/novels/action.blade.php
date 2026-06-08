<div class="flex items-center text-center text-sm font-medium">
    @if($novel->status === 'approved')
        <a href="{{ route('account.novels.show', $novel->id) }}" 
           class="text-sky-500 hover:text-sky-600 mr-4 transition-colors" 
           title="View Detail">
            <x-icons.eye width="20" height="20" />
        </a>
    @endif

    @can('novels.edit')
        <a href="{{ route('account.novels.edit', $novel->id) }}" class="text-primary hover:text-primary/90 mr-4 transition-colors">
            <x-icons.pencil width="20" height="20" />
        </a>
    @endcan

    @can('novels.delete')
        <button @click="$store.deleteModal.open('{{ route('account.novels.destroy', $novel->id) }}')"
            class="text-red-500 hover:text-red-500/90 cursor-pointer transition-colors">
            <x-icons.trash width="20" height="20" />
        </button>
    @endcan
</div>