<?php

class Roi extends Character
{

    const COLOR = 'yellow';

    public function play()
    {
        parent::play();

        # Devient le premier joueur
        $game = Game::getGame();
        foreach ($game->players as $playerTmp) {
            $playerTmp->crown = false;
            if($playerTmp->character instanceof self) {
                $player = $playerTmp;
                $playerTmp->crown = true;
            }
        }

        # Ses quartiers nobles raportent
        $this->districtGold($this->player);

    }

}