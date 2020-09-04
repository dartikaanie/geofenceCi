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

    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Data Merchant</h3>
        </div>
    </div>

    <!--    --><?php //$this->load->view("layout/sidebar.php") ?>

    <div id="content-wrapper">

        <div class="container-fluid">
            <table class="table">
                <thead>
                <td>ID</td>
                <td>Name</td>
                <td>Potition</td>
                </thead>
                <tbody>
                <?php foreach ($data_edc as $item):?>
                    <tr>
                        <td><?php echo $item->merchant_id;?></td>
                        <td><?php echo $item->merchant_name;?></td>
                        <td><?php echo $item->lat;?> ,  <?php echo $item->lng;?></td>
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