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
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css"
    />
    <?php $this->load->view("layout/head.php") ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

<?php $this->load->view("layout/navbar.php") ?>

<div id="wrapper">

    <?php $this->load->view("layout/sidebar.php") ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 text-dark">Edit Data Store <?php echo strtoupper($data_merchant[0]->merchant_name) ?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-2 pull-right">
                        <a class="btn btn-primary pull-left" href="<?php echo site_url('merchant/add'); ?>"> <i class="fa fa-plus"></i> Add Store</a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">

                    <div class="col-md-12" >
                        <div class="card">
                            <br>
                            <div class="card-group" style="margin-left: 15px; margin-right: 15px">
                                <input class="form-control col-md-11 pull-left"  name="address" value="" id="address" onchange="searchCoordinat()"/>
                                <button class="btn btn-default btn-sm col-md-1"  onclick="searchCoordinat()"> <i class="fa fa-search"></i></button>
                            </div>
                            <p  style="margin-left: 15px" id="results"></p>
                        </div>


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8" >
                        <div class="card">
                            <div id="map" style="width: 100%; height: 410px;"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form action="<?php echo site_url('merchant/update'); ?>" method="post">
                                    <input name="merchant_id" value="<?php echo  $data_merchant[0]->merchant_id; ?>" hidden>
                                    <label>Name :</label>
                                    <input class="form-control" name="merchant_name" value="<?php echo strtoupper($data_merchant[0]->merchant_name) ?>">
                                    <br>
                                    <label>Lat :</label>
                                    <input class="form-control" type="text" readonly id="lat" name="lat" value="<?php echo strtoupper($data_merchant[0]->lat) ?>">

                                    <br>
                                    <label>Long :</label>
                                    <input class="form-control"  type="text"   readonly  id="lng" name="lng" value="<?php echo strtoupper($data_merchant[0]->lng) ?>">

                                    <br>
                                    <label>Radius (m) :</label>
                                    <input type="range" id="radius" name="radius" min="100" class="col-md-12" max="10000" value="<?php echo strtoupper($data_merchant[0]->radius) ?>"
                                           oninput="amount.value=radius.value" onchange="setRadius(this.value)">
                                    <output id="amount" name="amount" for="rangeInput"><?php echo strtoupper($data_merchant[0]->radius) ?></output>

                                    <input type="submit" value="Update" class="btn btn-success" style="float: right; margin-left: 10px">
                                    <a class="btn btn-default"  style="float: right;" href="javascript:history.go(-1)"> cancel </a>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php $this->load->view("layout/footer.php") ?>

    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<?php $this->load->view("layout/script.php") ?>

</body>
</html>


<script>
    var markers = [];
    var marker;
    var circles =[];

    var map = L.map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    L.control.scale().addTo(map);

    getLocation();

    function chooseAddress(lat, lng) {
        map.setView({lon: lng, lat: lat}, 14);
        marker =  L.marker({lon:lng, lat:  lat}).addTo(map);
        markers.push(marker);
    }

    function searchCoordinat() {
        var input = document.getElementById("address");

        $.getJSON('http://nominatim.openstreetmap.org/search?format=json&limit=5&q=' + input.value +'&polygon_geojson=1', function(data) {
            console.log(data);
            var items = [];

            $.each(data, function(key, val) {
                lat = val.lat;
                lng = val.lon;
                items.push("<li><a href='#' onclick='chooseAddress(" + lat + ", " + lng + ")'>" + val.display_name + '</a></li>');
            });
            $('#results').empty();
            if (items.length != 0) {
                $('<p>', { html: "Search results:" }).appendTo('#results');
                $('<ul/>', {
                    'class': 'my-new-list',
                    html: items.join('')
                }).appendTo('#results');
            } else {
                $('<p>', { html: "No results found" }).appendTo('#results');
            }
        });
    }

    function getLocation() {
        map.setView({lon: <?php echo strtoupper($data_merchant[0]->lng) ?>, lat: <?php echo strtoupper($data_merchant[0]->lat) ?>}, 14);
        marker =  L.marker({lon:<?php echo strtoupper($data_merchant[0]->lng) ?>, lat:  <?php echo strtoupper($data_merchant[0]->lat) ?>}).addTo(map).bindPopup("Old Position") .openPopup();

        let circle = L.circle({lon:<?php echo $data_merchant[0]->lng?>, lat:  <?php echo $data_merchant[0]->lat?>}, <?php echo $data_merchant[0]->radius?>, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.7
        }).addTo(map);
        circles.push(circle);
    }

    function setRadius(rad){
        // if (circles.length > 1) {
        //     map.removeLayer(circles.pop());
        // }

        console.log(rad);
        let circle = L.circle({lon:document.getElementById("lat").value, lat:  document.getElementById("lng").value}, rad, {
            color: 'red',
            fillColor: '#2a27ff',
            fillOpacity: 0.5
        }).addTo(map);
        circles.push(circle);
    }

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

