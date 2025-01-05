@extends('layouts.admin')

@section('title', 'Manage Stock')

@section('content')
<div class="container">
    <h2>Manage Stock</h2>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addStockModal">
        <i class="fas fa-plus"></i> Add Stock
    </button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td>{{ $stock->product->product_name }}</td>
                    <td>{{ $stock->quantity }}</td>
                    <td>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editStockModal{{ $stock->id }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteStockModal{{ $stock->id }}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>

                <!-- Edit Stock Modal -->
                <div class="modal fade" id="editStockModal{{ $stock->id }}" tabindex="-1" role="dialog" aria-labelledby="editStockModalLabel{{ $stock->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editStockModalLabel{{ $stock->id }}">Edit Stock</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.update-stock', $stock->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="product_name">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $stock->product->product_name }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $stock->quantity }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Stock Modal -->
                <div class="modal fade" id="deleteStockModal{{ $stock->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteStockModalLabel{{ $stock->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteStockModalLabel{{ $stock->id }}">Delete Stock</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this stock?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <form action="{{ route('admin.delete-stock', $stock->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" role="dialog" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.store-stock') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="product_id">Product Name</label>
                        <select class="form-control" id="product_id" name="product_id">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Stock</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
