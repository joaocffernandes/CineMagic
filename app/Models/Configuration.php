<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'configuration';

    protected $fillable = [
        'ticket_price',
        'registered_customer_ticket_discount',
    ];

    public static function getSettings()
    {
        return self::first();
    }
}
