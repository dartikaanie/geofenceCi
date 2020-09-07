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

    <?php $this->load->view("layout/sidebar.php") ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 text-dark">Data Store</h1>
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
                    <div class="col-md-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th><strong>ID</strong></th>
                                        <th><strong>NAME</strong></th>
                                        <th><strong>POTITION</strong>
                                        <th><strong>ACTION</strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data_edc as $item):?>
                                        <tr>
                                            <td><?php echo $item->merchant_id;?></td>
                                            <td><?php echo $item->merchant_name;?></td>
                                            <td><?php echo $item->lat;?> ,  <?php echo $item->lng;?></td>
                                            <td><a class="btn btn-info" href="<?php echo site_url('merchant/show/'. $item->merchant_id); ?>">detail</a> </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
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
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
</body>
</html>

