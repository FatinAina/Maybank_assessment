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

            // echo $i.'. '.date('d-m-Y h:ia',$time);
            $time = strtotime("+10 minutes", $begin);

            $response = file_get_contents('https://api.wheretheiss.at/v1/satellites/25544/positions?timestamps='.$begin.','.$time.'&units=miles');
            $response = json_decode($response);

            // echo " latitude : ".$response[0]->latitude.", "."longitude: ".$response[0]->longitude;

            $response2 = file_get_contents('https://api.wheretheiss.at/v1/coordinates/'.$response[0]->latitude.','.$response[0]->longitude);
            $response2 = json_decode($response2);

            // echo "country code : ".$response2->country_code.", "." timezone : ".$response2->timezone_id.'<br />';

            $arr[] = array('time' => $begin, 'code' => $response2->country_code, 'lat' => $response[0]->latitude , 'lng' => $response[0]->longitude);
            


            $begin = $time;
            $i++;
            
        }

       

        // for($j=0;$j<count($output->results[0]->address_components);$j++){
        //     echo '<b>'.$output->results[0]->address_components[$j]->types[0].': </b>  '.$output->results[0]->address_components[$j]->long_name.'<br/>';
        // }

        $json_data = json_encode($arr);

        // header('Location: front.php?data='.urlencode($json_data));

        exit;


}

?>