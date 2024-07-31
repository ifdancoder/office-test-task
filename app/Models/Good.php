<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\ImageableTrait;

class Good extends Model
{
    use ImageableTrait;
    use HasFactory;

    protected $fillable = [
        'external_code',
        'name',
        'description',
        'price',
        'discount'
    ];

    public function attributes()
    {
        return $this->hasMany(GoodAttribute::class);
    }

    public function getPriceWithDiscount()
    {
        return $this->price - $this->discount;
    }
}
