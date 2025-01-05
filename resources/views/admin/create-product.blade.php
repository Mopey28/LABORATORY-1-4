@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="create-product">
    <h2>Create Product</h2>
    <form action="{{ route('admin.store-product') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}" required>
            @error('product_name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
            @error('price')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="discount">Discount (%)</label>
            <input type="number" step="0.01" name="discount" id="discount" class="form-control" value="{{ old('discount') }}">
            @error('discount')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') }}" required>
            @error('stock')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="brand">Brand</label>
            <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand', 'SHEIN') }}" required>
            @error('brand')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="main_category_id">Main Category</label>
            <select name="main_category_id" id="main_category_id" class="form-control">
                <option value="">Select Main Category</option>
                @foreach($mainCategories as $mainCategory)
                    <option value="{{ $mainCategory->id }}">{{ $mainCategory->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="sub_category_id">Sub Category</label>
            <select name="sub_category_id" id="sub_category_id" class="form-control">
                <option value="">Select Sub Category</option>
            </select>
        </div>
        <div class="form-group">
            <label for="sub_sub_category_id">Sub-Sub Category</label>
            <select name="sub_sub_category_id" id="sub_sub_category_id" class="form-control">
                <option value="">Select Sub-Sub Category</option>
            </select>
        </div>
        {{-- <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control">
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group">
            <label for="images">Images</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple required>
            @error('images.*')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch sub-categories based on selected main category
    document.querySelector('#main_category_id').addEventListener('change', function() {
        var mainCategoryId = this.value;
        var subCategorySelect = document.querySelector('#sub_category_id');
        var subSubCategorySelect = document.querySelector('#sub_sub_category_id');

        // Clear existing options
        subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
        subSubCategorySelect.innerHTML = '<option value="">Select Sub-Sub Category</option>';

        if (mainCategoryId) {
            fetch('/admin/show-sub-categories/' + mainCategoryId)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subCategory => {
                        var option = document.createElement('option');
                        option.value = subCategory.id;
                        option.textContent = subCategory.name;
                        subCategorySelect.appendChild(option);
                    });
                });
        }
    });

    // Fetch sub-sub-categories based on selected sub category
    document.querySelector('#sub_category_id').addEventListener('change', function() {
        var subCategoryId = this.value;
        var subSubCategorySelect = document.querySelector('#sub_sub_category_id');

        // Clear existing options
        subSubCategorySelect.innerHTML = '<option value="">Select Sub-Sub Category</option>';

        if (subCategoryId) {
            fetch('/admin/show-sub-sub-categories/' + subCategoryId)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subSubCategory => {
                        var option = document.createElement('option');
                        option.value = subSubCategory.id;
                        option.textContent = subSubCategory.name;
                        subSubCategorySelect.appendChild(option);
                    });
                });
        }
    });
});
</script>
@endsection
