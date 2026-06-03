<div class=" flex text-center text-sm font-medium">
    @can('permissions.edit')
        <a href="{{ route('account.permissions.edit', $permission->id) }}" class="text-primary hover:text-primary/90 mr-4">
            <x-icons.pencil width="20" height="20" />
        </a>
    @endcan

    @can('permissions.delete')
        <button @click="$store.deleteModal.open('{{ route('account.permissions.destroy', $permission->id) }}')"
            class="text-red-500 hover:text-red-500/90 cursor-pointer">
            <x-icons.trash width="20" height="20" />
        </button>
    @endcan
</div>