<?php 



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'quantity', 'low_stock_threshold'];

    protected static function booted()
    {
        static::saved(function ($product) {
            if ($product->quantity <= $product->low_stock_threshold) {
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new LowStockNotification($product));
            }
        });
    }
}