<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Purchase Details PDF</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fff;
        }
        .container {
            padding: 5px;
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            background: #fafafa;
            border: 1px solid #dcdcdc;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2, h3 {
            color: #444;
            text-decoration: underline;
        }
        p {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 5px;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 5px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 5px 15px;
            text-align: center;
        }
        .section {
            margin-bottom: 0px;
            padding: 15px;
            background-color: #ffffff;
            border: 1px solid #eeeeee;
        }
        .strong {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Purchase Details</h2>
        </div>
        
        <div class="section">
            <h3>General Information</h3>
            <p><strong>Purchase ID:</strong> {{ $purchase->id }}</p>
            <p><strong>Total Price:</strong> €{{ number_format($purchase->total_price, 2) }}</p>
            <p><strong>Purchase Date:</strong> {{ $purchase->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="section">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $purchase->customer_name }}</p>
            <p><strong>Email:</strong> {{ $purchase->customer_email }}</p>
            @if ($purchase->customer_nif)
                <p><strong>NIF:</strong> {{ $purchase->customer_nif }}</p>
            @endif
        </div>

        <div class="section">
            <h3>Payment Details</h3>
            <p><strong>Payment Type:</strong> {{ $purchase->payment_type }}</p>
            <p><strong>Payment Reference:</strong> {{ $purchase->payment_ref }}</p>
        </div>

        <div class="section">
            <h3>Tickets Purchased</h3>
            @if ($tickets??false && $tickets->isNotEmpty())
                <ul>
                    @foreach ($tickets as $ticket)
                        <li>
                            <p><strong>Ticket ID:</strong> {{ $ticket->id }}</p>
                            <p><strong>Movie:</strong> {{ $ticket->screening->movie->title }}</p>
                            <p><strong>Theater:</strong> {{ $ticket->screening->theater->name }}</p>
                            <p><strong>Date:</strong> {{ $ticket->screening->date }}</p>
                            <p><strong>Time:</strong> {{ $ticket->screening->start_time }}</p>
                            <p><strong>Seat:</strong> {{ $ticket->seat->row . $ticket->seat->seat_number }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No tickets were found for this purchase.</p>
            @endif
        </div>
    </div>
</body>
</html>
