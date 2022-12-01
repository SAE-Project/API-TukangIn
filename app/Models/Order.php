<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = [
        'user_id',
        'tukang_id',
        'layanan_id',
        'order_date',
        'order_time',
        'order_address',
        'order_status',
        'order_end',
        'order_price',
        'is_paid',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tukang()
    {
        return $this->belongsTo(Tukang::class);
    }
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
