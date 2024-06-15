<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Screening;
use Illuminate\Support\Facades\DB;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;


class StatsController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->type === 'A') {
            $minimo = Ticket::min('price');
            $maximo = Ticket::max('price');
            $media = Ticket::avg('price');
            $mediaFinal = number_format($media, 2, '.', ' ');

            $filmesMenosVistos = DB::select("SELECT screenings.movie_id
                FROM tickets
                JOIN screenings ON screenings.id = tickets.screening_id
                JOIN movies ON screenings.movie_id = movies.id
                GROUP BY screenings.movie_id
                HAVING COUNT(*) = (
                    SELECT MIN(count)
                    FROM (
                        SELECT COUNT(*) as count, screenings.movie_id
                        FROM tickets
                        JOIN screenings ON screenings.id = tickets.screening_id
                        GROUP BY screenings.movie_id
                    ) AS count_tickets_movie
                )");

            $filmesMaisVistos = DB::select("SELECT screenings.movie_id
                FROM tickets
                JOIN screenings ON screenings.id = tickets.screening_id
                JOIN movies ON screenings.movie_id = movies.id
                GROUP BY screenings.movie_id
                HAVING COUNT(*) = (
                    SELECT MAX(count)
                    FROM (
                        SELECT COUNT(*) as count, screenings.movie_id
                        FROM tickets
                        JOIN screenings ON screenings.id = tickets.screening_id
                        GROUP BY screenings.movie_id
                    ) AS count_tickets_movie
                )");

            $idsMenosVistos = array_map(function($filme) { return $filme->movie_id; }, $filmesMenosVistos);
            $idsMaisVistos = array_map(function($filme) { return $filme->movie_id; }, $filmesMaisVistos);

            $filmes = Movie::whereIn('id', $idsMenosVistos)
                ->orWhereIn('id', $idsMaisVistos)
                ->get()
                ->keyBy('id');

            $data = DB::table('movies')
                ->select(
                    DB::raw('genres.name AS genre'),
                    DB::raw('count(*) AS count')
                )
                ->join('genres', 'genres.code', '=', 'movies.genre_code')
                ->groupBy('genre_code')
                ->get();

            $array = [['genre', 'count']];
            foreach ($data as $key => $value) {
                $array[] = [$value->genre, $value->count];
            }

            return view('stats.index', [
                'minimo' => $minimo,
                'maximo' => $maximo,
                'media' => $mediaFinal,
                'filmes' => $filmes,
                'idsMenosVistos' => $idsMenosVistos,
                'idsMaisVistos' => $idsMaisVistos,
                'genre' => json_encode($array),
            ]);
        } else {
            return redirect('/')->with('error', 'Você não tem permissão para acessar esta página.');
        }
    }
}
