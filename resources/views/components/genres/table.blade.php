<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left flex justify-start">Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($genres as $genre)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left flex grow">{{ $genre->name }}</td>
                <td class="text-right">
                    <div class="flex justify-end">
                        @if($showEdit)
                        <x-table.icon-edit class="px-0.5" href="{{ route('genres.edit', ['genre' => $genre->code]) }}" class="inline-block" />
                        @endif
                        @if($showDelete)
                        <x-table.icon-delete class="px-0.5" action="{{ route('genres.destroy', ['genre' => $genre->code]) }}" class="inline-block" />
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>