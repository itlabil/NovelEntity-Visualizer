<div class=" flex text-center text-sm font-medium">
    @can('roles.edit')
        <a href="{{ route('account.roles.edit', $role->id) }}" class="text-primary hover:text-primary/90 mr-4">
            <x-icons.pencil width="20" height="20" />
        </a>
    @endcan

    @can('roles.delete')
        <button @click="$store.deleteModal.open('{{ route('account.roles.destroy', $role->id) }}')"
            class="text-red-500 hover:text-red-500/90 cursor-pointer">
            <x-icons.trash width="20" height="20" />
        </button>
    @endcan
</div>