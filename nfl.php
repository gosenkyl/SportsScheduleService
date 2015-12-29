<?php

include_once 'TeamHolder.php';

$teamHolder = TeamHolder::getInstance();
$teams =  $teamHolder->getNFLTeams();

header('Access-Control-Allow-Origin: *');
header('content-type: application/json; charset=utf-8');

echo json_encode($teams);