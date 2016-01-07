<?php

class Character
{

    public $name;
    public $turn;
    public $color;
    public $canChoose = true;
    public $player;

    public function __construct($character)
    {
        $this->color = (string)$character->color;
        $this->turn = (int)$character->turn;
        $this->name = (string)$character->name;
    }

    public function play()
    {
        trace($this->name . ' play');

        $game = Game::getGame();
        foreach ($game->players as $playerTmp) {
            if($playerTmp->character instanceof static)
                $this->player = $playerTmp;
        }

    }

    public function districtGold($player) {
        foreach($player->citadel as $district) {
            if($district->color == static::COLOR) {
                $player->gold ++;
                trace("Ses quartiers lui raportent");
            }
        }
    }


}