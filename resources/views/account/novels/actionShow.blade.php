<div class="flex items-center text-center text-sm font-medium">
    @can('entities.edit')
        <a href="{{ route('account.entities.edit', $entity->id) }}" class="text-primary hover:text-primary/90 mr-4 transition-colors">
            <x-icons.pencil width="20" height="20" />
        </a>
    @endcan

    @can('entities.delete')
        <button @click="$store.deleteModal.open('{{ route('account.entities.destroy', $entity->id) }}')"
            class="text-red-500 hover:text-red-500/90 cursor-pointer transition-colors">
            <x-icons.trash width="20" height="20" />
        </button>
    @endcan
</div>