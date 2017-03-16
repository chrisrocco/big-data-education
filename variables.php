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
<title>Big Data Ed | Variables</title>
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
						<h3>Variable Reference Guide</h3>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12">
						<div class="x_panel">
							<div class="x_title">
								Search: <input ng-model="searchText">
								<button ng-click="collapseAll()">Toggle Collapse</button>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div class="panel panel-default"
									ng-repeat="domain in domains | filter:searchText">
									<div class="panel-heading">
										<h4 class="panel-title">
											<i class="fa fa-{{domain.meta.faicon}}"></i> &nbsp;&nbsp; <a
												data-toggle="collapse" data-parent="#accordion1"
												href="#{{domain.meta.name}}"> <strong>{{domain.meta.label}}</strong>
												- {{domain.tooltip}}
											</a>
										</h4>
									</div>
									<div id="{{domain.meta.name}}"
										class="panel-collapse collapse in">
										<div class="panel-body">
											<!--<ng-include ng-src="'partials/_fieldTable.htm'"></ng-include>-->
											<table class="table table-bordered">
												<thead>
													<tr>
														<th></th>
														<th>Field</th>
														<th>Description</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="field in domain.fields">
														<th scope="row" style="font-size: large"><i
															class="fa fa-{{domain.meta.faicon}}"></i></th>
														<td class="ng-binding"
															style="font-weight: bold; font-size: large; text-align: center; vertical-align: center;">{{field.label}}</td>
														<td class="ng-binding" style="font-size: 16px;">{{field.tooltip}}</td>
													</tr>
												</tbody>
											</table>
											<div class="panel-group" id="accordion2"
												ng-repeat="subdomain in domain.subDomains">
												<div class="panel panel-default">
													<div class="panel-heading">
														<h4 class="panel-title">
															<a data-toggle="collapse" data-parent="#accordion2"
																href="#collapseThreeOne">{{subdomain.meta.label}} </a>
														</h4>
													</div>
													<div id="collapseThreeOne" class="panel-collapse collapse ">
														<table class="table table-bordered">
															<thead>
																<tr>
																	<th></th>
																	<th>Field</th>
																	<th>Description</th>
																</tr>
															</thead>
															<tbody>
																<tr ng-repeat="field in subdomain.fields">
																	<th scope="row" style="font-size: large"><i
																		class="fa fa-{{domain.meta.faicon}}"></i></th>
																	<td class="ng-binding"
																		style="font-weight: bold; font-size: large; text-align: center; vertical-align: center;">{{field.label}}</td>
																	<td class="ng-binding" style="font-size: 16px;">{{field.tooltip}}</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
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
app.controller("controller", function($scope){
	$scope.randomColor = function(){
		 var letters = '0123456789ABCDEF';
		var color = '#';
		for (var i = 0; i < 6; i++ ) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}
	
	$scope.domains = initializeExperiment();
	for(var i = 0; i < $scope.domains.length; i++){
		$scope.domains[i].color = $scope.randomColor();
	}
	
	
	$scope.collapseAll = function(){
		var selector = 'a[data-toggle="collapse"]';
		$(selector).click();
	}
});
</script>
<?php include_once("js/analyticstracking.php")?>
</body>
</html>
