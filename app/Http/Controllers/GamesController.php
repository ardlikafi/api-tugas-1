<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GamesController extends Controller
{
    public function index()
    {
        $url_api = 'https://www.freetogame.com/api/games';
        $client = new Client();
        $response = $client->request('GET', $url_api);
        $filtered_games = json_decode($response->getBody()->getContents(), true);

        return view('index', compact('filtered_games'));
    }
}