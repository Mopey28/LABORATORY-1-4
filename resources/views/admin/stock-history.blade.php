@extends('layouts.admin')

@section('title', 'Stock History')

@section('content')
<div class="container">
    <h1 class="mt-4">Stock History</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Old Stock</th>
                <th>Current Stock</th>
                <th>Added Stock</th>
                <th>Action</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockHistory as $history)
                <tr>
                    <td>{{ $history->product->product_name }}</td>
                    <td>{{ $history->old_stock }}</td>
                    <td>{{ $history->current_stock }}</td>
                    <td>{{ $history->added_stock }}</td>
                    <td>{{ $history->action }}</td>
                    <td>{{ $history->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
