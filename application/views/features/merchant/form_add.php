<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
    <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
    <link rel="stylesheet" href="https://npmcdn.com/leaflet@1.0.0-rc.2/dist/leaflet.css" />
    <script src="https://npmcdn.com/leaflet@1.0.0-rc.2/dist/leaflet.js"></script>


    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
    <?php $this->load->view("layout/head.php") ?>
</head>
<body id="page-top">

<?php $this->load->view("layout/navbar.php") ?>

<div id="wrapper">

    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Add Data Store</h3>
        </div>
    </div>

    <div id="content-wrapper">

        <div class="container-fluid">

            <div class="row">
                <div class="col-md-8" >
                   <div id="map" style="width: 100%; height: 400px;"></div>
                </div>
                <div class="col-md-4">

                    <form action="<?php echo site_url('merchant/store'); ?>" method="post">
                        <label>Name :</label>
                        <input class="form-control" name="merchant_name" placeholder="Name. . .">
                        <br>
                        <label>Lat :</label>
                        <input class="form-control" type="text" readonly id="lat" name="lat" placeholder="Lattitude. . .">

                        <br>
                        <label>Long :</label>
                        <input class="form-control"  type="text"   readonly  id="lng" name="lng" placeholder="Longitude. . .">

                        <br>
                        <label>Radius (m) :</label>
                        <input type="range" id="radius" name="radius" min="100" max="10000" value="0"
                               oninput="amount.value=radius.value">
                        <output id="amount" name="amount" for="rangeInput">100</output>

                        <input type="submit" value="Add" class="btn btn-success pull-right">



                    </form>
            </div>
        </div>
        <!-- /#wrapper -->

        <?php $this->load->view("layout/script.php") ?>

</body>
</html>
</html>

<script>
    var markers = [];
    var marker;

    var map = L.map('map').setView({lon: 106.819282, lat: -6.210665}, 18);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    L.control.scale().addTo(map);

    // map.fitBounds([[0,-180],[0,180]]);

    map.on('click', function(evt) {
        if (markers.length > 0) {
            map.removeLayer(markers.pop());
        }

        var pointLat = evt.latlng['lat'];
        var pointLng = evt.latlng['lng'];
        document.getElementById("lat").value = pointLat;
        document.getElementById("lng").value = pointLng;
        marker =  L.marker({lon:pointLng, lat:  pointLat}).addTo(map);
        markers.push(marker);
    });




    </script>