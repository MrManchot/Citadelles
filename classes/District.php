<?php

class District
{

    public $color;
    public $cost;
    public $name;

    public function __construct($district)
    {
        $this->color = (string)$district->color;
        $this->cost = (int)$district->cost;
        $this->name = (string)$district->name;
    }

    public function __toString()
    {
        return $this->name.' : '.$this->color.' - '.$this->cost.' gold';
    }

}