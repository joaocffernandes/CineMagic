<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use App\Models\Genre;
use App\Models\Screening;
use App\Models\Theater;
use App\Models\Ticket;
use Carbon\Carbon;
use Database\Seeders\ScreeningsSeeder;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\select;

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
            'poster_filename' => 'sometimes|image|mimes:jpeg,png,jpg|max:4096',
            'synopsis' => 'required|string',
            'trailer_url' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('poster_filename')) {
            $path = $request->poster_filename->store('public/posters');
            $validated['poster_filename'] = basename($path);
        }
        $movie=Movie::create($validated);
        return redirect()->route('movies.index')->with('alert-msg', 'Movie ' . $movie->name . ' created');;
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
            'poster_filename' => 'sometimes|image|mimes:jpeg,png,jpg|max:4096',
            'synopsis' => 'required|string',
            'trailer_url' => 'string|max:255',
        ]);

        if ($request->hasFile('poster_filename')) {
            if (
                $movie->poster_filename &&
                Storage::fileExists('public/posters/' . $movie->poster_filename)
            ) {
                Storage::delete('public/posters/' . $movie->poster_filename);
            }
            $path = $request->poster_filename->store('public/posters');
            $validated['poster_filename'] = basename($path);
        }


        $movie->update($validated);
        return redirect()->route('movies.index')->with('alert-msg', 'Movie ' . $movie->name . ' updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): RedirectResponse
    {
        $screenings=Screening::query();
        $screenings=$screenings->where('movie_id', $movie->id)->count();
        if($screenings>0){
            return redirect()->route('movies.index')->with('alert-msg', 'Movie ' . $movie->name . ' has active screenings');
        }
        $movie->delete();
        return redirect()->route('movies.index')->with('alert-msg', 'Movie ' . $movie->name . ' deleted');
    }

    public function screenings(Request $request)
    {
        $query = Movie::query();
        $config = Configuration::query();
        $config = $config->where('id', 1)->first();
        $config->cprice = $config->ticket_price - $config->registered_customer_ticket_discount;


        // Join with the screenings table and filter movies that have screenings within the next two weeks
        $query->whereHas('screenings', function ($query) {
            $today = Carbon::today();
            $twoWeeksLater = Carbon::today()->addWeeks(2);
            $query->whereBetween('date', [$today, $twoWeeksLater]);
        });
        $theaters = Theater::all();
        $theaters = $theaters->pluck('name', 'id')->toArray();

        $filterByTheater = $request->query('theater');
        $filterByName = $request->query('name');

        // Apply filters based on the request query parameters
        if ($filterByTheater !== null) {
            $query->whereHas('screenings', function ($query) use ($filterByTheater) {
                $query->where('theater_id', $filterByTheater);
            });
        }
        if ($filterByName !== null) {
            $query->where('title', 'like', "%$filterByName%");
        }

        // Get the movies after applying filters
        $movies = $query->get();

        return view('screenings.index', [
            'movies' => $movies,
            'theaters' => $theaters,
            'filterByTheater' => $filterByTheater,
            'filterByName' => $filterByName,
            'config' => $config
        ]);
    }

    public function editScreening(string $screening)
    {
        $screening = Screening::where('id', $screening)->first();
        $movie = Movie::where('id', $screening->movie_id)->first();
        $allTheaters = Theater::all();
        $theaters = $allTheaters->pluck('name', 'id')->toArray();
        return view('screenings.edit')
            ->with('screening', $screening)
            ->with('movie', $movie)
            ->with('theaters', $theaters);
    }

    public function updateScreening(Request $request, Screening $screening)
    {
        $validated = $request->validate([
            'date.*' => 'required|date_format:Y-m-d',
            'start_time.*' => 'required|date_format:H:i:s',
            'theater' => 'required|int'
        ]);

        $screening->date = $validated['date'][0];
        $screening->start_time = $validated['start_time'][0];
        $screening->theater_id = $validated['theater'];
        $screening->save();

        return redirect()->route('screenings.index')->with('alert-msg', 'Screening ' . $screening->id . ' updated');
    }

    public function destroyScreening(string $screening)
    {
        $screening = Screening::where('id', $screening)->first();
        $tickets = Ticket::where('screening_id', $screening->id)->count();
        if ($tickets > 0) {
            return redirect()->route('screenings.index')->with('alert-msg', 'We already have tickets for this session');
        }
        $screening->delete();
        return redirect()->route('screenings.index')->with('alert-msg', 'Screening ' . $screening->id . ' deleted');
    }

    public function createScreening(Movie $movie)
    {
        $screening = new Screening();
        $screening->start_time = '00:00:00';
        $screening->date = Carbon::today()->toDateString();
        $allTheaters = Theater::all();
        $theaters = $allTheaters->pluck('name', 'id')->toArray();
        return view('screenings.create')
            ->with('screening', $screening)
            ->with('movie', $movie)
            ->with('theaters', $theaters);
    }

    public function storeScreening(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'date.*' => 'required|date_format:Y-m-d',
            'start_time.*' => 'required|date_format:H:i:s',
            'theater' => 'required|int'
        ]);

        foreach ($validated['date'] as $index => $date) {
            $screening = new Screening();
            $screening->date = $date;
            $screening->start_time = $validated['start_time'][$index];
            $screening->theater_id = $validated['theater'];
            $screening->movie_id = $movie->id;
            $screening->save();
        }

        return redirect()->route('screenings.index')->with('alert-msg', 'Screening for ' . $movie->title . ' created');
    }

    public function editPrice()
    {
        $config = Configuration::query();
        $config = $config->where('id', 1)->first();
        return view('configuration.index')->with('config', $config);
    }

    public function updatePrice(Request $request)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0.01',
            'discount' => 'required|numeric|min:0.01',
        ]);

        $config = Configuration::query();
        $config = $config->where('id', 1)->first();
        $config->ticket_price = $validated['price'];
        $config->registered_customer_ticket_discount = $validated['discount'];
        $config->save();
        return redirect()->route('screenings.index');
    }

    public function destroyPoster(Request $request, Movie $movie): RedirectResponse
    {

        if (!$movie->poster_filename) {
            return redirect()->back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', "No poster available to delete.");
        }

        $photoPath = 'public/posters/' . $movie->poster_filename;
        if (!Storage::exists($photoPath)) {
            return redirect()->back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "File does not exist on the server.");
        }

        Storage::delete($photoPath);
        $movie->poster_filename = null;
        $movie->save();

        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Poster of {$movie->title} has been deleted.");
    }
}
