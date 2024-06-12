<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left">Name</th>
                <th class="px-2 py-2 text-left">Number of Seats</th>
                <th class="px-2 py-2 text-left">Number of Rows</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($theaters as $theater)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-center">{{ $theater->name }}</td>
                <td class="px-2 py-2 text-center">{{ $theater->seat_count }}</td>
                <td class="px-2 py-2 text-center">{{ $theater->rows_count }}</td>
                <td class="px-2 py-2 text-right flex justify-center grow">
                    @if($showView)
                    <x-table.icon-show class="px-0.5" href="{{ route('theaters.show', ['theater' => $theater->id]) }}" />
                    @endif
                    @if($showEdit)
                    <x-table.icon-edit class="px-0.5" href="{{ route('theaters.edit', ['theater' => $theater->id]) }}" class="inline-block" />
                    @endif
                    @if($showDelete)
                    <x-table.icon-delete class="px-0.5" action="{{ route('theaters.destroy', ['theater' => $theater->id]) }}" class="inline-block" />
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>