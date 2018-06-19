<?php
// Author : PY
// Project : G-Esport
namespace App\Http\Controllers;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class LeagueController extends Controller{

    private $url = "https://euw1.api.riotgames.com";

    private $api_key = 'api_key=RGAPI-d20dfcab-abd7-4478-8b94-297b2246d0be';

    private $client;

    private $param;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->client = new \GuzzleHttp\Client();
    }

    /* Gets the summoner's ID with the summoner's name on parameter */
    public function getAccount($summonerName){

        
        $res = $this->client->request('GET', 
            $this->url.'/lol/summoner/v3/summoners/by-name/'
            .$summonerName.
            '?'.
            $this->api_key
        );
        $contents = $res->getBody()->getContents();
        $contents = json_decode($contents);

        $matches = $this->getMatches($contents->accountId);    
        
        return $matches;
    }

    /* Gets games of the summoner in parameter */
    public function getMatches($accountID, $limit = 5){

        $this->param = "?endIndex=";

        $res = $this->client->request('GET', 
            $this->url.'/lol/match/v3/matchlists/by-account/'.
            $accountID.
            $this->param.$limit.
            '&'.
            $this->api_key
        );
        $contents = $res->getBody()->getContents();
        $contents = json_decode($contents);

        foreach($contents->matches as $game){
            $game->champion = $this->getChampion($game->champion);
            $game->image = $this->getImage($game->champion);
        }

        return $contents;
    }

    /* Returns champion's name with id */
    public function getChampion($championId){

        $path = storage_path() . "/json/champs.json";

        $json = json_decode(file_get_contents($path), true);

        foreach($json['data'] as $champion){
                
            if($champion['key'] == $championId){
                return $champion['name'];
            }
        }
    }

    /* Dynamicly returns the link of the champion's splash */
    public function getImage($championName){

        $path = storage_path() . "/json/champs.json"; 
        $json = json_decode(file_get_contents($path), true);

        foreach($json['data'] as $champion){
                
            if($champion['id'] == str_replace(" ", "", $championName) || strtolower($champion['id']) == strtolower(str_replace("'", "", $championName))){             

                return "http://ddragon.leagueoflegends.com/cdn/8.12.1/img/champion/".$champion['image']['full'];
            }
        }
    }
}
