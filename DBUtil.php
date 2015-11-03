<?php

class DBUtil {

    public function getNFLTeamArray($db){
        $sql = "select t.*
            from sport s
            inner join team t on s.sport_id = t.SPORT_ID
            where s.SPORT_CODE = 'NFL'
            order by t.city asc";

        if($teams = $db->prepare($sql)){
            $teams->execute();
            $teamList = $teams->fetchAll(PDO::FETCH_ASSOC);

            $teamArray = array();

            foreach($teamList as $team){
                $teamBean = $this->addTeamToBean($team);
                $teamArray[$teamBean->teamAbbr] = $teamBean;
            }

            return $teamArray;
        }

        header('Location: /error.php');
    }

    protected function addTeamToBean($team){
        $teamBean = new Team();
        $teamBean->teamId = $team['TEAM_ID'];
        $teamBean->city = $team['CITY'];
        $teamBean->mascot = $team['MASCOT'];
        $teamBean->teamAbbr = $team['TEAM_ABBR'];
        $teamBean->primaryColor = $team['PRIMARY_COLOR'];
        $teamBean->secondaryColor = $team['SECONDARY_COLOR'];
        $teamBean->logoURL = $team['LOGO_URL'];
        $teamBean->lonLocation = $team['LON_LOCATION'];
        $teamBean->latLocation = $team['LAT_LOCATION'];

        $teamBean->scheduleList = array();
        return $teamBean;
    }

}