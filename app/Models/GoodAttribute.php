<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'good_id',
        'key',
        'value',
    ];

    public function good()
    {
        return $this->belongsTo(Good::class);
    }
}
