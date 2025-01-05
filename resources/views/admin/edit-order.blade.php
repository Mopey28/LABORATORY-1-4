<!-- resources/views/admin/edit-order.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Order')

@section('content')
<div class="container mt-5">
    <h1>Edit Order</h1>
    <form action="{{ route('admin.update-order', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $order->first_name }}">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $order->last_name }}">
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $order->phone_number }}">
        </div>
        <div class="form-group">
            <label for="state">State</label>
            <input type="text" class="form-control" id="state" name="state" value="{{ $order->state }}">
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ $order->city }}">
        </div>
        <div class="form-group">
            <label for="post_zip_code">Post/Zip Code</label>
            <input type="text" class="form-control" id="post_zip_code" name="post_zip_code" value="{{ $order->post_zip_code }}">
        </div>
        <div class="form-group">
            <label for="address_line_1">Address Line 1</label>
            <input type="text" class="form-control" id="address_line_1" name="address_line_1" value="{{ $order->address_line_1 }}">
        </div>
        <div class="form-group">
            <label for="address_line_2">Address Line 2</label>
            <input type="text" class="form-control" id="address_line_2" name="address_line_2" value="{{ $order->address_line_2 }}">
        </div>
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <input type="text" class="form-control" id="payment_method" name="payment_method" value="{{ $order->payment_method }}">
        </div>
        <button type="submit" class="btn btn-primary">Update Order</button>
    </form>
</div>
@endsection
