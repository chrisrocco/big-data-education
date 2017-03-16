<?php
session_start();
include("php/config.php");
include("php/DataService.php");
include("php/require_login.php");
$authorized_emails = [
    'pcapers@uab.edu',
    'chris.rocco7@gmail.com',
    'awbrown@uab.edu'
];
if(!in_array($_SESSION['email'], $authorized_emails)){
    header('location: index.php');
}


$connection = mysqli_connect("localhost", "root", "", "BigDataEd");

$sql_accounts = $connection->query("SELECT * FROM accounts");
$accounts = [];
while($row = mysqli_fetch_assoc($sql_accounts)){
    $accounts[] = $row;
}

$sql_paperpool = $connection->query("SELECT * FROM paper_pool");
$paperpool = [];
while($row = mysqli_fetch_assoc($sql_paperpool)){
    $paperpool[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/images/icons/favicon.ico"
          type="image/x-icon" />
    <title>Big Data Ed | Dashboard</title>
    <!---------------------------------------->
    <!-------------FRAMEWORKS----------------->
    <!---------------------------------------->
    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <!-- Font Awesome -->
    <link
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
        crossorigin="anonymous">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="css/custom.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Angular JS -->
    <script
        src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
    <!-- Datatables -->
    <link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
</head>
<!---------------------------------------------------------------->
<!------------------------HTML BODY------------------------------->
<!---------------------------------------------------------------->
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <!-- Header Content -->
        <?php include_once 'partials/_sidebar.php';?>
        <!-- /Header Content -->

        <!-- top navigation -->
        <?php include_once('partials/_topNav.php'); ?>
        <!-- /top navigation -->

        <!-- /Header Content -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="page-title">
                <div class="title_left">
                    <h3>Admin Dashboard</h3>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Users</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <p>A list of currently registered accounts</p>

                            <!-- start student list -->
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($accounts as $account){ ?>
                                    <tr>
                                        <td><?=$account['name']?></td>
                                        <td><?=$account['email']?></td>
                                        <?php if($account['roleID'] == 1): ?>
                                            <td>Student</td>
                                        <?php elseif($account['roleID'] == 2): ?>
                                            <td>Teacher</td>
                                        <?php endif; ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!-- end student list -->

                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Paper Pool</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <p>Shows the paper pool</p>

                            <!-- start student list -->
                            <table id="papers-datatable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>PMC ID</th>
                                    <th>Title</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($paperpool as $paper){ ?>
                                    <tr>
                                        <td><?=$paper['pmc_ID']?></td>
                                        <td><?=$paper['title']?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!-- end student list -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>

<!---------------------------------------------------------------->
<!-----------------------------JAVASCRIPT------------------------->
<!---------------------------------------------------------------->

<!---------------------------------------->
<!-------------FRAMEWORKS----------------->
<!---------------------------------------->
<!-- JQuery Redirect -->
<script src="vendors/jquery-redirect/jquery.redirect.js"></script>
<!-- Bootstrap -->
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="vendors/nprogress/nprogress.js"></script>
<!-- bootstrap-progressbar -->
<script
    src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<script src="js/avatars.js"></script>
<!-- Datatables -->
<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="vendors/jszip/dist/jszip.min.js"></script>
<script src="vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="vendors/pdfmake/build/vfs_fonts.js"></script>

<!-- Custom Theme Scripts -->
<script src="js/custom.js"></script>
<script>
    $("#datatable").DataTable();
    $("#papers-datatable").DataTable();
</script>
<?php include_once("js/analyticstracking.php")?>
</body>
<?php include 'partials/_avatarPopUp.htm';?>
</html>
