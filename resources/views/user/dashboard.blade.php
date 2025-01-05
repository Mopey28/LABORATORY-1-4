@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard">
    <h2>Products</h2>
    <div class="dashboard-layout">
        <form id="filterForm">
            <div class="filters-sidebar">
                <div class="filter-header">
                    <h4>Filter <span id="filterCount">(0)</span></h4>
                    <button type="button" class="btn btn-link clear-all-btn">Clear All</button>
                </div>
                <div class="filter-category">
                    <h5>Category</h5>
                    <div class="category-dropdown">
                        @foreach($mainCategories as $mainCategory)
                            <div class="category-item">
                                <label class="filter-label">
                                    <input type="checkbox" name="main_category[]" value="{{ $mainCategory->id }}" onchange="filterProducts()" class="filter-checkbox">
                                    {{ $mainCategory->name }}
                                    @if($mainCategory->subCategories->isNotEmpty())
                                        <button type="button" class="dropdown-toggle" onclick="toggleSubCategories(this)"></button>
                                    @endif
                                </label>
                                @if($mainCategory->subCategories->isNotEmpty())
                                    <div class="sub-categories" style="display: none;">
                                        @foreach($mainCategory->subCategories as $subCategory)
                                            <label class="filter-label">
                                                <input type="checkbox" name="sub_category[]" value="{{ $subCategory->id }}" onchange="filterProducts()" class="filter-checkbox">
                                                {{ $subCategory->name }}
                                                @if($subCategory->subSubCategories->isNotEmpty())
                                                    <button type="button" class="dropdown-toggle" onclick="toggleSubSubCategories(this)">+</button>
                                                @endif
                                            </label>
                                            @if($subCategory->subSubCategories->isNotEmpty())
                                                <div class="sub-sub-categories" style="display: none;">
                                                    @foreach($subCategory->subSubCategories as $subSubCategory)
                                                        <label class="filter-label">
                                                            <input type="checkbox" name="sub_sub_category[]" value="{{ $subSubCategory->id }}" onchange="filterProducts()" class="filter-checkbox">
                                                            {{ $subSubCategory->name }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="price-range-filter">
                    <label for="price_range">Price Range (PHP)</label>
                    <input type="number" name="min_price" id="min_price" class="form-control filter-input" placeholder="Min Price" oninput="filterProducts()">
                    <input type="number" name="max_price" id="max_price" class="form-control filter-input" placeholder="Max Price" oninput="filterProducts()">
                </div>
            </div>
        </form>
        <div class="products-container" id="productsContainer">
            @if($products->isEmpty())
                <p>No products found.</p>
            @else
                <div class="row" id="productList">
                    @foreach($products as $product)
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image-container">
                                    @if($product->images->isNotEmpty())
                                        <div class="image-slider">
                                            @foreach($product->images as $image)
                                                <div class="slide">
                                                    <img src="{{ asset('storage/public/images/' . $image->image_path) }}" alt="{{ $product->product_name }}" class="card-img-top">
                                                </div>
                                            @endforeach
                                            <button class="slider-btn prev">&#10094;</button>
                                            <button class="slider-btn next">&#10095;</button>
                                        </div>
                                    @endif
                                    @if($product->discount > 0)
                                        <div class="discount-badge">-{{ $product->discount }}%</div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title" title="{{ $product->product_name }}">{{ Str::limit($product->product_name, 30) }}</h5>
                                    @if($product->discount > 0)
                                        <p class="card-text product-price">
                                            <del class="original-price">₱{{ number_format($product->price, 2) }}</del>
                                            <span class="discounted-price">₱{{ number_format($product->getDiscountedPrice(), 2) }}</span>
                                        </p>
                                    @else
                                        <p class="card-text product-price">₱{{ number_format($product->price, 2) }}</p>
                                    @endif
                                    <div class="stock-and-cart">
                                        <p class="card-text">Stock: {{ $product->stock }}</p>
                                        <button type="button" class="btn btn-primary cart-icon" data-toggle="modal" data-target="#productModal{{ $product->id }}">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="productModalLabel{{ $product->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="productModalLabel{{ $product->id }}">{{ $product->product_name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="product-image-modal">
                                                    @if($product->images->isNotEmpty())
                                                        <div class="primary-image-modal">
                                                            <img src="{{ asset('storage/public/images/' . $product->images->first()->image_path) }}" alt="{{ $product->product_name }}" class="img-fluid">
                                                        </div>
                                                        <div class="thumbnails">
                                                            @foreach($product->images as $image)
                                                                <img src="{{ asset('storage/public/images/' . $image->image_path) }}" alt="{{ $product->product_name }}" width="50" class="thumbnail" data-full="{{ asset('storage/public/images/' . $image->image_path) }}">
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span>No Image</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="product-details">
                                                    <h5>{{ $product->product_name }}</h5>
                                                    <p>{{ $product->description }}</p>
                                                    @if($product->discount > 0)
                                                        <p class="product-price">
                                                            <del class="original-price">₱{{ number_format($product->price, 2) }}</del>
                                                            <span class="discounted-price">₱{{ number_format($product->getDiscountedPrice(), 2) }}</span>
                                                        </p>
                                                    @else
                                                        <p class="product-price">₱{{ number_format($product->price, 2) }}</p>
                                                    @endif
                                                    <p>Stock: {{ $product->stock }}</p>
                                                    <div class="variations">
                                                        <div class="variation-options">
                                                            <div class="color-options">
                                                                <h6>Color:</h6>
                                                                <div class="color-swatches">
                                                                    @foreach($product->variations->groupBy('color') as $color => $variations)
                                                                        <label class="color-swatch" style="background-color: {{ $color }};" data-color="{{ $color }}">
                                                                            <input type="radio" name="color" value="{{ $color }}" required>
                                                                            <span class="checkmark"></span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="size-options">
                                                                <h6>Size:</h6>
                                                                <div class="size-swatches">
                                                                    @foreach($product->variations->groupBy('size') as $size => $variations)
                                                                        <label class="size-swatch">
                                                                            <input type="radio" name="size" value="{{ $size }}" required>
                                                                            <span>{{ $size }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="add-to-cart">
                                                        <form action="{{ route('user.add-to-cart', $product->id) }}" method="POST" class="add-to-cart-form">
                                                            @csrf
                                                            <input type="hidden" name="color" id="selectedColor{{ $product->id }}">
                                                            <input type="hidden" name="size" id="selectedSize{{ $product->id }}">
                                                            <button type="submit" class="btn btn-primary btn-block" disabled id="addToCartButton{{ $product->id }}">Add to Cart</button>
                                                        </form>
                                                        <form action="{{ route('user.add-to-wishlist', $product->id) }}" method="POST" class="wishlist-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-wishlist">
                                                                <i class="far fa-heart"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterCount = document.getElementById('filterCount');
    const clearAllBtn = document.querySelector('.clear-all-btn');
    const filterForm = document.getElementById('filterForm');
    const productsContainer = document.getElementById('productsContainer');

    function updateFilterCount() {
        const checkedCount = Array.from(document.querySelectorAll('input[type="checkbox"]')).filter(checkbox => checkbox.checked).length;
        filterCount.textContent = `(${checkedCount})`;
    }

    function toggleSubCategories(button) {
        const subCategories = button.parentElement.parentElement.querySelector('.sub-categories');
        if (subCategories) {
            subCategories.style.display = subCategories.style.display === 'block' ? 'none' : 'block';
            button.textContent = subCategories.style.display === 'block' ? '-' : '+';
        }
    }

    function toggleSubSubCategories(button) {
        const subSubCategories = button.parentElement.parentElement.querySelector('.sub-sub-categories');
        if (subSubCategories) {
            subSubCategories.style.display = subSubCategories.style.display === 'block' ? 'none' : 'block';
            button.textContent = subSubCategories.style.display === 'block' ? '-' : '+';
        }
    }

    function filterProducts() {
        const formData = new FormData(filterForm);
        fetch("{{ route('user.filterProducts') }}", {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('productList').innerHTML = data.html;
            initializeSliders();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    clearAllBtn.addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
            const subCategories = checkbox.parentElement.parentElement.querySelector('.sub-categories');
            const subSubCategories = checkbox.parentElement.parentElement.querySelector('.sub-sub-categories');
            if (subCategories) {
                subCategories.style.display = 'none';
            }
            if (subSubCategories) {
                subSubCategories.style.display = 'none';
            }
        });
        updateFilterCount();
        filterProducts();
    });

    updateFilterCount();

    // Thumbnail click event
    document.querySelectorAll('.thumbnail').forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function() {
            const modalImage = this.closest('.modal').querySelector('.primary-image-modal img');
            modalImage.src = this.dataset.full;
        });
    });

    // Initialize sliders
    function initializeSliders() {
        document.querySelectorAll('.product-card').forEach(function(productCard) {
            let currentIndex = 0;
            const slides = productCard.querySelectorAll('.slide');
            const totalSlides = slides.length;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.display = i === index ? 'block' : 'none';
                });
            }

            productCard.querySelector('.slider-btn.prev').addEventListener('click', function() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                showSlide(currentIndex);
            });

            productCard.querySelector('.slider-btn.next').addEventListener('click', function() {
                currentIndex = (currentIndex + 1) % totalSlides;
                showSlide(currentIndex);
            });

            showSlide(currentIndex);
        });
    }

    // Initialize sliders on page load
    initializeSliders();

    // Initialize thumbnail click event for modal
    document.querySelectorAll('.thumbnail').forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function() {
            const modalImage = this.closest('.modal').querySelector('.primary-image-modal img');
            modalImage.src = this.dataset.full;
        });
    });

    // Initialize modal image
    document.querySelectorAll('.cart-icon').forEach(function(cartIcon) {
        cartIcon.addEventListener('click', function() {
            const productCard = this.closest('.product-card');
            const firstImage = productCard.querySelector('.slide img').src;
            const modalImage = document.querySelector('.primary-image-modal img');
            modalImage.src = firstImage;
        });
    });

    // Variation selection
    document.querySelectorAll('.color-swatch').forEach(function(colorSwatch) {
        colorSwatch.addEventListener('click', function() {
            const selectedColor = this.dataset.color;
            const productId = this.closest('.modal').id.replace('productModal', '');
            document.querySelectorAll(`#productModal${productId} .color-swatch`).forEach(function(swatch) {
                swatch.classList.remove('selected');
            });
            this.classList.add('selected');
            document.getElementById(`selectedColor${productId}`).value = selectedColor;
            checkAddToCartButton(productId);
        });
    });

    document.querySelectorAll('.size-swatch input').forEach(function(sizeOption) {
        sizeOption.addEventListener('change', function() {
            const selectedSize = this.value;
            const productId = this.closest('.modal').id.replace('productModal', '');
            document.querySelectorAll(`#productModal${productId} .size-swatch`).forEach(function(swatch) {
                swatch.classList.remove('selected');
            });
            this.parentElement.classList.add('selected');
            document.getElementById(`selectedSize${productId}`).value = selectedSize;
            checkAddToCartButton(productId);
        });
    });

    function checkAddToCartButton(productId) {
        const addToCartButton = document.getElementById(`addToCartButton${productId}`);
        const selectedColor = document.getElementById(`selectedColor${productId}`).value;
        const selectedSize = document.getElementById(`selectedSize${productId}`).value;
        if (selectedColor && selectedSize) {
            addToCartButton.disabled = false;
        } else {
            addToCartButton.disabled = true;
        }
    }
});

</script>
@endsection
