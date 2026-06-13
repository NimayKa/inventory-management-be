<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'categories',
        'description',
        'quantity',
        'price',
        'picture',
    ];

    /**
     * Relationships
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}