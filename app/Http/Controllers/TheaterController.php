<?php

namespace App\Http\Controllers;

use App\Models\Theater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TheaterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $theaters = Theater::select('theaters.*')
            ->withCount([
                'Seat as seat_count',
                'Seat as rows_count' => function ($query) {
                    $query->select(DB::raw('count(distinct `row`)'));  // Count distinct rows only from non-deleted seats
                }
            ])
            ->get();

        // Returning the data to a view
        return view('theaters.index')->with('theaters', $theaters);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newTheater = new Theater();
        return view('theaters.create')->with('theater', $newTheater);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new theater using the validated data
        $theater = Theater::create($validated);

        if ($request->has('rows')) {
            // Prepare and perform validation for rows
            $validatedRows = $request->validate([
                'rows.*.row' => [
                    'required',
                    'regex:/^[A-Z]$/',
                    'distinct:rows.*.row'
                ],
                'rows.*.seat' => 'required|integer|min:1'
            ]);

            foreach ($validatedRows as $row) {
                for ($i = 1; $i <= $row['seat']; $i++)
                    $theater->Seat()->create([
                        'row' => $row['row'],
                        'seat_number' => $i
                    ]);
            }
        }

        return redirect()->route('theaters.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Theater $theater)
    {
        $rows = $theater->Seat()
            ->select("row", DB::raw('COUNT(*) as seat_count'))
            ->groupBy("row")
            ->get();

        return view('theaters.show', compact('theater', 'rows'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theater $theater)
    {
        $rows = $theater->Seat()
            ->select("row", DB::raw('COUNT(*) as seat_count'))
            ->groupBy("row")
            ->get();

        return view('theaters.edit', compact('theater', 'rows'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Theater $theater)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $theater->update($validated);

        if ($request->has('rows')) {
            // Prepare and perform validation for rows
            $validatedRows = $request->validate([
                'rows.*.row' => [
                    'required',
                    'regex:/^[A-Z]$/',
                    'distinct:rows.*.row'
                ],
                'rows.*.seat' => 'required|integer|min:1'
            ]);

            foreach ($validatedRows['rows'] as $index => $row) {
                // Check the existing number of seats for this row
                $currentSeats = $theater->Seat()->where('row', $row['row'])->get();
                $currentSeatCount = $currentSeats->count();

                // Compare and adjust seat numbers
                if ($currentSeatCount < $row['seat']) {
                    // Add additional seats if the current count is less than the desired count
                    for ($i = $currentSeatCount + 1; $i <= $row['seat']; $i++) {
                        $theater->Seat()->create([
                            'row' => $row['row'],
                            'seat_number' => $i
                        ]);
                    }
                } else if ($currentSeatCount > $row['seat']) {
                    // Remove excess seats if the current count is greater than the desired count
                    $theater->Seat()->where('row', $row['row'])
                        ->where('seat_number', '>', $row['seat'])
                        ->delete();
                }
                // If they are equal, do nothing
            }
        }
        return redirect()->route('theaters.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theater $theater)
    {
        $theater->delete();
        return redirect()->route('theaters.index');
    }
}
