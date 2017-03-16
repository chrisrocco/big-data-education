<?php
session_start ();
error_reporting ( E_ALL );
ini_set ( 'display_errors', 1 );
include ("php/config.php");
include ("php/DataService.php");
include ("php/require_login.php");
$assignments = json_decode ( $DataService->get ( "/assignments/" . $_SESSION ['ID'] ), true );
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
						<h3>Student Dashboard</h3>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>My Assignments</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<p>All current assignments show up here.</p>

								<!-- start project list -->
								<table class="table table-striped projects"
									ng-app="assignmentsTable"
									ng-controller="assignmentsTableController">
									<thead>
										<tr>
											<th style="width: 1%">#</th>
											<th style="width: 49%">Paper Name</th>
											<th style="width: 30%">Progress</th>
											<th style="width: 20%">Edit</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="assignment in assignments" >
											<td>{{assignment.pmcID}}</td>
											<td><a>{{assignment.title}}</a> <br /> <small>created:
													{{assignment.date_created}}</small></td>
											<td>
												<div ng-hide="assignment.status == 'conflict'" class="progress" >
														<div class="progress-bar progress-bar-success"
															role="progressbar"
															aria-valuenow="{{assignment.completion}}"
															aria-valuemin="0" aria-valuemax="100"
															style="width: {{assignment.completion}}%">
															{{assignment.completion}}%</div>
												</div>
												<div ng-show="assignment.status == 'conflict'">
													<span style="color: red"> <em>You have a conflict with another student</em> </span>
													<button class="btn btn-danger btn-xs pull-right"
														ng-click="loadResolution(assignment.pmcID, assignment.uniqueID)">
														<i class="fa fa-continue"></i>
														Resolve
													</button>
												</div>
											</td>
											<td><a href=""
												ng-click="loadAssignment(assignment.pmcID, assignment.uniqueID)"
												class="btn btn-primary btn-xs"><i class="fa fa-continue"></i>
													Continue </a></td>
										</tr>
									</tbody>
								</table>
								<!-- end project list -->

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

	<!-- Custom Theme Scripts -->
	<script src="js/custom.js"></script>

	<!---------------------------------------->
	<!---------APPLICATION CODE--------------->
	<!---------------------------------------->
	<script>
	var assignmentsTableApp = angular.module("assignmentsTable", []);
	assignmentsTableApp.controller("assignmentsTableController", function($scope){
		$scope.assignments = JSON.parse('<?=json_encode($assignments)?>');
		$scope.loadAssignment = function (pmcID, assignmentID){
			$.redirect('assignment.php', {'pmcID': pmcID, 'assignmentID': assignmentID, 'studentID':<?=$_SESSION['ID']?>});
		};
		$scope.loadResolution = function (pmcID, assignmentID){
			$.redirect('resolution.php', {'pmcID': pmcID, 'assignmentID': assignmentID});			
		}
	});
</script>
<?php include_once("js/analyticstracking.php")?>
</body>
    <?php include 'partials/_avatarPopUp.htm';?>
</html>
