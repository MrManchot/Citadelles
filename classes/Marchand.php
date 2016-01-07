<?php

class Marchand extends Character
{

    const COLOR = 'green';

    public function play()
    {
        parent::play();

        # Gagne une pièce d'or
        $this->player->gold ++;

        # Ses quartier marchands raportent
        $this->districtGold($this->player);

    }

}