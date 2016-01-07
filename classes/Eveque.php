<?php

class Eveque extends Character
{

    const COLOR = 'blue';

    public function play()
    {
        parent::play();

        # Est protégé contre le Condottiere

        # Ses quartier religieux raportent
        $this->districtGold($this->player);

    }

}