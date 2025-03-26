<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 20px auto;
        }

        .game {
            display: inline-block;
            width: 250px;
            background: white;
            padding: 15px;
            margin: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Daftar Game</h1>
    <div class="container">
        @if(isset($filtered_games))
            @foreach($filtered_games as $game)
                <div class="game">
                    <img src="{{ $game['thumbnail'] }}" alt="{{ $game['title'] }}">
                    <h3>{{ $game['title'] }}</h3>
                    <p><strong>Genre:</strong> {{ $game['genre'] }}</p>
                    <p><strong>Platform:</strong> {{ $game['platform'] }}</p>
                </div>
            @endforeach
        @else
            <p>Gagal mengambil data game.</p>
        @endif
    </div>
</body>
</html>