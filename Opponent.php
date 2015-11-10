<?php

class Opponent {

    public function __construct($eid, $date, $time, $week, $city, $mascot, $logoURL){
        $this->eid = $eid;
        $this->date = $date;
        $this->time = $time;
        $this->week = $week;
        $this->city = $city;
        $this->mascot = $mascot;
        $this->logoURL = $logoURL;
    }

    public $eid;
    public $date;
    public $time;
    public $week;
    public $city;
    public $mascot;
    public $logoURL;

}