@extends('layouts.user')

@section('title', 'Cart')

@section('content')
<div class="cart-container">
    <div class="cart-content">
        @if($cartItems->isEmpty())
            <div class="empty-cart">
                <img src="{{ asset('images/empty-cart.png') }}" alt="Empty Cart" class="empty-cart-image">
                <h2>YOUR CART IS EMPTY</h2>
                <a href="{{ route('user.dashboard') }}" class="btn btn-primary shop-now-btn">SHOP NOW</a>
            </div>
        @else
            <div class="cart-items-container">
                <div class="cart-items">
                    <div class="cart-items-header">
                        <div class="select-all">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                            <label for="selectAll">Select All</label>
                        </div>
                        <h2>ALL ITEMS ({{ $cartItems->count() }})</h2>
                    </div>
                    @foreach($cartItems as $item)
                        <div class="cart-item" data-item-id="{{ $item->id }}" data-color="{{ $item->pivot->color }}" data-size="{{ $item->pivot->size }}">
                            <div class="cart-item-select">
                                <input type="checkbox" class="item-checkbox" data-item-id="{{ $item->id }}" data-color="{{ $item->pivot->color }}" data-size="{{ $item->pivot->size }}" onchange="updateOrderSummary()">
                            </div>
                            <div class="cart-item-image">
                                @if($item->images->isNotEmpty())
                                    <img src="{{ asset('storage/public/images/' . $item->images->first()->image_path) }}" alt="{{ $item->product_name }}" class="cart-item-image-thumbnail">
                                @else
                                    <span>No Image</span>
                                @endif
                            </div>
                            <div class="cart-item-info">
                                <h3>{{ $item->product_name }}</h3>
                                <p>{{ $item->description }}</p>
                                <p>Color:
                                    <span class="color-box" style="background-color: {{ $item->pivot->color }};">
                                        <span class="color-name">{{ ucfirst($item->pivot->color) }}</span>
                                    </span>
                                    / Size: {{ $item->pivot->size }}
                                </p>
                                @if($item->discount > 0)
                                    <p class="discounted-price">₱{{ number_format($item->getDiscountedPrice() * $item->pivot->quantity, 2) }} <del>₱{{ number_format($item->price * $item->pivot->quantity, 2) }}</del> ({{ $item->discount }}% off)</p>
                                @else
                                    <p>₱{{ number_format($item->price * $item->pivot->quantity, 2) }}</p>
                                @endif
                            </div>
                            <div class="cart-item-actions">
                                <div class="quantity">
                                    <span>Qty:</span>
                                    <input type="number" value="{{ $item->pivot->quantity }}" min="1" class="quantity-input" data-item-id="{{ $item->id }}" data-color="{{ $item->pivot->color }}" data-size="{{ $item->pivot->size }}" onchange="updateQuantity({{ $item->id }}, '{{ $item->pivot->color }}', '{{ $item->pivot->size }}', this)">
                                </div>
                                <form action="{{ route('user.removeFromCart', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="color" value="{{ $item->pivot->color }}">
                                    <input type="hidden" name="size" value="{{ $item->pivot->size }}">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <div class="order-summary-details" id="orderSummaryDetails">
                        <p>Retail Price: ₱0.00</p>
                        <p>Promotions: -₱0.00</p>
                        <p>Coupon: -₱0</p>
                        <p>Estimated Price: ₱0.00</p>
                        <p>Already saved: ₱0.00</p>
                        <div id="selectedItemsImages"></div>
                    </div>
                    <a href="#" class="btn btn-primary checkout-btn" onclick="validateCheckout()">Checkout Now (0)</a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function updateQuantity(productId, color, size, input) {
        const quantity = input.value;
        fetch('{{ url('user/update-cart-quantity') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
                color: color,
                size: size
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Automatically reload the page to reflect the changes
                location.reload();
            } else {
                alert(data.message);
                // Revert the quantity input to its previous value
                input.value = data.current_quantity;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function toggleSelectAll(checkbox) {
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        itemCheckboxes.forEach(itemCheckbox => {
            itemCheckbox.checked = checkbox.checked;
        });
        updateOrderSummary();
    }

    function updateOrderSummary() {
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedItems = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked);
        const selectedItemIds = selectedItems.map(checkbox => {
            return {
                item_id: checkbox.getAttribute('data-item-id'),
                color: checkbox.getAttribute('data-color'),
                size: checkbox.getAttribute('data-size')
            };
        });

        fetch('{{ url('user/get-selected-cart-items') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: selectedItemIds
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('orderSummaryDetails').innerHTML = `
                <p>Retail Price: ₱${data.retail_price.toFixed(2)}</p>
                <p>Promotions: -₱${data.promotions.toFixed(2)}</p>
                <p>Coupon: -₱0</p>
                <p>Estimated Price: ₱${data.estimated_price.toFixed(2)}</p>
                <p>Already saved: ₱${data.saved.toFixed(2)}</p>
                <div id="selectedItemsImages"></div>
            `;
            document.querySelector('.checkout-btn').innerHTML = `Checkout Now (${data.total_quantity})`;

            // Display selected item images
            const selectedItemsImagesDiv = document.getElementById('selectedItemsImages');
            selectedItemsImagesDiv.innerHTML = '';
            selectedItems.forEach(checkbox => {
                const itemId = checkbox.getAttribute('data-item-id');
                const itemImage = document.querySelector(`.cart-item[data-item-id="${itemId}"] .cart-item-image img`).src;
                const imgElement = document.createElement('img');
                imgElement.src = itemImage;
                imgElement.style.width = '50px';
                imgElement.style.height = 'auto';
                imgElement.style.marginRight = '5px';
                selectedItemsImagesDiv.appendChild(imgElement);
            });
        })
        .catch(error => console.error('Error:', error));
    }

    function validateCheckout() {
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedItems = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked);

        if (selectedItems.length === 0) {
            alert('Please select at least one item to checkout.');
            return;
        }

        window.location.href = '{{ route('user.checkout') }}';
    }

    // Initial load of order summary
    document.addEventListener('DOMContentLoaded', function() {
        updateOrderSummary();
    });
</script>

<style>
    .color-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 30px;
        border-radius: 5px;
        margin-right: 5px;
        color: #fff;
        text-transform: capitalize;
        font-size: 14px;
        padding: 0 10px;
    }
    .color-name {
        font-size: 14px;
    }
</style>
@endsection
