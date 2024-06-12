<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Theater extends Model
{
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'photo_filename',
    ];

    public function Seat(){
        return $this->hasMany(Seat::class, 'theater_id');
    }
}
