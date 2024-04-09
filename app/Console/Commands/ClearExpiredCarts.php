<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Gloudemans\Shoppingcart\Facades\Cart;

class ClearExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:clear-expired-carts';
    protected $signature = 'carts:clear';
   
    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Clear expired carts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCarts = Cart::content()->filter(function($cartItem, $rowId){
            // Lọc các giỏ hàng quá hạn dựa trên logic của bạn
            return $cartItem->created_at < now()->subMinutes(1);
        });

        foreach ($expiredCarts as $cartItem){
            Cart::remove($cartItem->rowId);
        }
        $this->info('Expired carts cleared successfully.');
    }
}
