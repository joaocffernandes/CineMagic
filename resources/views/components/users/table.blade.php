<div class="w-full">
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left hidden lg:table-cell">Email</th>
                <th class="px-2 py-2 text-left">Name</th>
                <th class="px-2 py-2 text-left">Type</th>
                <th class="text-right"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $user->email }}</td>
                <td class="px-2 py-2 text-left">{{ $user->name }}</td>
                <td class="px-2 py-2 text-center">{{ $user->type }}</td>
                <td class="flex justify-end">
                    @if($showView)
                    <x-table.icon-show class="ps-3 px-0.5" href="{{ route('users.show', ['user' => $user]) }}" />
                    @endif
                    @if($showEdit)
                    <x-table.icon-edit class="px-0.5" href="{{ route('users.edit', ['user' => $user]) }}" />
                    @endif
                    @if($showDelete)
                    <x-table.icon-delete class="px-0.5" action="{{ route('users.destroy', ['user' => $user]) }}" />
                    @endif
                    @if($showBlock)
                    <form method="POST" action="{{ route('users.block', ['user' => $user]) }}">
                        @csrf @method('PUT')
                        @if($user->blocked == 1)
                        <button type="submit" name="put" class="w-6 h-6">
                            <svg class="h-7 w-8 text-blue-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M9 12l2 2l4 -4" />
                                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                            </svg>
                        </button>
                        @else
                        <button type="submit" name="put" class="w-6 h-6">
                            <svg class="h-7 w-8 text-red-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                                <path d="M10 10l4 4m0 -4l-4 4" />
                            </svg>
                        </button>
                        @endif
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>