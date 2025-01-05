@extends('layouts.admin')

@section('title', 'Sub Categories')

@section('content')
<div class="container">
    <h1 class="mt-4">Manage Sub-Categories</h1>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createSubCategoryModal">
        + Create Sub Category
    </button>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Main Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subCategories as $subCategory)
                <tr>
                    <td>{{ $subCategory->name }}</td>
                    <td>{{ $subCategory->mainCategory->name }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSubCategoryModal{{ $subCategory->id }}">
                            Edit
                        </button>
                        <form action="{{ route('admin.delete-sub-category', $subCategory->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this sub category?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Edit Sub Category Modal -->
                <div class="modal fade" id="editSubCategoryModal{{ $subCategory->id }}" tabindex="-1" role="dialog" aria-labelledby="editSubCategoryModalLabel{{ $subCategory->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSubCategoryModalLabel{{ $subCategory->id }}">Edit Sub Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.update-sub-category', $subCategory->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $subCategory->name }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="main_category_id">Main Category</label>
                                        <select name="main_category_id" id="main_category_id" class="form-control" required>
                                            <option value="">Select Main Category</option>
                                            @foreach($mainCategories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $subCategory->main_category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('main_category_id')
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

<!-- Create Sub Category Modal -->
<div class="modal fade" id="createSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createSubCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubCategoryModalLabel">Create Sub Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.store-sub-category') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="main_category_id">Main Category</label>
                        <select name="main_category_id" id="main_category_id" class="form-control" required>
                            <option value="">Select Main Category</option>
                            @foreach($mainCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('main_category_id')
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
