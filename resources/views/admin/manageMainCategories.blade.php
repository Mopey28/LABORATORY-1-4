@extends('layouts.admin')

@section('title', 'Main Categories')

@section('content')
<div class="container">
    <h1 class="mt-4">Manage Main Categories</h1>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createMainCategoryModal">
        + Create Main Category
    </button>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mainCategories as $mainCategory)
                <tr>
                    <td>{{ $mainCategory->name }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editMainCategoryModal{{ $mainCategory->id }}">
                            Edit
                        </button>
                        <form action="{{ route('admin.delete-main-category', $mainCategory->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this main category?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Edit Main Category Modal -->
                <div class="modal fade" id="editMainCategoryModal{{ $mainCategory->id }}" tabindex="-1" role="dialog" aria-labelledby="editMainCategoryModalLabel{{ $mainCategory->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMainCategoryModalLabel{{ $mainCategory->id }}">Edit Main Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.update-main-category', $mainCategory->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $mainCategory->name }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Create Main Category Modal -->
<div class="modal fade" id="createMainCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createMainCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMainCategoryModalLabel">Create Main Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.store-main-category') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
