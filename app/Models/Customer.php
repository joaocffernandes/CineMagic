<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'nif',
        'payment_type',
        'payment_ref',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
