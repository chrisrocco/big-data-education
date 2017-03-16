<?php
// -------------------------------------------
// Assignment Management
// -------------------------------------------
$app->group ( '/assignments', function () {
	$this->post ( "/paperpool", function ($request, $response) {
		$adminAuth = '675598'; // I am going to implement oAuth framework. This is for development.
		if (! ($adminAuth == $request->getParam ( "auth" ))) {
			$response->getBody ()->write ( "You must have a valid admin key to perform this function" );
			return;
		}
		
		$pmc_ID = $request->getParam ( 'pmcID' );
		
		$pmc_config = "?db=pmc";
		$pmc_config .= "&id=$pmc_ID";
		$pmc_config .= "&retmode=json";
		$pmc_config .= "&tool=BigDataEd";
		$pmc_config .= "&email=chris.rocco7@gmail.com";
		$pubMedContent = file_get_contents ( "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi" . $pmc_config );
		$json = json_decode ( $pubMedContent, TRUE );
		$pmcTitle = $json ['result'] [$pmc_ID] ['title'];
		
		$result = $this->database->query ( "INSERT INTO paper_pool (pmc_ID, title) VALUES ('$pmc_ID', '$pmcTitle')" );
		$result = $this->database->query ( "INSERT INTO paper_pool (pmc_ID, title) VALUES ('$pmc_ID', '$pmcTitle')" );
		
		$successMsg = "Two instances of paper with PMC ID $pmc_ID have been added to the random assignment pool";
		$errorMsg = "Opps.. something went wrong. Call Me (205)-639-6666 ~Chris Rocco";
		if ($result)
			$msg = $successMsg;
		else
			$msg = $errorMsg;
		$response->getBody ()->write ( json_encode ( $msg ) );
	} );
	$this->get ( "/load", function ($request, $response) {
		$assignment_ID = $request->getParam ( "assignmentID" );
		$account_ID = $request->getParam ( "accountID" );
		// escape inputs
		$assignment_ID = $this->database->escape ( $assignment_ID );
		$account_ID = $this->database->escape ( $account_ID );
		
		$sql = "SELECT * FROM submissions";
		$sql .= " WHERE assignmentID='$assignment_ID'";
		$sql .= " ORDER BY ID DESC LIMIT 1";
		$subs = $this->database->select ( $sql );
		if (count ( $subs ) == 0) {
			echo "false";
			exit ();
		}
		$response->getBody ()->write ( json_encode ( $subs [0] ) );
	} );
	$this->get ( "/{account_ID}", function ($request, $response) {
		$account_ID = $request->getAttribute ( 'account_ID' );
		$result_set = $this->assignments->getAll ( $account_ID );
		$response->getBody ()->write ( json_encode ( $result_set ) );
	} );
	$this->post ( "/{account_ID}", function ($request, $response) {
		/* TODO - authenticate request */
		$account_ID = $request->getAttribute ( 'account_ID' );
		$pmc_ID = $this->database->escape ( $request->getParam ( 'pmcID' ) );
		$result = $this->assignments->create ( $account_ID, $pmc_ID );
		// Write result
		$successMsg = "Paper with PMC ID $pmc_ID has been assigned to account with ID $account_ID";
		$errorMsg = "Opps.. something went wrong. Call Me (205)-639-6666 ~Chris Rocco";
		if ($result)
			$msg = $successMsg;
		else
			$msg = $errorMsg;
		$response->getBody ()->write ( json_encode ( $msg ) );
	});
});

$app->group ( '/submissions', function () {
	$this->post ( "", function ($request, $response) {
		$account_ID = $request->getParam ( 'account_ID' );
		$assignment_ID = $request->getParam ( 'assignment_ID' );
		$paperJSON = $request->getParam ( 'paperJSON' );
		$completion = $request->getParam ( 'completion' );
		$done = $request->getParam ( 'done' );
		// Upload save data
		if($subID = $this->assignments->submit ( $account_ID, $assignment_ID, $paperJSON, $completion, $done )){
			$conflict_scan_result = $this->conflictManager->update($subID);
			$response->getBody()->write( json_encode("Sub ID: $subID, Conflict scan result: $conflict_scan_result"));
		} else {
			$response->getBody()->write( json_encode("Error uploading submission"));
		}
	} );
	$this->delete ( "", function ($request, $response) {
		$account_ID = $request->getParam ( 'account_ID' );
		$assignment_ID = $request->getParam ( 'assignment_ID' );
		
		$SQL = "DELETE FROM submissions WHERE studentID='$account_ID' AND assignmentID='$assignment_ID'";
		$this->database->query ( $SQL );
		$this->database->query ( "UPDATE assignments SET completion='0' WHERE uniqueID='$assignment_ID'" );
		exit ();
	} );
});

$app->group ( '/classes', function () {
	$this->post ( "", function ($request, $response) {
		$teacher_ID = $request->getparam ( "teacher_ID" );
		$name = $this->database->escape ( $request->getParam ( "name" ) );
		echo $this->database->insert ( "INSERT INTO classes (teacherID, name) VALUES ('$teacher_ID', '$name')" );
		exit ();
	} );
	$this->get ( "/{teacher_ID}/roster", function ($request, $response) {
		$teacherID = $request->getAttribute ( "teacher_ID" );
		
		$roster = [ ];
		$classes = $this->database->select ( "SELECT * FROM classes WHERE teacherID = '$teacherID'" );
		foreach ( $classes as $class ) {
			$classCode = $class ['class_code'];
			$students = $this->database->select ( "SELECT accounts.* FROM accounts, enrollments WHERE accounts.uniqueID = enrollments.studentID AND enrollments.class_code = '$classCode'" );
			$class ['students'] = $students;
			$roster [] = $class;
		}
		echo json_encode ( ($roster) );
		exit ();
	} );
} );

