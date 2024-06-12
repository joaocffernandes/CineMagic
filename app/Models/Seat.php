<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'theather_id',
        'row',
        'seat_number',
    ];

    public function Theater(){
        return $this->belongsTo(Theater::class);
    }
}
