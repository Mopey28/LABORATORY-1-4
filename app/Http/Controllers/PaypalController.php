<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\Paypal as PaypalClient;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaypalController extends Controller
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PaypalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    public function paypal(Request $request)
    {
        // Save the order before redirecting to PayPal
        $user = Auth::user();
        $order = new Order();
        $order->user_id = $user->id;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->phone_number = $request->phone_number;
        $order->state = $request->state;
        $order->city = $request->city;
        $order->post_zip_code = $request->post_zip_code;
        $order->address_line_1 = $request->address_line_1;
        $order->address_line_2 = $request->address_line_2;
        $order->payment_method = 'PayPal';
        $order->save();

        $response = $this->provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "PHP",
                        "value" => $request->total
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('paypal.cancel');
        }
    }

    public function success(Request $request)
    {
        $response = $this->provider->capturePaymentOrder($request['token']);

        if (in_array(strtoupper($response['status']), ['COMPLETED', 'APPROVED'])) {
            // Process the payment success
            return redirect()->route('user.orderComplete', ['orderId' => $response['id']]);
        } else {
            return redirect()->route('paypal.cancel');
        }
    }

    public function cancel()
    {
        return redirect()->route('user.checkout')->with('error', 'Payment cancelled.');
    }
}
