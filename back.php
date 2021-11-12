<?php
    header('Content-Type: text/json');

    if(!empty($_GET['timestamp'])){
        $time1 = strtotime($_GET['timestamp']);

        $begin = strtotime("-1 hour", $time1);
        $end = strtotime("+1 hour", $time1);
        $time = $begin;
        $i = 1;
        $data = array();
        while( $begin <= $end){

            $time = strtotime("+10 minutes", $begin);

            $response = file_get_contents('https://api.wheretheiss.at/v1/satellites/25544/positions?timestamps='.$begin.','.$time.'&units=miles');
            $response = json_decode($response);

            $response2 = file_get_contents('https://api.wheretheiss.at/v1/coordinates/'.$response[0]->latitude.','.$response[0]->longitude);
            $response2 = json_decode($response2);

            $arr[] = array('time' => $begin, 'code' => $response2->country_code, 'lat' => $response[0]->latitude , 'lng' => $response[0]->longitude , 'country' => Locale::getDisplayRegion('-'.$response2->country_code, 'en'));
            
            $begin = $time;
            $i++;
            
        }

        $json_data = json_encode($arr);
        header('Location: front.php?data='.urlencode($json_data));
        exit;

}

?>