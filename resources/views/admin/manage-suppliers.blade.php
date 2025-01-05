@extends('layouts.admin')

@section('title', 'Manage Suppliers')

@section('content')
<div class="manage-suppliers">
    <h2>Manage Suppliers</h2>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createSupplierModal">
        <i class="fas fa-plus"></i> Add Supplier
    </button>
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
                <tr>
                    <td>
                        @if($supplier->image_path)
                            <img src="{{ asset('storage/public/images/' . $supplier->image_path) }}" alt="{{ $supplier->supplier_name }}" width="50">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $supplier->supplier_name }}</td>
                    <td>{{ $supplier->email_address }}</td>
                    <td>{{ $supplier->phone_number }}</td>
                    <td>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editSupplierModal{{ $supplier->id }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteSupplierModal{{ $supplier->id }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>

                <!-- Edit Supplier Modal -->
                <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" role="dialog" aria-labelledby="editSupplierModalLabel{{ $supplier->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSupplierModalLabel{{ $supplier->id }}">Edit Supplier</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.update-supplier', $supplier->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="supplier_name">Name</label>
                                        <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="{{ $supplier->supplier_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email_address">Email Address</label>
                                        <input type="email" class="form-control" id="email_address" name="email_address" value="{{ $supplier->email_address }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $supplier->phone_number }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Supplier Modal -->
                <div class="modal fade" id="deleteSupplierModal{{ $supplier->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteSupplierModalLabel{{ $supplier->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteSupplierModalLabel{{ $supplier->id }}">Delete Supplier</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this supplier?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <form action="{{ route('admin.delete-supplier', $supplier->id) }}" method="POST" style="display:inline;">
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

<!-- Create Supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" role="dialog" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSupplierModalLabel">Create Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.store-supplier') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="supplier_name">Name</label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name">
                    </div>
                    <div class="form-group">
                        <label for="email_address">Email Address</label>
                        <input type="email" class="form-control" id="email_address" name="email_address">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number">
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
