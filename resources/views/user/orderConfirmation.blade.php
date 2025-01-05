@extends('layouts.user')

@section('title', 'Order Confirmation')

@section('content')
<div class="order-confirmation-container">
    <div class="order-confirmation-content">
        <h2>Order Confirmation</h2>
        <p>Thank you for your order! Your order has been successfully placed.</p>
        <div class="order-details">
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
            <p><strong>Address:</strong> {{ $order->address_line_1 }}, {{ $order->city }}, {{ $order->state }}, {{ $order->post_zip_code }}</p>
            <p><strong>Phone Number:</strong> {{ $order->phone_number }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-primary continue-shopping-btn">Continue Shopping</a>
    </div>
</div>
@endsection
