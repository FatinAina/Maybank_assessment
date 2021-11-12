<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>ISS</title>
        <style>
            table, td, th {
                border: 1px solid black;
            }

            table {
                border-collapse: collapse;
            }
        </style>
    </head>
    <body>
        <h3 class=title >Where was the International Space Station (ISS)?</h3>  
        <h5> Choose one past date and time to track the location of the ISS </h5>
         
        <form action="back.php">
            <input type="datetime-local" name="timestamp"/>
            <button type="submit">submit</button>
        </form></br><hr>

        <?php
            if(!empty($_GET['data'])){
                $json=json_encode($_GET['data']);               
            }
        ?> 

        <div id="container">
            <table></table> 
        </div>

        <div id="map_title"></div>
        <div id="map" style="height: 354px; width:650px;"></div>

        <div id="weather"></div>      

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBF31XUBKGYHkcxejmGQlNAh3L3wFp-n3g&callback=initMap&v=weekly"></script>
        <script type="text/javascript">

            var j=<?php echo $json; ?>;               
            var obj = JSON.parse(j);
            var res = [];
            var loc = [];
            var count = 1;
            
            for(var i in obj)
                res.push(obj[i]);

            var chosen_date = new Date(res[6].time*1000);
            var str = '<h4>Time & Location of ISS within 1 hour (before & after) '+chosen_date.getDate()+"/"+(chosen_date.getMonth()+1)+"/"+chosen_date.getFullYear()+" "+chosen_date.getHours()+":"+chosen_date.getMinutes()+":"+chosen_date.getSeconds() +'</h4>'+
                    '<table>'+
                        '<tr>'+
                            '<th>#</th><th>Time</th><th>Location</th><th>Country Code</th>'+
                        '</tr>';

            for(var i in res){
                str += '<tr>';
                var date = new Date(res[i].time*1000);

                loc.push([res[i].lat, res[i].lng]);
                if(i != 6)
                    str += '<td>' + count++ + '</td>' + '<td>' + date + '</td>' + '<td>' + res[i].lat.toFixed(4) +', ' + res[i].lng.toFixed(4) + '</td>'+ '<td>' + res[i].code + '</td></tr>';
                else
                    str += '<td><strong>' + count++ + '</strong></td>' + '<td><strong>' + date + '</strong></td>' + '<td><strong>' + res[i].lat.toFixed(4) +', ' + res[i].lng.toFixed(4) + '</strong></td>'+ '<td><strong>' + res[i].code + '</strong></td></tr>';
            } 
            
            str += '</table>';

            document.getElementById("container").innerHTML = str;

            function initMap() {
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 2,
                    center: { lat: loc[6][0], lng: loc[6][1] },
                    mapTypeId: "terrain",
                });
                const flightPlanCoordinates = [
                    { lat: loc[0][0], lng: loc[0][1] },
                    { lat: loc[1][0], lng: loc[1][1] },
                    { lat: loc[2][0], lng: loc[2][1] },
                    { lat: loc[3][0], lng: loc[3][1] },
                    { lat: loc[4][0], lng: loc[4][1] },
                    { lat: loc[5][0], lng: loc[5][1] },
                    { lat: loc[6][0], lng: loc[6][1] },
                    { lat: loc[7][0], lng: loc[7][1] },
                    { lat: loc[8][0], lng: loc[8][1] },
                    { lat: loc[9][0], lng: loc[9][1] },
                    { lat: loc[10][0], lng: loc[10][1] },
                    { lat: loc[11][0], lng: loc[11][1] },
                    { lat: loc[12][0], lng: loc[12][1] },

                ];
                const flightPath = new google.maps.Polyline({
                    path: flightPlanCoordinates,
                    geodesic: true,
                    strokeColor: "#FF0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                });

                flightPath.setMap(map);
            }
            google.maps.event.addDomListener(window, 'load', initMap);
            document.getElementById("map_title").innerHTML = "<h4>Path of ISS within above Timeframe</h4>";

            $.getJSON("http://api.openweathermap.org/data/2.5/weather?lat="+loc[6][0]+"&lon="+loc[6][1]+"&appid=177f2ba1d128d0fe7db470b9b9655ee4",function(json){
                var info=[];
                var obj = JSON.parse(JSON.stringify(json));
                for(var i in obj)
                    info.push(obj[i]);
                    
                document.getElementById("weather").innerHTML = "<h4>Weather at location ("+loc[6][0].toFixed(4)+", "+loc[6][1].toFixed(4) +")</h4>"+ 
                                                                    "<p>Weather: " + info[1][0].main + " ("+ info[1][0].description + ") <br>"+
                                                                    "Temperature: "+ info[3].temp +"K<br>"+
                                                                    "Humidity: "+ info[3].humidity + "</p>";

            });
            

        </script>     
    </body> 
</html>
