<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    public function index()
    {
        // Recupera todas as sessões com seus filmes relacionados e cinemas
        $screenings = Screening::with(['movie', 'theater'])->get();

        // Passa as sessões para a view
        return view('screenings.index', compact('screenings'));
    }

    // Relacionamento com o modelo Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Relacionamento com o modelo Theater
    public function theater()
    {
        return $this->belongsTo(Theater::class);
    }

    // Pode incluir outros atributos e métodos necessários
}
