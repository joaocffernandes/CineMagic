<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = Genre::all();
        return view('genres.index')->with('genres', $genres);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newGenre = new Genre();
        return view('genres.create')
            ->with('genre', $newGenre);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['code' => strtoupper($request->code)]);
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:genres',
            'name' => 'required|string|max:255',
        ]);
        Genre::create($validated);
        return redirect()->route('genres.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genre $genre)
    {

        return view('genres.edit')
            ->with('genre', $genre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $genre->update($validated);
        return redirect()->route('genres.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect()->route('genres.index');
    }
}
