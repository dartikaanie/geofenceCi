<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("layout/head.php") ?>
</head>
<body id="page-top">

<?php $this->load->view("layout/navbar.php") ?>

<div id="wrapper">

    <!--    --><?php //$this->load->view("layout/sidebar.php") ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Data EDC</h3>
        </div>
    </div>

    <div id="content-wrapper">

        <div class="container-fluid">
            <table class="table">
                <thead>
                <td>ID</td>
                <td>Serial Number</td>
                <td>Device Model</td>
                </thead>
                <tbody>
                <?php foreach ($data_edc as $item):?>
                    <tr>
                        <td><?php echo $item->id;?></td>
                        <td><?php echo $item->serial_number;?></td>
                        <td><?php echo $item->device_model;?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <!-- /#wrapper -->

        <?php //$this->load->view("layout/script.php") ?>

</body>
</html>
</html>