<!-- resources/views/admin/manage-orders.blade.php -->
@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('content')
<div class="container mt-5">
    <h1>Manage Orders</h1>
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
                <th>Status</th>
                <th>Actions</th>
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
                    <td>
                        <form action="{{ route('admin.update-order-status', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }}>Returned</option>
                                <option value="on hold" {{ $order->status == 'on hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="failed" {{ $order->status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.edit-order', $order->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('admin.delete-order', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
