{{-- Versão simplicada para dar no pdf --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 17px;
        }
        .ticket {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .qr-code {
            text-align: left;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>Ticket</h1>
        </div>
        <p>Ticket Id: {{ $ticket->id }}</p>
        <p>Theater: {{ $ticket->screening->theater->name }}</p>
        <p>Movie: {{ $ticket->screening->movie->title }}</p>
        <p>Date: {{ $ticket->screening->date }}</p>
        <p>Time: {{ $ticket->screening->start_time }}</p>
        <p>Seat: {{ $ticket->seat->row . $ticket->seat->seat_number }}</p>
        @if($ticket->purchase->customer)
            <p>Name: {{ $ticket->purchase->customer->user->name }}</p>
            <p>Email: {{ $ticket->purchase->customer->user->email }} </p>
            @if($ticket->purchase->customer->user->getPhotoFullUrl != null)
                <x-field.image name="photo_filename" label="Photo" width="md" :readonly="true" :deleteAllow="false" :imageUrl="$ticket->purchase->customer->user->getPhotoFullUrl" />
            @endif
        @endif
        @if($ticket->qrcode_url)
            <div class="qr-code">
                <img src="{{ $qrImageSrc }}" alt="QR Code">
            </div>
        @else
            <p>No qrcode image available</p>
        @endif
    </div>
</body>
</html>
