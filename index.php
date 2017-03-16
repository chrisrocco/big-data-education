<?php
session_start ();
if (isset ( $_SESSION ['ID'] ) && isset ( $_SESSION ['name'] ) && isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['email'] ) && isset ( $_SESSION ['role'] ) && isset ( $_SESSION ['login_time'] )) {
	if ($_SESSION ['role'] == "1") {
		echo "<script>window.location = 'myAssignments.php'</script>";
	}
	if ($_SESSION ['role'] == "2") {
		echo "<script>window.location = 'myClasses.php'</script>";
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
<title>BigDataEd | Login</title>

<!-- Bootstrap -->
<link href="vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<!-- Font Awesome -->
<link href="vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<!-- Animate.css -->
<link href="vendors/animate.css/animate.min.css" rel="stylesheet">
<!-- Custom Theme Style -->
<link href="css/custom.css" rel="stylesheet">
<!-- Angular JS -->
<script
	src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
</head>

<body class="login" ng-app="app" ng-controller="controller">
	<div>
		<a class="hiddenanchor" id="signup"></a> <a class="hiddenanchor"
			id="signin"></a>
		<div class="login_wrapper">
			<div id="login" class="animate form login_form">
				<section class="login_content">
					<div>					
						<img src="images/UAB-logo.png" style="max-width: 100%;">
						<br />
						<br />
					</div>
					<form name="loginForm" onSubmit="return login()">
						<h1>BIG DATA ED</h1>
						<div>
							<input name="email" type="text" class="form-control"
								placeholder="Email" required="" />
						</div>
						<div>
							<input name="password" type="password" class="form-control"
								placeholder="Password" required="" />
						</div>
						<p ng-cloak class="invalid-login">Username or Password is
							incorrect</p>
						<div>
							<input type="submit" class="btn btn-default submit"
								value="Log In" /> <a class="reset_pass"
								href="javascript:sendRecovery()">Lost your password?</a>
						</div>

						<div class="clearfix"></div>

						<div class="separator">
							<p class="change_link">
								New to site? <a href="#signup" class="to_register"> Create
									Account </a>
							</p>

							<div class="clearfix"></div>
							<br />

<!-- 							<div> -->
<!-- 								<h1> -->
<!-- 									<i class="fa fa-database"></i> BigDataEd -->
<!-- 								</h1> -->
<!-- 							</div> -->
						</div>
					</form>
				</section>
			</div>

			<div id="register" class="animate form registration_form">
				<section class="login_content">
					<form name="registerForm">
						<h1>Create Account</h1>
						<div>
							<input name="name" type="text" class="form-control"
								placeholder="Full Name" required="" />
						</div>
						<div>
							<input name="email" type="email" class="form-control"
								placeholder="Email" required="" />
						</div>
						<div>
							<input name="password" type="password" class="form-control"
								placeholder="Password" required="" />
						</div>
						<div>
							<label class="pull-left"> I am a ... </label> <select
								name="roleID" type="number" class="form-control"
								style="margin-bottom: 20px;" ng-model="roleField">
								<option value="">-- Select One ---</option>
								<option value="1">Student</option>
								<option value="2">Teacher</option>
							</select>
						</div>
						<div ng-show="roleField==1">
							<label> Class Code: </label> <input name="classCode"
								type="number" class="form-control" placeholder="Class Code"
								required="" />
						</div>
						<br /> <br />
						<div>
							<input type="" onclick="registerCheck()"
								class="btn btn-default submit" value="Submit" />
						</div>

						<div class="clearfix"></div>

						<div class="separator">
							<p class="change_link">
								Already a member ? <a href="#signin" class="to_register"> Log in
								</a>
							</p>

							<div class="clearfix"></div>
						</div>
					</form>
				</section>
			</div>
		</div>
	</div>

	<!-- Consent Form -->
	<div class="modal fade" id="consentModal" tabindex="-1" role="dialog"
		aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Please Agree to the
						Terms and Conditions</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					This â€œBig Data Edâ€� web portal is a part of the research project
					titled Science Education Enabling Careers (University of Alabama at
					Birmingham IRB Protocol X140226006) under the investigator J.
					Michael Wyss, PhD. The goal of this research is to teach you about
					scientific research and Big Data. In the process, we will work
					together to create a large data set that can be used to ask
					scientific questions. You will extract data from primary scientific
					research articles (known as coding). Your responses will be
					recorded and only your teacher will know your identity. Risks in
					participating in this study are minimal, and are similar to a class
					setting, where you might feel embarrassed if get an answer wrong.

					You have to complete the tasks as part of your grade. How the tasks
					affect your grade is up to you. By creating an account, you agree
					to:

					<ol>
						<li>Participate in this study by extracting data from primary
							scientific research articles</li>

						<li>Attempt to complete the assigned tasks of entering data into
							the â€œBig Data Edâ€� web</li> portal

						<li>Attempt to resolve any disagreements with another person who
							is coding the same primary research article (if such
							disagreements occur)</li>

						<li>Allows us to use the data you enter for research purposes
							&nbsp;&nbsp;&nbsp; - Data collected and reported from primary
							research articles</li>
					</ol>



					By clicking â€œI Agreeâ€� you agree to: - Allow us to use data
					collected on you about your performance on tests, surveys, or other

					evaluations relating to the research project If you do not agree,
					we will not retain data about your performance or characteristics
					of your responses or discussions. In either case, after the
					semester is over all of your responses will be anonymized, meaning
					no one will know which responses were created by you. If you have
					any concerns about your participation, please discuss with us. We
					are happy to provide any information we can to help answer
					questions you have about this study. If you have question,
					concerns, or complaints during or after your participation, please
					contact Dr. Patrice Capers, by phone at (205) 934-6885 or by email
					at pcapers@uab.edu. If you have questions about your rights as a
					research participant, or concerns or complaints about the research,
					you may contact the UAB Office of the IRB (OIRB) at (205) 934-3789
					or toll free at 1-855- 860-3789. Regular hours for the OIRB are
					8:00 a.m. to 5:00 p.m. CT, Monday through Friday. You may also call
					this number in the event the research staff cannot be reached or
					your wish to talk to someone else. Be sure to reference protocol
					X140226006.
				</div>
				<div class="modal-footer">
					<button type="button" onclick="agree()" class="btn btn-primary">I
						Agree</button>
				</div>
			</div>
		</div>
	</div>
	<!-- / Consent Form -->

	<!-- jQuery -->
	<script src="vendors/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- DataService -->
	<script src="js/DataService.js"></script>

	<script>
	$(".invalid-login").css("display", "none");
	$("input").on("keydown", function(){
		$("input[type=text], input[type='password']").css("border", "1px solid #c8c8c8");
		$(".invalid-login").css("display", "none");
	});
		
	function login(){
		var email = document.forms['loginForm']['email'].value;
		var password = document.forms['loginForm']['password'].value;
		
		var loginData = "email="+email;
		loginData += "&password="+password;
		
		DataService.execute("POST", "/accounts/login", loginData, function(response){
			console.log("Login Result: " + response);
			 if(response == "invalid"){
				 $("input[type=text], input[type='password']").css("border", "1px solid #D1282B");
				 $(".invalid-login").css("display", "block");
			 } else if(response == "approved"){
				 window.location.reload();
			 }
		});
			
		return false;
	}
	
	var name;
	var email;
	var password;
	var roleID;
	var classCode;
	function registerCheck(){
		name = document.forms['registerForm']['name'].value;
		email = document.forms['registerForm']['email'].value;
		password = document.forms['registerForm']['password'].value;
		roleID = document.forms['registerForm']['roleID'].value;
		classCode = document.forms['registerForm']['classCode'].value;
				
		if(name == ""){ alert("Enter a name"); return false; }
		if(!email.includes("@") || !email.includes(".")){ alert("invalid email"); return false; }
		if(password.length < 5){ alert("Password must be at least 5 characters"); return false; }
		if(roleID != 1 && roleID != 2){ alert("Specify if you are a teacher or student"); return false; }
		if(roleID === "1"){
			if(classCode < 100000 || classCode > 999999){ alert("invalid class code"); return false; }
		}
		
		$('#consentModal').modal("show");
		
		return false;
	}
	function agree(){
		var registerData = "name="+name;
		registerData += "&email="+email;
		registerData += "&password="+password;
		registerData += "&role="+roleID;
		registerData += "&classCode="+classCode;
 		registerData = registerData.replace(/'/g, ""); // Escape quotes
 		
		console.log("Role: " + roleID);
		
		console.log(registerData);
		
		
		DataService.execute("POST", "/accounts/register", registerData, function(response){
			console.log("Create Account Result: " + response);
			 if(response === "success"){
// 				 alert("Account creation successful. Check email for account activation link");
				 alert("Account creation successful. You may now login");
				 window.location = window.location.pathname;
			 } else if(response === "invalid class code"){
				 alert("Invalid class code");
			 } else if(response === "exists"){
				 alert("Email is already in use");
			 } else {
				 alert("Something went wrong");
				 console.log(response);
			 }
		});
	}
	function sendRecovery(){
		var email = prompt("Account email: ");
		
		var valid = (email.includes("@") &&
					 email.includes(".") &&
					 email.indexOf("@") != 0);
		if(!valid){ alert("invalid email"); return; }
		
		var postData = "email="+email;
		DataService.execute("POST", "/accounts/recover", postData, function(response){
			if(response){
				console.log(response);
				alert("Recovery instructions sent.");
			}
		});
	}
	</script>
	<script>
	var app = angular.module("app", []);
	app.controller("controller", function($scope) {});
	</script>
    
    <?php include_once("js/analyticstracking.php")?>
  </body>
</html>