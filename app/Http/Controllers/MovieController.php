<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allMovies = Movie::all();
        return view('movies.index')->with('movies', $allMovies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newMovie = new Movie();
        return view('movies.create')->with('movie', $newMovie);
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
            'trailer_url' => 'string|max:255',
            ]);
        Movie::create($validated);
        return redirect()->route('movies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return view('movies.show')->with('movie', $movie);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        return view('movies.edit')->with('movie', $movie);
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
}
