@extends('layouts.user')

@section('title', 'Wishlist')

@section('content')
    <div class="wishlist-container">
        <div class="wishlist-header">
            <h2>Wishlist</h2>
        </div>
        <div class="wishlist-content">
            @if($wishlistItems->isEmpty())
                <div class="empty-wishlist">
                    <img src="{{ asset('images/empty-cart.png') }}" alt="Empty Wishlist" class="empty-wishlist-image">
                    <h2>YOUR WISHLIST IS EMPTY</h2>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary shop-now-btn">SHOP NOW</a>
                </div>
            @else
                <div class="wishlist-items">
                    <div class="wishlist-items-header">
                        <h2>ALL ITEMS ({{ $wishlistItems->count() }})</h2>
                    </div>
                    @foreach($wishlistItems as $item)
                        <div class="wishlist-item">
                            <div class="wishlist-item-image">
                                @foreach($item->images as $image)
                                    <img src="{{ asset('storage/public/images/' . $image->image_path) }}" alt="{{ $item->product_name }}" class="wishlist-item-image-thumbnail">
                                @endforeach
                            </div>
                            <div class="wishlist-item-info">
                                <h3>{{ $item->product_name }}</h3>
                                <p>{{ $item->description }}</p>
                                <p>Color: Beige / Size: {{ $item->pivot->size }}</p>
                                @if($item->discount > 0)
                                    <p>₱{{ number_format($item->getDiscountedPrice(), 2) }} <del>₱{{ number_format($item->price, 2) }}</del> ({{ $item->discount }}% off)</p>
                                @else
                                    <p>₱{{ number_format($item->price, 2) }}</p>
                                @endif
                            </div>
                            <div class="wishlist-item-actions">
                                <form action="{{ route('user.removeFromWishlist', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                                <form action="{{ route('user.add-to-cart', $item->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="size" value="{{ $item->pivot->size }}">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
