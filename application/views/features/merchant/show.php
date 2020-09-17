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
<body class="hold-transition sidebar-mini layout-fixed">

<?php $this->load->view("layout/navbar.php") ?>

<div id="wrapper">

    <?php $this->load->view("layout/sidebar.php") ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-1 pull-right">
                        <div class="btn-group">
                            <a class="btn btn-warning btn-sm" href="<?php echo site_url('merchant/edit/'. $data_merchant[0]->merchant_id); ?>"><i class="fa fa-edit"></i> </a>
                            <form action="<?php echo site_url('merchant/delete'); ?>" method="post" onclick="return confirm('Are you sure you want to delete this store?');">

                                <input name="merchant_id" value="<?php echo  $data_merchant[0]->merchant_id; ?>" hidden>
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <h1><?php echo strtoupper($data_merchant[0]->merchant_name) ?></h1>
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
                            <div class="card-body">
                                <div id="map" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" >
                        <div class="card">
                            <div class="card-body">
                                <table id="table_edc" class="table table-striped">
                                    <thead>
                                        <td>Date time</td>
                                        <td>Serial Number</td>
                                        <td>Potition</td>
                                        <td>Status</td>
                                    </thead>
                                    <tr>
                                    </tr>
                                </table>
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

        var iconOut = L.icon({
            iconUrl: '<?php echo base_url('img/marker_edc_in.png') ?>',
            iconSize:     [30, 45], // size of the icon
            shadowSize:   [50, 64], // size of the shadow
            iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
            shadowAnchor: [4, 62],  // the same for the shadow
            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        });

        var iconIn = L.icon({
            iconUrl: '<?php echo base_url('img/marker_edc_out.png') ?>',
            iconSize:     [30, 30], // size of the icon
            shadowSize:   [50, 64], // size of the shadow
            iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
            shadowAnchor: [4, 62],  // the same for the shadow
            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        });


        setTimeout(function() {
            if(markers[0].length > 0){

                for ( var i=0; i < markers[0].length; i++ )
                {
                    let status ="";
                    let distance = getDistance(lat,lng, markers[0][i].lat,  markers[0][i].lng);
                    if(distance<radius){
                        L.marker([markers[0][i].lat, markers[0][i].lng], {icon: iconIn})
                            .bindPopup(markers[0][i].serial_number)
                            .openPopup().addTo(map);
                        status ="<label class='right badge badge-success'>IN</label>"
                    }else{
                        L.marker([markers[0][i].lat, markers[0][i].lng], {icon: iconOut})
                            .bindPopup(markers[0][i].serial_number)
                            .openPopup().addTo(map);
                        status ="<label class='right badge badge-danger'>OUT</label>"
                    }

                    var newRow=document.getElementById('table_edc').insertRow();
                     newRow.innerHTML = "<td>"+ markers[0][i].datetime + "</td>" +
                         "<td>"+ markers[0][i].serial_number + "</td>" +
                         "<td>"+ markers[0][i].lat + " , "+markers[0][i].lng + "</td>" +
                         "<td>"+ status +"</td>";
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
