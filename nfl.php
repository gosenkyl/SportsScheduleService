<?php

include_once 'TeamHolder.php';

$teamHolder = TeamHolder::getInstance();
$teams =  $teamHolder->getNFLTeams();

echo json_encode($teams);