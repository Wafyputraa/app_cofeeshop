<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // USERS
        User::create(['role' => 'admin', 'name' => 'Administrator', 'email' => 'admin@gmail.com', 'password' => Hash::make('test123')]);
        User::create(['role' => 'kepala', 'name' => 'Kepala', 'email' => 'kepala@gmail.com', 'password' => Hash::make('test123')]);

        // MEJA
        $tables = [];
        foreach (['A1','A2','A3','B1','B2','B3','C1','C2'] as $num) {
            $tables[] = Table::create(['table_number' => $num, 'qr_code_link' => null]);
        }

        // KATEGORI
        $cats = [];
        foreach (['Kopi','Non-Kopi','Snack','Minuman Dingin'] as $name) {
            $cats[$name] = Category::create(['name' => $name]);
        }

        // MENU
        $menuData = [
            ['category' => 'Kopi',           'name' => 'Americano',          'price' => 18000],
            ['category' => 'Kopi',           'name' => 'Cappuccino',         'price' => 22000],
            ['category' => 'Kopi',           'name' => 'Latte',              'price' => 24000],
            ['category' => 'Kopi',           'name' => 'Cold Brew',          'price' => 26000],
            ['category' => 'Kopi',           'name' => 'Caramel Macchiato',  'price' => 28000],
            ['category' => 'Non-Kopi',       'name' => 'Matcha Latte',       'price' => 25000],
            ['category' => 'Non-Kopi',       'name' => 'Cokelat Panas',      'price' => 20000],
            ['category' => 'Non-Kopi',       'name' => 'Teh Tarik',          'price' => 15000],
            ['category' => 'Snack',          'name' => 'Croissant',          'price' => 16000],
            ['category' => 'Snack',          'name' => 'Roti Bakar Cokelat', 'price' => 14000],
            ['category' => 'Snack',          'name' => 'French Fries',       'price' => 18000],
            ['category' => 'Minuman Dingin', 'name' => 'Es Lemon Tea',       'price' => 14000],
            ['category' => 'Minuman Dingin', 'name' => 'Fruit Punch',        'price' => 18000],
            ['category' => 'Minuman Dingin', 'name' => 'Es Kopi Susu',       'price' => 22000],
        ];

        $menus = [];
        foreach ($menuData as $m) {
            $menus[] = Menu::create([
                'category_id'  => $cats[$m['category']]->id,
                'name'         => $m['name'],
                'description'  => $m['name'] . ' - menu andalan Warso Coffee.',
                'price'        => $m['price'],
                'is_available' => true,
                'is_active'    => true,
                'stock'        => rand(20, 50),
            ]);
        }

        // ORDERS - 7 hari terakhir
        $temps   = ['Hot', 'Ice'];
        $ices    = ['Normal', 'Less Ice', 'No Ice'];
        $sugars  = ['Normal', 'Less Sugar', 'No Sugar'];
        $pays    = ['cash', 'qris', 'transfer'];
        $hours   = [8,9,10,11,12,13,14,15,16,17,18,19,20,21];

        $dist = [0=>14, 1=>9, 2=>11, 3=>7, 4=>10, 5=>8, 6=>6];

        foreach ($dist as $daysAgo => $count) {
            $date = Carbon::today()->subDays($daysAgo);
            for ($i = 0; $i < $count; $i++) {
                $t  = $date->copy()->setTime($hours[array_rand($hours)], rand(0,59), 0);
                $tb = $tables[array_rand($tables)];

                $n    = rand(1, min(3, count($menus)));
                $keys = array_rand($menus, $n);
                if (!is_array($keys)) $keys = [$keys];

                $total = 0;
                $items = [];
                foreach ($keys as $k) {
                    $qty = rand(1, 3);
                    $total += $menus[$k]->price * $qty;
                    $items[] = ['menu' => $menus[$k], 'qty' => $qty,
                        'temp' => $temps[array_rand($temps)],
                        'ice'  => $ices[array_rand($ices)],
                        'sug'  => $sugars[array_rand($sugars)]];
                }

                $paid = ($daysAgo > 0) ? true : (rand(1,10) > 2);

                $order = Order::create([
                    'order_code'     => 'ORD-' . strtoupper(Str::random(6)),
                    'table_id'       => $tb->id,
                    'total_price'    => $total,
                    'payment_status' => $paid ? 'Paid' : 'Unpaid',
                    'order_status'   => $paid ? 'Completed' : 'Pending',
                    'payment_method' => $paid ? $pays[array_rand($pays)] : null,
                    'created_at'     => $t,
                    'updated_at'     => $t,
                ]);

                foreach ($items as $it) {
                    OrderItem::create([
                        'order_id'    => $order->id,
                        'menu_id'     => $it['menu']->id,
                        'quantity'    => $it['qty'],
                        'price'       => $it['menu']->price,
                        'temperature' => $it['temp'],
                        'ice_level'   => $it['ice'],
                        'sugar_level' => $it['sug'],
                        'created_at'  => $t,
                        'updated_at'  => $t,
                    ]);
                }
            }
        }
    }
}
