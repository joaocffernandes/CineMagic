<div {{ $attributes }}>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left">Movie</th>
            <th class="px-2 py-2 text-left">Date</th>
            <th class="px-2 py-2 text-left">Time</th>
            <th class="px-2 py-2 text-left">Theater</th>
            <th class="px-2 py-2 text-left">Seat</th>
            <th class="px-2 py-2 text-left">Price</th>
            <th class="px-2 py-2 text-left">Status</th>
            @if($showView)
                <th></th>
            @endif
            @if($showEdit)
                <th></th>
            @endif
            @if($showDelete)
                <th></th>
            @endif
            @if($showAddToCart)
                <th></th>
            @endif
            @if($showRemoveFromCart)
                <th></th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($tickets as $ticket)
            @php 
                $isTempTicket = ! \App\Models\Ticket::where('id', $ticket->id)->exists(); 
            @endphp
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">{{ $ticket->screening->movie->title }}</td>
                <td class="px-2 py-2 text-left">{{ $ticket->screening->date }}</td>
                <td class="px-2 py-2 text-left">{{ $ticket->screening->start_time }}</td>
                <td class="px-2 py-2 text-left">{{ $ticket->screening->theater->name }}</td>
                <td class="px-2 py-2 text-center">{{ $ticket->seat->row . $ticket->seat->seat_number }}</td>
                <td class="px-2 py-2 text-left">{{ $ticket->price }}</td>
                <td class="px-2 py-2 text-left">{{ $ticket->status }}</td>
                @if($showView)
                    <td>
                        <x-table.icon-show class="ps-3 px-0.5"
                            href="{{ $isTempTicket ? route('tickets.showBySession', ['screeningId' => $ticket->screening_id, 'seatId' => $ticket->seat_id]) :
                                                     route('tickets.show', ['ticket' => $ticket])}}"/>
                    </td>
                @endif
                @if($showEdit && !$isTempTicket)
                    <td>
                        <x-table.icon-edit class="px-0.5"
                            href="{{ route('tickets.edit', ['ticket' => $ticket->id]) }}"/>
                    </td>
                @endif
                @if($showDelete && !$isTempTicket)
                    <td>
                        <x-table.icon-delete class="px-0.5"
                            action="{{ route('tickets.destroy', ['ticket' => $ticket->id]) }}"/>
                    </td>
                @endif
                @if($showRemoveFromCart)
                    <td>
                        <x-table.icon-minus class="px-0.5"
                            method="delete"
                            action="{{ route('cart.remove', ['screeningId' => $ticket->screening_id, 'seatId' => $ticket->seat_id]) }}"/>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
