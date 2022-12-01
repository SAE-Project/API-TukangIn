<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;
    protected $table = "layanans";
    protected $primaryKey = "layanan_id";
    protected $fillable = [
        'layanan_id',
        'layanan_name',
        'layanan_price',
    ];
    public function tukang()
    {
        return $this->hasMany(Tukang::class);
    }
}
