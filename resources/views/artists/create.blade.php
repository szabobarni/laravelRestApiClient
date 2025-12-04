<title>New Artist</title>
<h2>New Artist</h2>
<form action="{{ route('artists.store') }}" method="POST">
    @csrf
    @method('POST')
    <label for="name">Name: </label><input type="text" name="name" value=""><br>
    <label for="nationality">Nationality: </label><input type="text" name="nationality" value=""><br>
    <label for="image">Image: </label><input type="text" name="image" value=""><br>
    <label for="description">Description: </label><input type="text" name="description" value=""><br>
    <label for="is_band">is_band: </label><input type="text" name="is_band" value=""><br>
    <button type="submit">OK</button>
</form>
<a href="{{ route('artists.index') }}"><button type="button">Cancel</button></a>