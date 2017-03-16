<?php

session_start ();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include ("php/config.php");
include ("php/DataService.php");
include ("php/require_login.php");
$classRoster = json_decode ( $DataService->get ( "classes/" . $_SESSION ['ID'] . "/roster" ), true );
for($j = 0; $j < count ( $classRoster ); $j ++) {
	for($i = 0; $i < count ( $classRoster [$j] ['students'] ); $i ++) {
		$assignments = json_decode ( $DataService->get ( "assignments/" . $classRoster [$j] ['students'] [$i] ['uniqueID'] ), true );
		$classRoster [$j] ['students'] [$i] ['assignments'] = $assignments;
	}
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
<!-- Nprogress -->
<script src="vendors/nprogress/nprogress.js"></script>
<!-- jQuery -->
<script src="vendors/jquery/dist/jquery.min.js"></script>
<!-- Angular JS -->
<script
	src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>

<!-- Custom Theme Style -->
<link href="css/custom.css" rel="stylesheet">
</head>
<!---------------------------------------------------------------->
<!------------------------HTML BODY------------------------------->
<!---------------------------------------------------------------->
<body class="nav-md" ng-app="teacherDash"
	ng-controller="teacherDashController">
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
						<h3>Teacher Dashboard</h3>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-6 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>
									Class Roster</small>
								</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<p class="text-muted font-13 m-b-30">All of your students are
									listed here</p>
								<div class="btn-group">
									<p>Viewing..</p>
									<button type="button" class="btn">{{viewingClass.name}}</button>
									<button type="button" class="btn dropdown-toggle"
										data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span> <span class="sr-only">Toggle
											Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li ng-repeat="class in classRoster"
											ng-click="viewClass(class)"><a href="#">{{class.name}}</a></li>
									</ul>
								</div>
								<br />
								<table class="table table-hover">
									<thead>
										<tr>
											<th>ID</th>
											<th>Name</th>
											<th>Username</th>
										</tr>
									</thead>

									<tbody>
										<tr ng-repeat="student in viewingClass.students"
											ng-click="viewStudent(student)">
											<td>{{student.uniqueID}}</td>
											<td>{{student.name}}</td>
											<td>{{student.username}}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="x_panel">
							<div class="x_title">
								<h2>
									Manage My Classes</small>
								</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<p class="text-muted font-13 m-b-30">Give these class codes to
									your students at registration</p>
								<ul ng-cloak class="list-unstyled top_profiles scroll-view"
									style="height: auto">
									<li class="media event" ng-repeat="class in classRoster"><a
										class="pull-left border-aero profile_thumb"> <i
											class="fa fa-file-text aero"></i>
									</a>
										<div class="media-body">
											<a class="title" href="#">{{class.name}}</a>
											<p>
												<strong>{{class.class_code}} </strong> Class Code
											</p>
											<p>
												<small>{{class.students.length}} Students</small>
											</p>
										</div></li>
								</ul>
								<button ng-click="createClass()" type="button"
									class="btn btn-default btn-sm pull-right">
									<i class="fa fa-plus"></i> Create New
								</button>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>
									Student Profile</small>
								</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div ng-show="viewingStudent" class="profile_left">
									<div class="profile_img">
										<div id="crop-avatar">
											<!-- Current avatar -->
											<img class="img-responsive avatar-view"
												style="border-radius: 15px;" src="images/user.png"
												alt="Avatar" title="Change the avatar">
										</div>
									</div>
									<h3>{{viewingStudent.name}}</h3>

									<ul class="list-unstyled user_data">
										<li><i class="fa fa-envelope user-profile-icon"></i>
											{{viewingStudent.email}}</li>
										<li class="m-top-xs"><i
											class="fa fa-sign-in user-profile-icon"></i>
											{{viewingStudent.last_logon}}</li>
									</ul>
									<br>

									<!-- start skills -->
									<h4>Assignments</h4>
									<ul class="list-unstyled user_data">
										<li ng-repeat="a in viewingStudent.assignments">
											<p class="pull-left">PMC ID: {{a.pmcID}}</p> <br> <a
											ng-click="loadAssignment(a)" class="text-success" href="">
												preview contents </a>
											<div class="progress progress_lg">
												&nbsp; &nbsp; {{a.completion}}%
												<div class="progress-bar bg-green" role="progressbar"
													data-transitiongoal="{{a.completion}}"
													aria-valuenow="{{a.completion}}"
													style="width: {{a.completion"></div>
											</div>
										</li>
									</ul>
									<!-- end of skills -->

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /page content -->
		</div>
	</div>

	<div id="submissionModal" class="modal fade">
		<div class="modal-dialog" role="document" style="width: 80%">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="submissionTitle"></h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p id="submissionContents"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!---------------------------------------------------------------->
	<!-----------------------------JAVASCRIPT------------------------->
	<!---------------------------------------------------------------->
	<!-- JQuery Redirect -->
	<script src="vendors/jquery-redirect/jquery.redirect.js"></script>
	<!-- Bootstrap -->
	<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Nprogress -->
	<script src="vendors/nprogress/nprogress.js"></script>
	<!-- Bootstrap Progressbar -->
	<script
		src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
	<!-- Custom Theme Scripts -->
	<script src="js/custom.js"></script>
	<script src="js/DataService.js"></script>
	<!---------------------------------------->
	<!---------APPLICATION CODE--------------->
	<!---------------------------------------->
	<script>
var app = angular.module("teacherDash", []);
app.controller("teacherDashController", function($scope, $http) {
	$scope.classRoster = JSON.parse('<?=json_encode($classRoster)?>');
	$scope.viewingClass = $scope.classRoster[0];
	
	$scope.viewStudent = function(studentObj){
		$scope.viewingStudent = studentObj;
	}
	$scope.viewClass = function(classObj){
		$scope.viewingClass = classObj;
		console.log($scope.students);
	}
	$scope.loadAssignment = function (assignmentObj){
		var assignmentID = assignmentObj.uniqueID;
		var pmcID = assignmentObj.pmcID;
		var studentID = assignmentObj.studentID;
		
		$.redirect('assignment.php', {'pmcID': pmcID, 'assignmentID': assignmentID, 'studentID': studentID, 'previewing':true });
	}
	$scope.createClass = function(){
		var name = prompt("Enter a Class Name");
		if(name == null){ return; }
		if(name.length < 5){ alert("Invalid class name"); return; }
		
		var postData = "name="+name;
		postData += "&teacher_ID=" + '<?=$_SESSION['ID']?>';
		
		DataService.execute("POST", "/classes", postData, function(response){
			alert("Give this class code to your students: " + response);
			window.location.reload();
		});
	}
});
</script>
<?php include_once("js/analyticstracking.php")?>
</body>
</html>
