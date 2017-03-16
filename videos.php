<?php

session_start ();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include ("php/config.php");
include ("php/DataService.php");
include ("php/require_login.php");
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
<title>Big Data Ed | Videos</title>
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
<!-- Cool domain button icons -->
<link href="css/icons.css" rel="stylesheet">
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
			<div class="right_col" role="main" ng-app="app"
				ng-controller="controller">
				<div class="page-title">
					<div class="title_left">
						<h3>Video Tutorials</h3>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-6 col-sm-12" ng-repeat="video in videos"
						ng-hide="video.teacherOnly == true && role != 2">
						<div class="x_panel">
							<div class="x_title">
								<h2>{{video.title}}</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<iframe width="560" height="315" style="max-width: 100%"
									ng-src="{{video.embedLink}}" frameborder="0" allowfullscreen></iframe>
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
	<!-- Custom Theme Scripts -->
	<script src="js/custom.js"></script>
	
	<script src="js/avatars.js"></script>

	<!---------------------------------------->
	<!---------APPLICATION CODE--------------->
	<!---------------------------------------->
	<script src="js/domainBuilder.js"></script>
	<script>
var app = angular.module("app", [] );
app.controller("controller", function($scope, $sce){
	$scope.role = '<?=$_SESSION['role']?>';
	
	var registerAndCreateClasses = new Video("How to register and create a class or classes", "https://www.youtube.com/embed/eiBpmRjaEVk");
	registerAndCreateClasses.teacherOnly = true;
	
	$scope.videos = [
		registerAndCreateClasses,
		new Video("How to navigate article", "https://www.youtube.com/embed/XYJY2F8ixu0"),
		new Video("How to extract data", "https://www.youtube.com/embed/kZ6IeI8Gtrg"),
		new Video("How to register and access assignments", "https://www.youtube.com/embed/uU2RonGVcRw")
	]
	
	function Video(title, embedLink){
		this.title = title;
		this.embedLink = $sce.trustAsResourceUrl(embedLink);;
	}
});
</script>
<?php include_once("js/analyticstracking.php")?>
</body>
</html>
