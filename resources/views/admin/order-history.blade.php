<!-- resources/views/admin/order-history.blade.php -->
@extends('layouts.admin')

@section('title', 'Order History')

@section('content')
<div class="container mt-5">
    <h1>Order History</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>State</th>
                <th>City</th>
                <th>Post/Zip Code</th>
                <th>Address Line 1</th>
                <th>Address Line 2</th>
                <th>Payment Method</th>
                <th>Items</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user_id }}</td>
                    <td>{{ $order->first_name }}</td>
                    <td>{{ $order->last_name }}</td>
                    <td>{{ $order->phone_number }}</td>
                    <td>{{ $order->state }}</td>
                    <td>{{ $order->city }}</td>
                    <td>{{ $order->post_zip_code }}</td>
                    <td>{{ $order->address_line_1 }}</td>
                    <td>{{ $order->address_line_2 }}</td>
                    <td>{{ $order->payment_method }}</td>
                    <td>
                        <ul>
                            @foreach($order->items as $item)
                                <li>
                                    {{ $item->product->name }} - {{ $item->quantity }} x {{ $item->price }}
                                    @if($item->product->images->isNotEmpty())
                                        <br>
                                        <img src="{{ asset('storage/images/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" width="50">
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $order->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