$app->group ( '/accounts', function () {
	$this->post ( "/login", function ($request, $response) {
		/*
		 * Post requests containing an 'email' and 'password' fields are checked against database.
		 * If an account with the credentials exsist, session variables are set, and the string "approved" is written.
		 * If the credentials do not exsist, the string "invalid" is written.
		 */
		$email = $request->getParam ( "email" );
		$password = $request->getParam ( "password" );
		$account = $this->accounts->login ( $email, $password );
		/* Fail */
		if (! $account) {
			echo "invalid";
			return;
		}
		/* Win */
		// set session variables
		$_SESSION ["ID"] = $account ['uniqueID'];
		$_SESSION ["name"] = $account ["name"];
		$_SESSION ["email"] = $account ['email'];
		$_SESSION ["password"] = $account ['password'];
		$_SESSION ["role"] = $account ['roleID'];
		$_SESSION ["login_time"] = time ();
		// set last logon date
		$ID = $account ['uniqueID'];
		$date = date ( 'Y-m-d H:i:s' );
		$this->database->query ( "UPDATE accounts SET last_logon='$date' WHERE uniqueID='$ID'" );
		
		echo "approved";
	} );
	$this->post ( "/register", function ($request, $response) {
		$email = $request->getParam ( "email" );
		$password = $request->getParam ( "password" );
		$name = $request->getParam ( "name" );
		$name = str_replace("'", "", $name);
		$role = $request->getParam ( "role" );
		// create account
		$account_ID = $this->accounts->register ( $name, $email, $password, $role );
		if ($account_ID === Accounts::$ALREADY_EXISTS_ERROR) {
			echo 'exists';
			return;
		}
		// enroll in class
		if ($role === '1') {
			$class_code = $request->getParam ( "classCode" );
			if (! $this->accounts->enroll ( $account_ID, $class_code )) {
				echo "invalid class code";
				$this->database->query ( "DELETE FROM accounts WHERE uniqueID='$account_ID'" );
				return;
			}
		}
		// send validation email
		// if (! $this->accounts->sendValidation ( $email, "https://https://bigdata.cas.uab.edu/API/public/index.php/accounts/validate" )) {
		// echo "error sending validation";
		// return;
		// }
		
		// assign random paper
		if ($role === '2') {
			$uniformTeacherAssignment = 2940117;
			if (! $this->assignments->create ( $account_ID, $uniformTeacherAssignment )) {
				echo "error creating teacher assignment";
				return;
			}
		} else {
			for($i = 0; $i < 5; $i ++) {
				if (! $this->assignments->createFromPool ( $account_ID )) {
					echo "error creating assignment";
					return;
				}
			}
		}
		
		echo "success";
	} );
	$this->get ( "/validate", function ($request, $response) {
		$hash = $request->getParam ( "hash" );
		if ($this->accounts->validate ( $hash )) {
			echo "Your account has been validated. You may now login";
		}
	} );
	$this->post ( "/reset", function ($request, $response) {
		$hash = $request->getParam ( "hash" );
		$password = $request->getParam ( "newPassword" );
		echo $this->accounts->resetPassword ( $hash, $password );
	} );
	$this->post ( "/recover", function ($request, $response) {
		$email = $request->getParam ( "email" );
		echo $this->accounts->sendRecovery ( $email );
	} );
} );

$app->get ('/conflicts/{SID}', function($req, $res){
	$submissionID = $req->getAttribute('SID');
	echo json_encode($this->conflictManager->scan($submissionID));
});
$app->get('/conflicts/preview/{SID}', function($request, $response){
	$submissionID = $request->getAttribute('SID');
	$theConflict = $this->conflictManager->scan($submissionID);
	if($submissionID === $theConflict['submissionA']) $otherID = $theConflict['submissionB'];
	else if($submissionID === $theConflict['submissionB']) $otherID = $theConflict['submissionA'];
	else {
		$error = "A conflict was found, but the provided ID didn't match either subID";
		echo $error;
		return;
	}
	
	$otherSubmission = $this->database->selectOne("SELECT * FROM submissions WHERE ID='$otherID'");
	$response->getBody()->write($otherSubmission['JSON']);
});

$app->post('/bulkAssignment', function($request, $response){
	$authKey = "aoisunbvjnawohg;ajndoifuapsjfpASJDFHLKAJSHFLKJHASLDKFJH";
	if($request->getParam('auth') != $authKey ){
		echo "Unauthorized";
		exit();
	}
	
	$SQL = "SELECT * from accounts WHERE roleID='1' AND uniqueID < 136";
	$accounts = $this->database->select($SQL);
	foreach ($accounts as $account){
		$ID = $account['uniqueID'];
		for ($i = 0; $i < 4; $i++) {
			// Create 4 assignments
			if($this->assignments->createFromPool($ID) == true){
				echo "<br/>";
				echo "Created new Assignment for " . $account['name'];
			}
		}
	}
});

$app->get('/control', function($request, $response){
    echo "Success<br/>";
   echo var_dump($request->getParams());
});