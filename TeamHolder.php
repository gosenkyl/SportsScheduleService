<?php

include_once 'DBUtil.php';
include_once 'Team.php';
include_once 'Opponent.php';

include_once 'DBConnect.php';

class TeamHolder {

    private static $instance;

    private $nflTeams;
    private $mlbTeams;

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct(){
        $dbConnect = DBConnect::getInstance();
        $db = $dbConnect->getDB();

        $this->setNFLTeams($db);
    }

    public function setNFLTeams($db){
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
        $teams = $dbUtil->getTeamsArray($db, 'NFL');

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
            //echo '<b>WEEK: '.$weekInfo['w'].' YEAR: '.$weekInfo['y'].'</b><br/><br/>';

            // Loop through each game of this week
            foreach($week->children() as $game){
                $gameInfo = $game->attributes();
                //echo $gameInfo['h'].' vs '.$gameInfo['v'].'<br/>';

                $homeAbbr = $gameInfo['h']->__toString();
                $awayAbbr = $gameInfo['v']->__toString();

                // Format - YYYYMMDDGG, GG = Game Number that DAY
                $eid = $gameInfo['eid']->__toString();

                $gameDate = substr($eid, 0, 8);
                //$gameDay = $gameInfo['d']->__toString();
                $gameTime = $gameInfo['t']->__toString();

                $home = $teams[$homeAbbr];
                $away = $teams[$awayAbbr];

                $homeSchedule = $home->scheduleList;
                $awaySchedule = $away->scheduleList;

                $homeOpponent = new Opponent($eid, $gameDate, $gameTime, $i, $away->city, $away->mascot, $away->logoURL);
                $awayOpponent = new Opponent($eid, $gameDate, $gameTime, $i, $home->city, $home->mascot, $home->logoURL);

                array_push($teams[$homeAbbr]->scheduleList, $homeOpponent);
                array_push($teams[$awayAbbr]->scheduleList, $awayOpponent);
            }

            foreach($teams as $team){
                if(count($team->scheduleList) < $i){
                    $bye = new Opponent('BYE', 'BYE', 'BYE', $i, 'BYE', 'BYE', 'BYE');
                    array_push($team->scheduleList, $bye);
                }
            }

            //echo '<br/>';

            $i++;
        }

        $this->nflTeams = $teams;
    }


    public function getNFLTeams(){
        return $this->nflTeams;
    }

}