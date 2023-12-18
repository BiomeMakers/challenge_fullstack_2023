<?php

namespace App\Http\Controllers;

use App\Models\Organism;
use App\Models\Sample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Example of controller for the Challenge
 */
class BiomeController extends Controller
{
    /**
     * Returns a list of samples
     */
    public function listSamples(){

        return Sample::query()
            ->with(['crop'])
            ->withCount('abundances')
            ->get();
    }

    /**
     * Creates a new organism
     */
    public function newOrganism(Request $request){
        // Log is configured to print to stderr
        Log::info($request->all());

        // Validate request and return error if any of the fields is null
        try {
            $request->validate([
                'genus' => ['required'],
                'species' => ['required'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Both the Genus and Species fields are required.'], 400);
        }

        // Create new instance of Organism and store it
        $organism = new Organism();
        $organism->genus = $request->input('genus');
        $organism->species = $request->input('species');
        $organism->save();

        return response()->json(['message' => 'Organism created.'], 201);
    }

    /**
     * Returns a paginated list of organisms 
     */
    public function listOrganisms(){
        return Organism::paginate(10);
    }

    /**
     * Returns the top list of organisms
     */
    public function listOrganismsTop10(){
        // Gets amount of Abundances related to Organism which is also the amount of Samples
        // it is related to
        return Organism::query()
            ->withCount('abundances as sample_count')
            ->orderByDesc('sample_count')
            ->limit(10)
            ->get();
    }
}
