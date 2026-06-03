<div class=" flex text-sm font-medium">
    @foreach($user->roles as $role)
        <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">{{ $role->name }}</span>
    @endforeach
</div>