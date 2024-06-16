<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',     
        'total_price',     
        'customer_name',   
        'customer_email',  
        'nif',             
        'payment_type',    
        'payment_ref',     
        'date',            
        'receipt_pdf_filename',  
        'status'           
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);//->withPivot('price');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
