<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use App\Models\Genre;
use Carbon\Carbon;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with the query builder instead of retrieving all records
        $query = Movie::query();
        $allGenres = Genre::all();
        $genres = $allGenres->pluck('name', 'code')->toArray();

        $filterByGenre = $request->query('genre');
        $filterByName = $request->query('name');

        // Apply filters based on the request query parameters
        if ($filterByGenre !== null) {
            $query->where('genre_code', $filterByGenre);
        }
        if ($filterByName !== null) {
            $query->where('title', 'like', "%$filterByName%");
        }

        // Get the movies after applying filters
        $movies = $query->get();

        return view('movies.index', [
            'movies' => $movies,
            'genres' => $genres,
            'filterByGenre' => $filterByGenre,
            'filterByName' => $filterByName
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newMovie = new Movie();
        $allGenres = Genre::all();
        $genres = $allGenres->pluck('name', 'code')->toArray();
        return view('movies.create')
            ->with('movie', $newMovie)
            ->with('genres', $genres);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre_code' => 'required|string|max:20',
            'year' => 'required|integer|min:1900|max:2100',
            'poster_filename' => 'string|max:255',
            'synopsis' => 'required|string',
            'trailer_url' => 'nullable|string|max:255',
        ]);
        Movie::create($validated);
        return redirect()->route('movies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        $allGenres = Genre::all();
        $genres = $allGenres->pluck('name', 'code')->toArray();
        return view('movies.show')
            ->with('movie', $movie)
            ->with('genres', $genres);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        $allGenres = Genre::all();
        $genres = $allGenres->pluck('name', 'code')->toArray();
        return view('movies.edit')
            ->with('movie', $movie)
            ->with('genres', $genres);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre_code' => 'required|string|max:20',
            'year' => 'required|integer|min:1900|max:2100',
            'poster_filename' => 'string|max:255',
            'synopsis' => 'required|string',
            'trailer_url' => 'string|max:255',
        ]);
        $movie->update($validated);
        return redirect()->route('movies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();
        return redirect()->route('movies.index');
    }

    public function screenings(Request $request)
    {
        $query = Movie::query();

        // Join with the screenings table and filter movies that have screenings within the next two weeks
        $query->whereHas('screenings', function ($query) {
            $today = Carbon::today();
            $twoWeeksLater = Carbon::today()->addWeeks(2);
            $query->whereBetween('date', [$today, $twoWeeksLater]);
        });
        $allGenres = Genre::all();
        $genres = $allGenres->pluck('name', 'code')->toArray();

        $filterByGenre = $request->query('genre');
        $filterByName = $request->query('name');

        // Apply filters based on the request query parameters
        if ($filterByGenre !== null) {
            $query->where('genre_code', $filterByGenre);
        }
        if ($filterByName !== null) {
            $query->where('title', 'like', "%$filterByName%");
        }

        // Get the movies after applying filters
        $movies = $query->get();

        return view('screenings.index', [
            'movies' => $movies,
            'genres' => $genres,
            'filterByGenre' => $filterByGenre,
            'filterByName' => $filterByName
        ]);
    }
}
