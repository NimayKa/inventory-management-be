<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'categories',
        'action',
        'description',
        'quantity',
        'price',
        'picture',
        'inventory_id',
        'changed_by',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}