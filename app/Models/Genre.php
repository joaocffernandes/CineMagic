<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
    protected $primaryKey = 'code';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    public function Movie()
    {
        return $this->hasMany(Movie::class);
    }
}
