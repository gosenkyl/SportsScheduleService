<?php

include_once 'DBUtil.php';
include_once 'Team.php';
include_once 'Opponent.php';

include_once 'DBConnect.php';

// Current NFL Week
$CUR_GAME_WEEK = 'http://www.nfl.com/liveupdate/scorestrip/ss.xml';

$xml = simplexml_load_file($CUR_GAME_WEEK) or die('No Current Week Found!');
$cur_week = $xml->gms[0]->attributes();
$CUR_WEEK = $cur_week['w'];
$CUR_SEASON = $cur_week['y'];

// Pull given week
$GAME_WEEK = 'http://www.nfl.com/ajax/scorestrip?season=[season]&seasonType=[seasonType]&week=[week]';
$GAME_WEEK = str_replace('[season]', $CUR_SEASON, $GAME_WEEK);
$GAME_WEEK = str_replace('[seasonType]', 'REG', $GAME_WEEK);

$dbUtil = new DBUtil();
$teams = $dbUtil->getNFLTeamArray($db);

// Loop through each week
$i = 1;
while(true) {
    $week = str_replace('[week]', $i, $GAME_WEEK);

    $xml = simplexml_load_file($week);

    if($xml->count() === 0){
        break;
    }

    $week = $xml->gms[0];
    $weekInfo = $week->attributes();
    echo '<b>WEEK: '.$weekInfo['w'].' YEAR: '.$weekInfo['y'].'</b><br/><br/>';

    // Loop through each game of this week
    foreach($week->children() as $game){
        $gameInfo = $game->attributes();
        echo $gameInfo['h'].' vs '.$gameInfo['v'].'<br/>';

        $homeAbbr = $gameInfo['h']->__toString();
        $awayAbbr = $gameInfo['v']->__toString();

        $gameDay = $gameInfo['d']->__toString();
        $gameTime = $gameInfo['t']->__toString();

        $home = $teams[$homeAbbr];
        $away = $teams[$awayAbbr];

        $homeSchedule = $home->scheduleList;
        $awaySchedule = $away->scheduleList;

        $homeOpponent = new Opponent($gameDay, $gameTime, $away->city, $away->mascot);
        $awayOpponent = new Opponent($gameDay, $gameTime, $home->city, $home->mascot);

        array_push($teams[$homeAbbr]->scheduleList, $homeOpponent);
        array_push($teams[$awayAbbr]->scheduleList, $awayOpponent);
    }

    foreach($teams as $team){
        if(count($team->scheduleList) < $i){
            $bye = new Opponent('BYE', 'BYE', 'BYE', 'BYE');
            array_push($team->scheduleList, $bye);
        }
    }

    echo '<br/>';

    $i++;
}

$test = 'test';