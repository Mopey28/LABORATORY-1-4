@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
<div class="manage-products">
    <h2>Manage Products</h2>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createProductModal">
        <i class="fas fa-plus"></i> Add Product
    </button>
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Brand</th>
                <th>Colors</th>
                <th>Sizes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    @if($product->images->isNotEmpty())
                        <img src="{{ asset('storage/public/images/' . $product->images->first()->image_path) }}" alt="{{ $product->product_name }}" width="50">
                    @else
                        <span>No Image</span>
                    @endif
                </td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->brand }}</td>
                <td>
                    @foreach($product->variations as $variation)
                        <span style="background-color: {{ $variation->color }}; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;">{{ $variation->color }}</span>
                    @endforeach
                </td>
                <td>
                    @foreach($product->variations as $variation)
                        <span class="size-option">{{ $variation->size }}</span>
                    @endforeach
                </td>
                <td>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editProductModal{{ $product->id }}">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteProductModal{{ $product->id }}">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
            <!-- Edit Product Modal -->
            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin.update-product', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" name="product_name" id="product_name" class="form-control" value="{{ $product->product_name }}" required>
                                    @error('product_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ $product->price }}" required>
                                    @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="discount">Discount (%)</label>
                                    <input type="number" step="0.01" name="discount" id="discount" class="form-control" value="{{ $product->discount }}">
                                    @error('discount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <input type="text" name="brand" id="brand" class="form-control" value="{{ $product->brand }}" required>
                                    @error('brand')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="main_category_id">Main Category</label>
                                    <select name="main_category_id" id="main_category_id" class="form-control">
                                        <option value="">Select Main Category</option>
                                        @foreach($mainCategories as $mainCategory)
                                        <option value="{{ $mainCategory->id }}" {{ $mainCategory->id == $product->main_category_id ? 'selected' : '' }}>{{ $mainCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sub_category_id">Sub Category</label>
                                    <select name="sub_category_id" id="sub_category_id" class="form-control">
                                        <option value="">Select Sub Category</option>
                                        @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ $subCategory->id == $product->sub_category_id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sub_sub_category_id">Sub-Sub Category</label>
                                    <select name="sub_sub_category_id" id="sub_sub_category_id" class="form-control">
                                        <option value="">Select Sub-Sub Category</option>
                                        @foreach($subSubCategories as $subSubCategory)
                                        <option value="{{ $subSubCategory->id }}" {{ $subSubCategory->id == $product->sub_sub_category_id ? 'selected' : '' }}>{{ $subSubCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="images">Images</label>
                                    <div class="existing-images">
                                        @foreach($product->images as $image)
                                        <img src="{{ asset('storage/public/images/' . $image->image_path) }}" alt="{{ $product->product_name }}" width="50">
                                        @endforeach
                                    </div>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple>
                                    @error('images.*')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Variations</label>
                                    <div id="variations-container">
                                        @foreach($product->variations as $index => $variation)
                                        <div class="variation">
                                            <input type="text" name="variations[{{ $index }}][color]" value="{{ $variation->color }}" placeholder="Color" required>
                                            <input type="text" name="variations[{{ $index }}][size]" value="{{ $variation->size }}" placeholder="Size" required>
                                            <input type="number" name="variations[{{ $index }}][stock]" value="{{ $variation->stock }}" placeholder="Stock" required>
                                        </div>
                                        @endforeach
                                    </div>
                                    <button type="button" id="add-variation">Add Variation</button>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Product Modal -->
            <div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteProductModalLabel{{ $product->id }}">Delete Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this product?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <form action="{{ route('admin.delete-product', $product->id) }}" method="POST">
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
<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Create Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
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
                    <div class="form-group">
                        <label for="images">Images</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple required>
                        @error('images.*')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Variations</label>
                        <div id="variations-container">
                            <div class="variation">
                                <input type="text" name="variations[0][color]" placeholder="Color" required>
                                <input type="text" name="variations[0][size]" placeholder="Size" required>
                                <input type="number" name="variations[0][stock]" placeholder="Stock" required>
                            </div>
                        </div>
                        <button type="button" id="add-variation">Add Variation</button>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch sub-categories based on selected main category
    document.querySelectorAll('#main_category_id').forEach(function(select) {
        select.addEventListener('change', function() {
            var mainCategoryId = this.value;
            var subCategorySelect = this.closest('.modal-content').querySelector('#sub_category_id');
            var subSubCategorySelect = this.closest('.modal-content').querySelector('#sub_sub_category_id');
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
    });

    // Fetch sub-sub-categories based on selected sub category
    document.querySelectorAll('#sub_category_id').forEach(function(select) {
        select.addEventListener('change', function() {
            var subCategoryId = this.value;
            var subSubCategorySelect = this.closest('.modal-content').querySelector('#sub_sub_category_id');
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

    // Add variation
    document.querySelectorAll('#add-variation').forEach(function(button) {
        button.addEventListener('click', function() {
            const variationsContainer = this.closest('.modal-content').querySelector('#variations-container');
            const variationCount = variationsContainer.getElementsByClassName('variation').length;
            const newVariation = document.createElement('div');
            newVariation.className = 'variation';
            newVariation.innerHTML = `
                <input type="text" name="variations[${variationCount}][color]" placeholder="Color" required>
                <input type="text" name="variations[${variationCount}][size]" placeholder="Size" required>
                <input type="number" name="variations[${variationCount}][stock]" placeholder="Stock" required>
            `;
            variationsContainer.appendChild(newVariation);
        });
    });
});
</script>
@endsection
