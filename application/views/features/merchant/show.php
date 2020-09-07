<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
    <?php $this->load->view("layout/head.php") ?>
</head>
<body id="page-top">

<?php $this->load->view("layout/navbar.php") ?>

<div id="wrapper">

    <div class="panel panel-default">
        <div class="panel-body">
            <h3><?php echo strtoupper($data_merchant[0]->merchant_name) ?></h3>
        </div>
    </div>
    <div id="content-wrapper">

        <div class="container-fluid">
            <div id="map" style="width: 800px; height: 500px;"></div>
        </div>
        <?php $this->load->view("layout/script.php") ?>

</body>
</html>

<script>
    markers=[];
    var lat = <?php echo $data_merchant[0]->lat?>;
    var lng = <?php echo $data_merchant[0]->lng?>;
    var radius =<?php echo $data_merchant[0]->radius?>;
    var map = L.map('map').setView({lon: <?php echo $data_merchant[0]->lng?>, lat: <?php echo $data_merchant[0]->lat?>}, 18);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    L.control.scale().addTo(map);
    L.marker({lon:<?php echo $data_merchant[0]->lng?>, lat:  <?php echo $data_merchant[0]->lat?>}).bindPopup('<?php echo $data_merchant[0]->merchant_name ?>').addTo(map).openPopup();;
    L.circle({lon:<?php echo $data_merchant[0]->lng?>, lat:  <?php echo $data_merchant[0]->lat?>}, radius, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5
    }).addTo(map);

   initMap();

    function initMap() {
        getEdc();

        var iconIn = L.icon({
            iconUrl: '<?php echo base_url('img/marker_edc_in.png') ?>',
            iconSize:     [30, 45], // size of the icon
            shadowSize:   [50, 64], // size of the shadow
            iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
            shadowAnchor: [4, 62],  // the same for the shadow
            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        });

        var iconOut = L.icon({
            iconUrl: '<?php echo base_url('img/marker_edc_out.png') ?>',
            iconSize:     [50, 55], // size of the icon
            shadowSize:   [50, 64], // size of the shadow
            iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
            shadowAnchor: [4, 62],  // the same for the shadow
            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        });


        setTimeout(function() {

            if(markers.length > 1){

                for ( var i=0; i <= markers.length; i++ )
                {
                    let distance = getDistance(lat,lng, markers[0][i].lat,  markers[0][i].lng);
                    if(distance<radius){
                        L.marker([markers[0][i].lat, markers[0][i].lng], {icon: iconIn})
                            .bindPopup(markers[0][i].serial_number)
                            .openPopup().addTo(map);
                    }else{
                        L.marker([markers[0][i].lat, markers[0][i].lng], {icon: iconOut})
                            .bindPopup(markers[0][i].serial_number)
                            .openPopup().addTo(map);
                    }




                }
            }
        }, 2000);


    }


    function getEdc(){
        $.ajax({
            url: '<?php echo $data_merchant[0]->merchant_id ?>/getEdc',
            data: "",
            dataType: 'json',
            success: function (rows) {
                    markers.push(rows);

            }
        });
    }

    function getDistance(lat1, lon1, lat2, lon2) {
        pi80 = Math.PI / 180;
        lat1 *= pi80;
        lon1 *= pi80;
        lat2 *= pi80;
        lon2 *= pi80;
        r = 6372.797; // mean radius of Earth in km
        dlat = lat2 - lat1;
        dlon = lon2 - lon1;
        a = Math.sin(dlat / 2) * Math.sin(dlat / 2) + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dlon / 2) * Math.sin(dlon / 2);
        c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        m = r * c *1000;
        return m;
    }
</script>