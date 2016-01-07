<?php

class Player
{

    private $name;
    public $gold;
    private $hand = array();
    public $citadel = array();
    public $character;
    public $crown = false;
    public $havePlay = false;
    public $lastPlay = false;

    public function __construct($name)
    {
        $this->name = $name;
        $this->gold = Game::START_GOLD;
        for ($i = 1; $i <= Game::START_DISTRICT; $i++) {
            $this->draw();
        }
    }


    public function chooseCharacter($characters)
    {

        $stayCharacter = '';
        foreach($characters as $character) {
            $stayCharacter .= '<span'.(!$character->canChoose ? ' style="text-decoration: line-through;"': '').'>'.$character->name.'</span> / ';
        }
        trace('<small>Disponible : '.$stayCharacter.'</small>');

        # Choix random
        while(!$this->havePlay) {
            $characterKey = rand(0, count($characters)-1);
            $this->character = $characters[$characterKey];
            if($this->character->canChoose) {
                unset($characters[$characterKey]);
                trace($this->name . ' ' . ($this->crown ? '<span style="color: orange">***</span>' : '') . ' choose ' . $this->character->name);
                $this->havePlay = true;
                $this->lastPlay = true;
            }
        }

        return array_values($characters);
    }

    public function play()
    {
        $this->bankOrDraw();

        if($this->hand[0]->cost <= $this->gold)
            $this->playDistrict(0);

        $this->character->play();
    }


    public function bankOrDraw() {
        if(!array_key_exists(0, $this->hand))
            return $this->draw();
        else
            return $this->bank();

    }


    public function playDistrict($key) {
        $district = $this->hand[$key];
        $this->gold = $this->gold - $district->cost;
        unset($this->hand[$key]);
        $this->hand = array_values($this->hand);
        $this->citadel[] = $district;
        trace($this->name . ' add '.$district);
    }

    public function bank() {
        $this->gold = $this->gold +2;
        trace($this->name . ' bank : '.$this->gold.' po');
    }


    public function draw()
    {
        $game = Game::getGame();
        if (array_key_exists(0, $game->districts)) {
            $this->hand[] = $game->districts[0];
            unset($game->districts[0]);
            $game->districts = array_values($game->districts);
            trace($this->name . ' draw');
        } else {
            die('Districts is empty');
        }
    }


}