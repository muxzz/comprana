<?php

namespace App\Http\Controllers;

use App\Events\CreateOrderEvent;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhooksController extends Controller
{
    //
    public function __invoke(Request $request)
    {
        $order = Order::find($request['order']);

        $response = Http::withToken(config('services.mercadopago.token'))
            ->withUrlParameters([
                'id' => $request['data']['id'],
            ])
            ->get('https://api.mercadopago.com/v1/payments/{id}');

        if ($response->successful()) {
            if (is_null($order->payment_id)) {
                CreateOrderEvent::dispatch($response['additional_info']['items'], $order);
                $order->payment_id = $response['id'];
            }
            $order->payment_status = $response['status'];

            $order->save();

            return Response(status: 200);
        }
    }
}
