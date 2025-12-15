<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $artist['name'] ?? 'Artist' }} - Albums and Songs</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        @page {
            margin: 20px;
            margin-bottom: 100px;
        }
        .page-header {
            background: linear-gradient(135deg, #FF2D20 0%, #FF6B6B 100%);
            color: white;
            padding: 20px 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 3px solid #FF2D20;
            margin: -20px -20px 20px -20px;
            width: calc(100% + 40px);
        }
        .page-footer {
            background-color: #f8f9fa;
            border-top: 2px solid #FF2D20;
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9pt;
            color: #666;
            margin: 20px -20px -20px -20px;
            width: calc(100% + 40px);
        }
        .footer-left {
            flex: 1;
        }
        .footer-right {
            text-align: right;
            font-weight: bold;
            color: #FF2D20;
        }
        .logo-container {
            flex-shrink: 0;
            background: white;
            padding: 5px;
            border-radius: 4px;
        }
        .logo {
            width: 40px;
            height: 40px;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .header-content {
            flex: 1;
        }
        .header-content h2 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            color: black;
        }
        .header-content p {
            margin: 3px 0 0 0;
            font-size: 9pt;
            color: black;
            opacity: 0.8;
        }
        .main-content {
            padding: 20px 0;
            margin-top: 0px;
            position: relative;
            z-index: 10;
        }
        .artist-header {
            border-bottom: 3px solid #FF2D20;
            margin-bottom: 25px;
            padding-bottom: 20px;
        }
        .artist-title {
            font-size: 32pt;
            font-weight: bold;
            color: #FF2D20;
            margin: 0 0 15px 0;
        }
        .artist-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            font-size: 10pt;
        }
        .artist-info-item {
            margin: 5px 0;
        }
        .artist-info-label {
            font-weight: bold;
            color: #FF2D20;
        }
        .artist-description {
            grid-column: 1 / -1;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10pt;
        }
        .album {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .album-title {
            background-color: #fff3f3;
            padding: 12px 15px;
            font-size: 14pt;
            font-weight: bold;
            border-left: 5px solid #FF2D20;
            color: #FF2D20;
            margin-bottom: 10px;
        }
        .songs-list {
            margin-left: 20px;
        }
        .song {
            padding: 6px 0;
            border-bottom: 1px dotted #ddd;
            font-size: 10.5pt;
        }
        .song:last-child {
            border-bottom: none;
        }
        .song-name {
            margin-left: 10px;
            color: #333;
        }
        .empty-albums {
            padding: 30px;
            text-align: center;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="logo-container">
            <div class="logo">
                <img src="{{ asset('image/angled_view3.png') }}" alt="Melodex Logo">
            </div>
        </div>
        <div class="header-content">
            <h2>Artist Music Catalog</h2>
            <p>Albums & Songs Report</p>
        </div>
    </div>

    <div class="main-content">
        <div class="artist-header">
            <h1 class="artist-title">{{ $artist['name'] ?? 'Unknown Artist' }}</h1>
            <div class="artist-info">
                @if(!empty($artist['nationality']))
                    <div class="artist-info-item">
                        <span class="artist-info-label">Nationality:</span> {{ $artist['nationality'] }}
                    </div>
                @endif
                @if(!empty($artist['is_band']))
                    <div class="artist-info-item">
                        <span class="artist-info-label">Type:</span> {{ $artist['is_band'] ? 'Band' : 'Solo Artist' }}
                    </div>
                @endif
            </div>
            @if(!empty($artist['description']))
                <div class="artist-description">
                    <span class="artist-info-label">Description:</span><br>
                    {{ $artist['description'] }}
                </div>
            @endif
        </div>

        <div class="content">
            @forelse($albums as $album)
                <div class="album">
                    <div class="album-title">{{ $album['name'] ?? 'Unknown Album' }}</div>
                    <div class="songs-list">
                        @forelse($album['songs'] as $song)
                            <div class="song">
                                <span class="song-name">• {{ $song['name'] ?? 'Unknown Song' }}</span>
                            </div>
                        @empty
                            <div class="song">
                                <span class="song-name"><em>No songs available</em></span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="empty-albums">No albums found for this artist.</div>
            @endforelse
        </div>
    </div>

    <div class="page-footer">
        <div class="footer-left">
            Generated on {{ date('F j, Y \a\t H:i') }}
        </div>
        <div class="footer-right">
            Page <span class="pagenum"></span>
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script(function($pageNumber, $pageCount, $pdf) {
                $pdf->text(250, 760, "Page " . $pageNumber . " of " . $pageCount, "Helvetica", 9);
            });
        }
    </script>
</body>
</html>
</body>
</html>
