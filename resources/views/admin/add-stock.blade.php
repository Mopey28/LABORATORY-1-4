@extends('layouts.admin')

@section('title', 'Add Stock')

@section('content')
<div class="container">
    <h2>Add Stock</h2>
    <form action="{{ route('admin.store-stock') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
        </div>
        <button type="submit" class="btn btn-primary">Add Stock</button>
    </form>
</div>
@endsection
