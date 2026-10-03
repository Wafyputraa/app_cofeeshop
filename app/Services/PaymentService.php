<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(Order $order): string
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_code,
                'gross_amount' => $order->total_price,
            ],
            'enabled_payments' => [
                'qris',
                'gopay' // GoPay di Midtrans pada dasarnya akan memunculkan QRIS juga
            ],
            'customer_details' => [
                'first_name' => 'Meja ' . $order->table->table_number,
            ],
            'item_details' => $this->buildItemDetails($order),
        ];

        return Snap::getSnapToken($params);
    }

    private function buildItemDetails(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id'       => 'MENU-' . $item->menu_id,
                'price'    => (int) ($item->price / $item->quantity),
                'quantity' => $item->quantity,
                'name'     => $item->menu->name,
            ];

            foreach ($item->addons as $addon) {
                $items[] = [
                    'id'       => 'ADDON-' . $addon->add_on_id,
                    'price'    => $addon->price,
                    'quantity' => $item->quantity,
                    'name'     => '+' . $addon->addOn->name,
                ];
            }
        }

        return $items;
    }
}
