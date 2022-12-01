<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Tukang extends Model
{
    use HasFactory;
    protected $table = "tukangs";
    protected $fillable = [
        'tukang_name',
        'tukang_address',
        'rating',
    ];
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
