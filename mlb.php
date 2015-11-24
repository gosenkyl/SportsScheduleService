<?php

include_once 'TeamHolder.php';

$teamHolder = TeamHolder::getInstance();
$teams =  $teamHolder->getMLBTeams();

echo json_encode($teams);