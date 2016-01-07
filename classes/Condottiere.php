<?php

class Condottiere extends Character
{

    const COLOR = 'red';

    public function play()
    {
        parent::play();

        #  Détruit un quartier

        # Ses quartiers militaires rapportent
        $this->districtGold($this->player);

    }

}