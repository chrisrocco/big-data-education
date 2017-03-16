<?php
class Assignments {
	private $database;
	
	function __construct($database){
		$this->database = $database;
	}
	
	/**
	* Assigns a paper
	*/
	function create($account_ID, $pmc_ID){
		// Fetching PMC_ID title
		$pmc_title = $this->getTitle($pmc_ID);
		// Insert into database
		return $this->database->query("INSERT INTO assignments (studentID, pmcID, title, status) VALUES ('$account_ID', '$pmc_ID', '$pmc_title', 'active')");
	}
	
	/**
	* Returns a student's assignments
	*/
	function getAll($account_ID){
		$sql = "SELECT * FROM assignments";
		$sql .= " WHERE studentID = '$account_ID'";
		$sql .= " ORDER BY date_created";
		return $this->database->select($sql);
	}
	
	function setStatus($ID, $status){
		$SQL = "UPDATE assignments SET status='$status' WHERE uniqueID='$ID'";
		return $this->database->query($SQL);
	}
	
	function getIDbySubID($submissionID){
		$SQL = "SELECT assignments.uniqueID";
		$SQL .= " FROM submissions, assignments";
		$SQL .= " WHERE submissions.assignmentID = assignments.uniqueID";
		$SQL .= " AND submissions.ID = '$submissionID'";
		if($result = $this->database->selectOne($SQL)){
			return $result['uniqueID'];
		}
	}
	
	/**
	* Assigns a random assignment from a table of papers
	*/
	function createFromPool($account_ID){
		$randomPapers = $this->database->select("SELECT * FROM paper_pool ORDER BY RAND() LIMIT 1");
		$randomPMC_ID = $randomPapers[0]['pmc_ID'];
		$randomPMC_title = $this->database->escape($randomPapers[0]['title']);
		$randomID = $randomPapers[0]['ID'];
		// Make sure they don't get the same paper twice
		
		// Assign it
		$sql = "INSERT INTO assignments";
		$sql .= " (studentID, pmcID, title, status)";
		$sql .= " VALUES ('$account_ID', '$randomPMC_ID', '$randomPMC_title', 'active')";
		return $this->database->query($sql);
		// Remove it
 		return $this->database->query("DELETE FROM paper_pool WHERE ID='$randomID'");
	}
	
	/**
	* Returns a paper's title
	*/
	function getTitle($pmc_ID){
		$content = file_get_contents("https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pmc&id=$pmc_ID&retmode=json&tool=BigData_Testing&email=chris.rocco7@gmail.com");
		$arr = json_decode($content, TRUE);
		return $arr['result'][$pmc_ID]['title'];
	}
	
	/**
	* Uploads an assignment submission
	*/
	function submit($account_ID, $assignment_ID, $paperJSON, $completion, $done){
		// Delete all old submissions to this student's assignment
		$deleteSQL = "DELETE FROM submissions WHERE studentID='$account_ID' AND assignmentID='$assignment_ID'";
		$this->database->query($deleteSQL);
		// Insert the new submission
		$sql = "INSERT INTO submissions";
		$sql .= " (studentID, assignmentID, JSON, done)";
		$sql .= " VALUES ('$account_ID', '$assignment_ID', '$paperJSON', '$done')";
		$ID = $this->database->insert($sql);
		$this->database->query("UPDATE assignments SET completion='$completion' WHERE uniqueID='$assignment_ID'");
		return $ID;
	}
}
?>