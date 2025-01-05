@extends('layouts.admin')

@section('title', 'Manage Variations')

@section('content')
<div class="manage-variations">
    <h2>Manage Variations</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Color</th>
                <th>Size</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($variations as $variation)
            <tr>
                <td>{{ $variation->product->product_name }}</td>
                <td>{{ $variation->color }}</td>
                <td>{{ $variation->size }}</td>
                <td>{{ $variation->stock }}</td>
                <td>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editVariationModal{{ $variation->id }}">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteVariationModal{{ $variation->id }}">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
            <!-- Edit Variation Modal -->
            <div class="modal fade" id="editVariationModal{{ $variation->id }}" tabindex="-1" role="dialog" aria-labelledby="editVariationModalLabel{{ $variation->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editVariationModalLabel{{ $variation->id }}">Edit Variation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin.update-variation', $variation->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text" name="color" id="color" class="form-control" value="{{ $variation->color }}" required>
                                    @error('color')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    <input type="text" name="size" id="size" class="form-control" value="{{ $variation->size }}" required>
                                    @error('size')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="stock">Stock</label>
                                    <input type="number" name="stock" id="stock" class="form-control" value="{{ $variation->stock }}" required>
                                    @error('stock')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Update Variation</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
