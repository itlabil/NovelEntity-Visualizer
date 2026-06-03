<div class=" flex text-center text-sm font-medium">
    @can('users.edit')
        <a href="{{ route('account.users.edit', $user->id) }}" class="text-primary hover:text-primary/90 mr-4">
            <x-icons.pencil width="20" height="20" />
        </a>
    @endcan

    @can('users.delete')
        <button @click="$store.deleteModal.open('{{ route('account.users.destroy', $user->id) }}')"
            class="text-red-500 hover:text-red-500/90 cursor-pointer">
            <x-icons.trash width="20" height="20" />
        </button>
    @endcan
</div>