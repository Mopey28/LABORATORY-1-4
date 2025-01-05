@extends('layouts.admin')

@section('title', 'Sub sub-Categories')

@section('content')
<div class="container">
    <h1 class="mt-4">Manage Sub-Sub Categories</h1>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createSubSubCategoryModal">
        + Create Sub-Sub Category
    </button>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Sub Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subSubCategories as $subSubCategory)
                <tr>
                    <td>{{ $subSubCategory->name }}</td>
                    <td>{{ $subSubCategory->subCategory->name }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSubSubCategoryModal{{ $subSubCategory->id }}">
                            Edit
                        </button>
                        <form action="{{ route('admin.delete-sub-sub-category', $subSubCategory->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this sub-sub category?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Edit Sub-Sub Category Modal -->
                <div class="modal fade" id="editSubSubCategoryModal{{ $subSubCategory->id }}" tabindex="-1" role="dialog" aria-labelledby="editSubSubCategoryModalLabel{{ $subSubCategory->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSubSubCategoryModalLabel{{ $subSubCategory->id }}">Edit Sub-Sub Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.update-sub-sub-category', $subSubCategory->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $subSubCategory->name }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="sub_category_id">Sub Category</label>
                                        <select name="sub_category_id" id="sub_category_id" class="form-control" required>
                                            <option value="">Select Sub Category</option>
                                            @foreach($subCategories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $subSubCategory->sub_category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('sub_category_id')
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

<!-- Create Sub-Sub Category Modal -->
<div class="modal fade" id="createSubSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createSubSubCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubSubCategoryModalLabel">Create Sub-Sub Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.store-sub-sub-category') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sub_category_id">Sub Category</label>
                        <select name="sub_category_id" id="sub_category_id" class="form-control" required>
                            <option value="">Select Sub Category</option>
                            @foreach($subCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('sub_category_id')
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
