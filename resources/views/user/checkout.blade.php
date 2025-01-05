<!-- checkout.blade.php -->
@extends('layouts.user')

@section('title', 'Checkout')

@section('content')
<div class="checkout-container">
    <div class="checkout-content">
        <h2>Checkout</h2>
        <form action="{{ route('user.processCheckout') }}" method="POST" id="checkout-form">
            @csrf
            <div class="checkout-layout">
                <div class="checkout-left">
                    <div class="shipping-address">
                        <h3>Shipping Address</h3>
                        <div class="form-group">
                            <label for="location">Location*</label>
                            <select name="location" id="location" class="form-control" required>
                                <option value="Philippines" selected>Philippines</option>
                                <!-- Add more locations as needed -->
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="form-col">
                                    <label for="first_name">First Name*</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                                </div>
                                <div class="form-col">
                                    <label for="last_name">Last Name*</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number*</label>
                            <div class="input-group">
                                <span class="input-group-text">+63</span>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" required pattern="\d{10}" title="Please enter a 10-digit phone number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="state">State/Province*</label>
                            <input type="text" name="state" id="state" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="city">City/Municipality*</label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="post_zip_code">Post/Zip Code*</label>
                            <input type="text" name="post_zip_code" id="post_zip_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="address_line_1">Address Line 1*</label>
                            <input type="text" name="address_line_1" id="address_line_1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="address_line_2">Address Line 2</label>
                            <input type="text" name="address_line_2" id="address_line_2" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="checkout-right">
                    <div class="order-summary">
                        <h3>Order Summary</h3>
                        <div class="order-summary-details">
                            <p>Subtotal: ₱{{ number_format($subtotal, 2) }}</p>
                            <p>Promotions: -₱{{ number_format($promotions, 2) }}</p>
                            <p>Coupon: -₱0</p>
                            <p>Estimated Price: ₱{{ number_format($total, 2) }}</p>
                            <p>Already saved: ₱{{ number_format($promotions, 2) }}</p>
                            <div id="selectedItemsImages"></div>
                        </div>
                        <div class="payment-methods">
                            <h4>Payment Method</h4>
                            <div class="payment-options">
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="payment_method" value="PayPal">
        </form>
    </div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.sandbox.client_id') }}&currency=PHP"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paypalButtonContainer = document.getElementById('paypal-button-container');
        const requiredFields = document.querySelectorAll('[required]');
        const phoneNumberInput = document.getElementById('phone_number');
        let paypalButtonRendered = false;

        function checkRequiredFields() {
            let allFilled = true;
            requiredFields.forEach(field => {
                if (!field.value) {
                    allFilled = false;
                }
            });
            return allFilled;
        }

        function enablePayPalButton() {
            if (checkRequiredFields() && !paypalButtonRendered) {
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: '{{ $total }}' // Halagang ibabayad
                                }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            alert('Transaction completed by ' + details.payer.name.given_name);
                            // Magagamit mo ang details para sa iyong backend processing
                            document.getElementById('checkout-form').submit();
                        });
                    }
                }).render('#paypal-button-container');
                paypalButtonRendered = true;
            } else if (!checkRequiredFields() && paypalButtonRendered) {
                paypalButtonContainer.innerHTML = ''; // Clear the PayPal button if not all fields are filled
                paypalButtonRendered = false;
            }
        }

        requiredFields.forEach(field => {
            field.addEventListener('input', enablePayPalButton);
        });

        phoneNumberInput.addEventListener('input', function() {
            const value = phoneNumberInput.value;
            if (!/^\d{0,10}$/.test(value)) {
                phoneNumberInput.value = value.replace(/\D/g, ''); // Remove non-digit characters
            }
        });

        enablePayPalButton();
    });
</script>
@endsection
