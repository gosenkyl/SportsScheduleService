<?php

class Opponent {

    public function __construct($eid, $date, $time, $week, $city, $mascot){
        $this->eid = $eid;
        $this->date = $date;
        $this->time = $time;
        $this->week = $week;
        $this->city = $city;
        $this->mascot = $mascot;
    }

    public $eid;
    public $date;
    public $time;
    public $week;
    public $city;
    public $mascot;

}