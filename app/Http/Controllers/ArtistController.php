<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ArtistRequest;
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
            $response = Http::api()->get("artist/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Couldn\'t retrieve artist data.';
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Error: $message");
            }

            $body = $response->body();
            if (strpos($body, '<{') === 0) {
                $body = substr($body, 1);
            }
            
            $data = json_decode($body, true);
            $entity = $data['artist'] ?? $data;

            if (!$entity) {
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Couldn't get artist data.");
            }

            return view('artists.show', ['artist' => (object)$entity]);

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.index')
                ->with('error', "Couldn't get artist data: " . $e->getMessage());
        }
    }

    public function showAlbums($id)
    {
         try {
            $response = Http::api()->get("artist/$id/albums");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Couldn\'t retrieve artist data.';
                return redirect()
                    ->route('artists.show')
                    ->with('error', "Error: $message");
            }

            $body = $response->body();
            if (strpos($body, '<{') === 0) {
                $body = substr($body, 1);
            }
            
            $data = json_decode($body, true);
            $albums = $data['albums'] ?? $data;

            if (!$albums) {
                return redirect()
                    ->route('artists.show')
                    ->with('error', "Couldn't get artist's albums.");
            }

            return view('artists.albums', ['albums' => (object)$albums]);

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.show')
                ->with('error', "Couldn't get artist data: " . $e->getMessage());
        }
    } 

    public function showSongs($artist_id,$id)
    {
         try {
            $response = Http::api()->get("/artist/$artist_id/album/$id/songs");

            /*echo "Status: " . $response->status() . "<br>";
            echo "Body: " . htmlspecialchars(substr($response->body(), 0, 1000)) . "<br>";
            die;*/

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Couldn\'t retrieve album\'s songs.';
                return redirect()
                    ->route('artists.albums')
                    ->with('error', "Error: $message");
            }

            $body = $response->body();
            if (strpos($body, '<{') === 0) {
                $body = substr($body, 1);
            }
            
            $data = json_decode($body, true);
            $entity = $data['songs'] ?? $data;

            if (!$entity) {
                return redirect()
                    ->route('artists.albums')
                    ->with('error', "Couldn't get album's songs.");
            }

            return view('artists.songs', ['songs' => (object)$entity]);

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.albums')
                ->with('error', "Couldn't get album's songs: " . $e->getMessage());
        }
    } 

    public function create()
    {
        return view('artists.create');
    }

    public function store(ArtistRequest $request)
    {
        $name = $request->get('name');
        $nationality = $request->get('nationality');
        $image = $request->get('image');
        $description = $request->get('description');
        $is_band = $request->get('is_band');

        try {
            $response = Http::api()
                ->post('artist', [
                    'name' => $name,
                    'nationality' => $nationality,
                    'image' => $image,
                    'description' => $description,
                    'is_band' => $is_band
                ]);

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
            $response = Http::api()->get("artist/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Couldn\'t retrieve artist data.';
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Error: $message");
            } 
            
            $body = $response->body();
            if (strpos($body, '<{') === 0) {
                $body = substr($body, 1);
            }
            
            $data = json_decode($body, true);
            $entity = $data['artist'] ?? $data;

            if(!$entity){
                return redirect()
                    ->route('artists.index')
                    ->with('error', "Couldn't get artist data.");
            }

            return view('artists.edit', ['artist' => $entity]);
        } catch(\Exception $e){
            return redirect()
                ->route('artists.index')
                ->with('error', "Couldn't get artist data: " . $e->getMessage());
        }
    }
    
    public function update(ArtistRequest $request, $id)
    {
        $name = $request->get('name');
        $nationality = $request->get('nationality');
        $image = $request->get('image');
        $description = $request->get('description');

        try {
            $response = Http::api()
                ->withToken($this->token)
                ->patch("artist/$id", [
                    'name' => $name,
                    'nationality' => $nationality,
                    'image' => $image,
                    'description' => $description
                ]);

            if ($response->successful()) {
                return redirect()
                    ->route('artists.show', ['id' => $id])
                    ->with('success', "$name successfully updated!");
            }

            $errorMessage = $response->json('message') ?? 'Unknown error.';
            return redirect()
                ->route('artists.show', ['id' => $id])
                ->with('error', "Error: $errorMessage");

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.show', ['id' => $id])
                ->with('error', "Couldn't update: " . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $response = Http::api()
                ->delete("artist/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Couldn\'t delete artist.';
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