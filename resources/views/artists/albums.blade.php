<title>Albums</title>
@foreach($albums as $album)
    <div style="margin-bottom:12px;">
           <a href="{{ route('artists.songs', ['artist_id' => $album['artist_id'], 'id' => $album['id']]) }}"><h2>{{$album['name']}}</h2></a>
    </div>
@endforeach
