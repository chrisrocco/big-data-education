<?php
session_start ();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ("php/config.php");
include ("php/DataService.php");
include ("php/require_login.php");
// Set account ID differently if it is a teacher preview
if (isset ( $_POST ['previewing'] )) {
	$accountID = $_POST ['studentID'];
} else {
	$accountID = $_SESSION ['ID'];
}
// Set assignment ID
$assignmentID = $_POST ['assignmentID'];
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
<title>Big Data Ed | Experiment Coding</title>
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
<!-- Ion.RangeSlider -->
<link href="vendors/normalize-css/normalize.css" rel="stylesheet">
<link href="vendors/ion.rangeSlider/css/ion.rangeSlider.css"
	rel="stylesheet">
<link href="vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css"
	rel="stylesheet">
<!-- Cool domain button icons -->
<link href="css/icons.css" rel="stylesheet">
<!-- Custom Theme Style -->
<link href="css/custom.css" rel="stylesheet">
<!-- jQuery -->
<script src="vendors/jquery/dist/jquery.min.js"></script>
</head>

<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<!-- Header Content -->
			<?php include_once 'partials/_sidebar.php';?>
			<!-- /Header Content -->

			<!-- top navigation -->
		    <?php include_once('partials/_topNav.php'); ?>
		    <!-- /top navigation -->

			<!-- page content -->
			<div class="right_col" role="main" ng-app="paper-coder" ng-controller="PaperCoderController">
				<div class="page-title">
					<div class="title_left">
						<h3>Experiment Coding Form</h3>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
						<?php if(!isset($_SESSION['previewing'])){ ?>
          				<bd-panel title="'Controls'">
          					<p>Fill out the forms to the best of your ability, and mark as
								complete!</p>
							<!-- Completion Bar -->
							<div class="progress">
								<div class="progress-bar progress-bar-success"
									role="progressbar" aria-valuenow="{{calculateCompletion()}}"
									aria-valuemin="0" aria-valuemax="100"
									style="width: {{calculateCompletion()}}%">
									{{calculateCompletion()}}%</div>
							</div>
							<!-- New Study Arm Button -->
							<a class="btn btn-app" ng-click="newStudyArm()"> <span
								class="badge bg-red" ng-cloak>{{experiment.studyArms.length}}</span>
								<i class="fa fa-plus"></i> New Arm
							</a>
							<!-- Save Button -->
							<a class="btn btn-app" ng-click="save(true)"> <i
								class="fa fa-save"></i> Save
							</a> 
							<!-- Reset Button -->
							<a class="btn btn-app" ng-class="{'finished': done}"
								ng-click="flagDone()"> <i class="fa fa-check"></i> Done
							</a> <a class="btn btn-app" ng-click="resetAssignment()"> <i
								class="fa fa-trash""></i> Reset
							</a> 
          				</bd-panel>
          				<?php } ?>
          				<div class="x_panel">
							<div class="x_title">
								<h2>Variables</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
									<li><a ng-click="toggleEditMode()">
										<i class="fa fa-pencil"></i>
									</a></li>
								</ul>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<p>Fill out the forms to the best of your ability, and mark as
									complete!</p>
								<div class="container">
									<!-- domain buttons -->
									<bd-domain ng-repeat="domain in experiment.domains"
												domain="domain"
												ng-show="domain.scope === 0" ></bd-domain>
								</div>

							</div>
						</div>
						<div class="x_panel">
							<div class="x_title">
								<h2>Study Arms</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
									<li><a ng-click="toggleEditMode()">
										<i class="fa fa-pencil"></i>
									</a></li>
								</ul>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<!-- Study Arms -->
								<div ng-repeat="arm in experiment.studyArms"
									 class="domainButton col-md-2">
									<a ng-click="studyArmClicked(arm)" href=""
										class="snip1489 studyArmButton"
										ng-class="{'done': isComplete(arm)}"> <i class="fa fa-sitemap"
										ng-hide="editMode"></i> <i class="fa fa-trash"
										ng-show="editMode"></i>
									</a>
									<p ng-hide="editMode">Study Arm</p>
									<p ng-show="editMode">Delete</p>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
						<bd-panel title="'Paper'">
							<iframe src="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC<?=$_POST['pmcID']?>?report=reader"
								width='100%' height='700px'></iframe>
						</bd-panel>
					</div>
				</div>
				
				<bd-viewer></bd-viewer>
				
			</div>
			<!-- /page content -->
		</div>
		<?php include("partials/_savePopUp.htm")?>
	</div>
<!-- Angular JS -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
<!-- Bootstrap -->
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Ion.rangeslider -->
<script src="vendors/ion.rangeSlider/js/ion.rangeSlider.min.js"></script>
<!-- Ion.rangeslider Angular -->
<script src="vendors/ion.rangeSlider/js/ionic-range-slider-angular.js"></script>
<!-- PNotify -->
<script src="vendors/pnotify/dist/pnotify.js"></script>
<script src="vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="vendors/pnotify/dist/pnotify.nonblock.js"></script>
<!-- Custom Theme Scripts -->
<script src="js/custom.js"></script>
<!-- DataService -->
<script src="js/DataService.js"></script>
<!-- Avatars -->
<script src="js/avatars.js"></script>
<script>
var accountID = <?=$_SESSION['ID']?>;
var assignmentID = <?=$_POST['assignmentID']?>;
var pmcID = <?=$_POST['pmcID']?>;

var SCOPES = { "CONST":0, "STUDY":1 };
</script> 
<?php
// If this is a teacher preview, inject the student's save instead.
if ($_SESSION ['role'] == 2 && isset ( $_POST ['studentID'] ) && isset ( $_POST ['assignmentID'] ) && isset ( $_POST ['previewing'] )) {
	require ("partials/_notifyPreviewMode.html");
}
?>
<script src="js/domainBuilder.js"></script>
<script src="js/DataService.js"></script>
<script src="app/core/core.module.js"></script>
<script src="app/core/panel.directive.js"></script>
<script src="app/paperCoder/paper-coder.module.js"></script>
<script src="app/paperCoder/paper-coder.service.js"></script>
<script src="app/paperCoder/viewer.service.js"></script>
<script src="app/paperCoder/viewer.directive.js"></script>
<script src="app/paperCoder/domain.directive.js"></script>
<script src="app/paperCoder/field.directive.js"></script>
<script src="app/paperCoder/paper-coder.controller.js"></script>

<?php include_once("js/analyticstracking.php")?>
</body>
</html>