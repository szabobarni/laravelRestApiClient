<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\ResponseHelper;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $needle = $request->get('needle');

        try{
            $url = $needle ? "artists?needle=" .urlencode($needle) : "artists";

            $response = Http::api()->get($url);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Unknown error.';
                return redirect()
                    ->route('artists.index')
                    ->with('error', "There was an error during the request: $message");
            }

            $body = $response->body();
            if (strpos($body, '<{') === 0) {
                $body = substr($body, 1);
            }
            
            $data = json_decode($body, true);
            $artists = $data['artists'] ?? [];
            $entities = collect($artists)->unique('name');

            return view('artists.index', [
                'artists' => $entities,
                'isAuthenticated' => $this->isAuthenticated()
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('artists.index')
                ->with('error', "Couldn't load the artists: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::api()->get("/artists/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Couldn\'t retrieve artist data.';
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Error: $message");
            }

            $body = $response->body();
            $entity = $body['artist'] ?? null;

            if (!$entity) {
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Couldn't get artist data.");
            }

            return view('artists.show', ['entity' => $entity]);

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.index')
                ->with('error', "Couldn't get artist data: " . $e->getMessage());
        }
    }

    public function create()
    {
        return view('artists.create');
    }

    public function store(Request $request)
    {
        $name = $request->get('name');

        try {
            $response = Http::api()
                ->withToken($this->token)
                ->post('/artists', ['name' => $name]);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Couldn\'t create artist.';
                return redirect()
                    ->route('artists.create')
                    ->with('error', "Error: $message");
            }

            return redirect()
                ->route('artists.index')
                ->with('success', '$name artist created successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.index')
                ->with('error', "Couldn't communicate with API: " . $e->getMessage());
        }
    }

    public function edit($id){
        try {
            $response = Http::api()->get("/artists/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Couldn\'t retrieve artist data.';
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Error: $message");
            } 
            
            $body = $response->json();
            $entity = $body['artist'] ?? null;

            if(!$entity){
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Couldn't get artist data.");
            }

            return view('artists.edit', ['entity' => $entity]);
        } catch(\Exception $e){
            return redirect()
                ->route('artists.index')
                ->with('error', "Couldn't get artist data: " . $e->getMessage());
        }
    }
    
    public function update(Request $request, $id)
    {
        $name = $request->get('name');

        try {
            $response = Http::api()
                ->withToken($this->token)
                ->put("/artists/$id", ['name' => $name]);

            if ($response->successful()) {
                return redirect()
                    ->route('artists.index')
                    ->with('success', "$name successfully updated!");
            }

            $errorMessage = $response->json('message') ?? 'Unknown error.';
            return redirect()
                ->route('artists.index')
                ->with('error', "Error: $errorMessage");

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.index')
                ->with('error', "Couldn't update: " . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $response = Http::api()
                ->withToken($this->token)
                ->delete("/artists/$id", ['id' => $id]);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Coudln\'t delete artist.';
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Error: $message");
            }

            $body = $response->json();
            $name = $body['name'] ?? 'Unknown';

            return redirect()
                ->route('artists.index')
                ->with('success', "$name deleted successfully!");

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.index')
                ->with('error', "Couldn't communicate with the API: " . $e->getMessage());
        }
    }
}