<?php

class Game
{

    const START_GOLD = 2;
    const START_DISTRICT = 4;
    const MIN_PLAYER = 4;
    const MAX_PLAYER = 7;
    const XML_DIR = '/../xml/';
    const XML_DISTRICT = 'districts.xml';
    const XML_CHARACTER = 'characters.xml';

    public $districts;
    public $players;
    private $step = 'choose';
    private $characters;
    private $nbPlayer;

    private static $game;
    private static $visibeRemovedCard = array(
        4 => 2,
        5 => 1,
        6 => 0,
        7 => 0
    );

    # Singleton
    public static function getGame()
    {
        if (is_null(self::$game))
            self::$game = new Game();
        return self::$game;
    }


    public function init($nbPlayer)
    {

        $this->nbPlayer = $nbPlayer;

        if ($this->nbPlayer < 4 || $this->nbPlayer > 7)
            die('Invalid player number');

        $this->initDistricts();
        $this->initCharacters();
        $this->initPlayers($nbPlayer);

        while (!$this->isVictory())
            $this->playTurn();

        dump($this->players);

    }

    private function initDistricts()
    {
        $districtsXmlString = file_get_contents(__DIR__ . self::XML_DIR . self::XML_DISTRICT);
        $districtsXml = new SimpleXMLElement($districtsXmlString);

        for ($i = 1; $i <= 10; $i++)
            foreach ($districtsXml->district as $district)
                $this->districts[] = new District($district);

        shuffle($this->districts);
    }

    private function initCharacters()
    {
        $charactersXmlString = file_get_contents(__DIR__ . self::XML_DIR . self::XML_CHARACTER);
        $charactersXml = new SimpleXMLElement($charactersXmlString);
        foreach ($charactersXml->character as $character) {
            $className = (string)$character->name;
            $this->characters[(int)$character->turn] = new $className($character);
        }
    }

    private function initPlayers($nbPlayer)
    {
        for ($i = 1; $i <= $nbPlayer; $i++) {
            $this->players[] = new Player('Player ' . $i);
        }
        # Who start
        $randomPlayer = rand(0, $nbPlayer - 1);
        $this->players[$randomPlayer]->crown = true;
    }

    public function isVictory()
    {
        foreach($this->players as $player)
            if(count($player->citadel) > 8)
                return true;

        return false;
    }

    private function playTurn()
    {
        if ($this->step == 'choose') {
            $player = $this->nextPlayerChooseCharacter();
            if ($player instanceof Player) {

                foreach ($this->players as $playerTmp)
                    $playerTmp->lastPlay = false;

                $this->characters = $player->chooseCharacter($this->characters);
                $player->lastPlay = true;

            } else {
                $this->step = 'play';

                foreach ($this->players as $playerTmp) {
                    $playerTmp->lastPlay = false;
                    $playerTmp->havePlay = false;
                }

                $this->initCharacters();

            }
        } else {

            uasort($this->characters, function($a, $b) {
                if($a->turn > $b->turn) {
                    return 1;
                } else {
                    return -1;
                }
            });

            foreach($this->characters as $character) {
                $player = $this->whoPlay($character);
                if ($player) {
                    $player->play();
                }
            }
            $this->step = 'choose';
            trace('<hr/>');

        }
    }

    private function whoPlay($character) {
        foreach ($this->players as $player) {
            if($player->character==$character)
                return $player;
        }
        return false;
    }

    private function nextPlayerChooseCharacter()
    {
        $o = null;
        $firstPlay = true;
        $allPlay = true;
        foreach ($this->players as $k => $player) {

            if ($player->havePlay)
                $firstPlay = false;
            else
                $allPlay = false;

            if ($player->lastPlay)
                $o = $k;

        }


        if ($firstPlay) {

            # Personnage face cachée
            $this->characters = array_values($this->characters);
            $characterKey = rand(0, count($this->characters)-1);
            unset($this->characters[$characterKey]);

            # Personnages face visibles
            $nbCharacter = self::$visibeRemovedCard[$this->nbPlayer];
            while($nbCharacter > 0) {
                $this->characters = array_values($this->characters);
                $characterKey = rand(0, count($this->characters) - 1);
                $this->characters[$characterKey]->canChoose = false;
                $nbCharacter--;
            }

            foreach ($this->players as $k => $player) {
                if ($player->crown) {
                    return $this->players[$k];
                }
            }
        }


        if ($allPlay)
            return false;


        if (array_key_exists($o + 1, $this->players)) {
            return $this->players[$o + 1];
        } else {
            return $this->players[0];
        }

    }


}