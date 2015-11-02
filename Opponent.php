<?php

class Opponent {

    public function __construct($day, $time, $city, $mascot){
        $this->day = $day;
        $this->time = $time;
        $this->city = $city;
        $this->mascot = $mascot;
    }

    public $day;
    public $time;
    public $city;
    public $mascot;

}