<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ArtistRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArtistExport;
use Illuminate\Support\Str;

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
                ->withToken(session('api_token'))
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
                ->withToken(session('api_token'))
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
                ->withToken(session('api_token'))
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

    public function exportAlbumsSongs($id, $format = 'pdf')
    {
        try {
            // Get artist info
            $artistResponse = Http::api()->get("artist/$id");
            if ($artistResponse->failed()) {
                return redirect()
                    ->route('artists.show', ['id' => $id])
                    ->with('error', "Couldn't retrieve artist data.");
            }

            $artistBody = $artistResponse->body();
            if (strpos($artistBody, '<{') === 0) {
                $artistBody = substr($artistBody, 1);
            }
            $artistData = json_decode($artistBody, true);
            $artist = $artistData['artist'] ?? $artistData;

            // Get albums
            $albumsResponse = Http::api()->get("artist/$id/albums");
            if ($albumsResponse->failed()) {
                return redirect()
                    ->route('artists.show', ['id' => $id])
                    ->with('error', "Couldn't retrieve albums.");
            }

            $albumsBody = $albumsResponse->body();
            if (strpos($albumsBody, '<{') === 0) {
                $albumsBody = substr($albumsBody, 1);
            }
            $albumsData = json_decode($albumsBody, true);
            $albums = $albumsData['albums'] ?? $albumsData;

            // Get songs for each album
            $albumsWithSongs = [];
            foreach ($albums as $album) {
                $songsResponse = Http::api()->get("/artist/$id/album/{$album['id']}/songs");
                if ($songsResponse->successful()) {
                    $songsBody = $songsResponse->body();
                    if (strpos($songsBody, '<{') === 0) {
                        $songsBody = substr($songsBody, 1);
                    }
                    $songsData = json_decode($songsBody, true);
                    $album['songs'] = $songsData['songs'] ?? [];
                } else {
                    $album['songs'] = [];
                }
                $albumsWithSongs[] = $album;
            }

            if ($format === 'pdf') {
                return $this->generatePDF($artist, $albumsWithSongs);
            } else {
                return $this->generateCSV($artist, $albumsWithSongs);
            }

        } catch (\Exception $e) {
            return redirect()
                ->route('artists.show', ['id' => $id])
                ->with('error', "Couldn't export data: " . $e->getMessage());
        }
    }

    private function generatePDF($artist, $albums)
    {
        $html = view('exports.albums-songs-pdf', [
            'artist' => $artist,
            'albums' => $albums
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $filename = Str::slug($artist['name'] ?? 'artist') . '_albums_songs_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    private function generateCSV($artist, $albums)
    {
        $filename = Str::slug($artist['name'] ?? 'artist') . '_albums_songs_' . date('Y-m-d') . '.csv';
        
        return response()->streamDownload(function () use ($artist, $albums) {
            $output = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($output, ['Artist: ' . ($artist['name'] ?? 'Unknown')]);
            fputcsv($output, ['Nationality: ' . ($artist['nationality'] ?? 'N/A')]);
            fputcsv($output, []);
            
            fputcsv($output, ['Album Name', 'Song Name']);
            fputcsv($output, []);
            
            // Add album and song data
            foreach ($albums as $album) {
                $albumName = $album['name'] ?? 'Unknown Album';
                if (!empty($album['songs'])) {
                    foreach ($album['songs'] as $song) {
                        fputcsv($output, [$albumName, $song['name'] ?? 'Unknown Song']);
                    }
                } else {
                    fputcsv($output, [$albumName, '']);
                }
            }
            
            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}