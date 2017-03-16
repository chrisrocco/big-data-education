<?php
/**
 * This class is responsible for the detection, assessment, creation, deletion, and maintenence of conflicting assignment submissions.
 * @author Chris Rocco
 * Entire class is dependent on the structure of a submission
 */
class ConflictManager {
	
	private $database;             // Allows database interaction
	private $assignments;          // Needed for manipulation of assignments
	
	// Codes for the different conflicts
	const CONFLICT_STRUCTURE = 0;
	const CONFLICT_SCOPE = 1;
	const CONFLICT_VALUE = 2;
	
	function __construct($database, $assignments){
		$this->database = $database;
		$this->assignments = $assignments;
	}
	
	/*
	 * 
	 */
	function update($submissionID){
		$theSubmission = $this->database->selectOne("SELECT * FROM submissions WHERE ID='$submissionID'");
		if($theSubmission['done'] === 'true' &&
				($conflict = $this->scan($submissionID))){   // If the conflict scan returns a conflict
			$this->assignments->setStatus($conflict['assignmentA'], "conflict");
			$this->assignments->setStatus($conflict['assignmentB'], "conflict");
			return "Found and conflict and updated assignment statuses";
		} else {
			// Set assignments status to active
			$subsToSamePaper = $this->getOtherSubmissionsToPMCID($submissionID);
			$subA = $subsToSamePaper[0];
			$subB = $subsToSamePaper[1];
			$this->assignments->setStatus($subA['assignmentID'], "active");
			$this->assignments->setStatus($subB['assignmentID'], "active");
			return "Scanned and found no conflict. Set statuses to active";
		}
	}


	function scan($submissionID){
		$subsToSamePaper = $this->getOtherSubmissionsToPMCID($submissionID);				// Get the other submission to the same paper, if it exsists
		if($subsToSamePaper == null) return;												// If it doesn't exsist, stop right now
		$subA = $subsToSamePaper[0];														// The first submission
		$subB = $subsToSamePaper[1];														// The second submission
		$snapshotA = json_decode($subA['JSON'], true);										// The first sub's entered information, decoded into a PHP object
		$snapshotB = json_decode($subB['JSON'], true);										// The second sub's entered information, decoded into a PHP object
		// Run the three layers of comparison on them
		// Look for a difference in the number of study arms
		if($conflict = $this->compareStructure($snapshotA, $snapshotB)){					// If a conflict details object was generated
			return $this->createConflict($subA, $subB, self::CONFLICT_STRUCTURE,$conflict);	// Use it to create a conflict object, and return it
		}
		// Look for a difference in the scopes of the domains
		if($conflict = $this->compareScopes($snapshotA, $snapshotB)){						// If a conflict details object was generated
			return $this->createConflict($subA, $subB, self::CONFLICT_SCOPE,$conflict);		// Use it to create a conflict object, and return it
		}
		// Look for a difference in the values entered
		if($conflict = $this->compareValues($snapshotA, $snapshotB)){						// If a conflict details object was generated
			return $this->createConflict($subA, $subB, self::CONFLICT_VALUE,$conflict);		// Use it to create a conflict object, and return it
		}
	}
	
	function getOtherSubmissionsToPMCID($submissionID){
		$SQL = "SELECT assignments.* FROM assignments, submissions";
		$SQL .= " WHERE submissions.ID='$submissionID'";
		$SQL .= " AND assignments.uniqueID=submissions.assignmentID";
		$assignment = $this->database->selectOne($SQL);
		$pmcID = $assignment['pmcID'];
		$SQL = "SELECT submissions.* FROM assignments, submissions";
		$SQL .= " WHERE assignments.pmcID='$pmcID'";
		$SQL .= " AND submissions.assignmentID=assignments.uniqueID";
		$subsToSamePaper = $this->database->select($SQL);
		if(count($subsToSamePaper) !== 2) return;
		else return $subsToSamePaper;
	}
	
	/**
	 * 
	 * @param Submission Object $snapshotA
	 * @param Submission Object $snapshotB
	 * @return ConflictDetails[]
	 */
	private function compareStructure($snapshotA, $snapshotB){
		$armsCountA = count($snapshotA['studyArms']);	// The first sub's study arm count
		$armsCountB = count($snapshotB['studyArms']);	// The second sub's study arm count
		
		if($armsCountA !== $armsCountB){				// If there is a conflict
			$structureConflictDetails = [				// create a conflict details object
				"armsA" => $armsCountA,					// The number of study arms in the first paper
				"armsB" => $armsCountB					// The number of study arms in the second paper
			];
			return $structureConflictDetails;			// If there was a conflict, return the the details object. Else, return null.
		}
	}
	
	/**
	 * 
	 * @param snapshot $snapshotA
	 * @param snapshot $snapshotB
	 * @return ConflictDetails[] boolean
	 */
	private function compareScopes($snapshotA, $snapshotB){
		for($i = 0; $i < count($snapshotA['domains']); $i++){      // For every exeriment level domain
			$domainA = $snapshotA['domains'][$i];				   // first sub's domain
			$domainB = $snapshotB['domains'][$i];				   // second sub's domain
			if($domainA['scope'] !== $domainB['scope']) {          // if the scopes are different
				$scopeConflictDetails[] = [                        // create a conflict details object
						"domain" => $domainA['meta']['name'],	   // unique identifier for the domain
						"scopeA" => $domainA['scope'],			   // the scope of the first domain
						"scopeB" => $domainB['scope']			   // the scope of the second domain
				];
			}
		}
		
		if(isset($scopeConflictDetails)) return $scopeConflictDetails; // if there was a conflict, return the details object. Else, return null.
	}
	
	/**
	 * 
	 * @param snapshot $snapshotA
	 * @param snapshot $snapshotB
	 * @return ConflictDetails
	 */
	private function compareValues($snapshotA, $snapshotB){
		for($i = 0; $i < count($snapshotA['domains']); $i++){							// for each domain
			$domainA = $snapshotA['domains'][$i];										// The first sub
			$domainB = $snapshotB['domains'][$i];										// The second sub
			if($fieldConflicts = $this->compareValuesDomains($domainA, $domainB, [])){	// Get a list of the domain's field conflicts
				foreach($fieldConflicts as $fieldConflict){								// for each domains conflict list
					$valueConflictDetails[] = $fieldConflict;							// Add the conflict to the master list
				}
			}
		}
		
		if(isset($valueConflictDetails)) return $valueConflictDetails;					// If there was a conflict, return the details object
	}
	
	/**
	 * Compares the values of the fields of the two domain objects provided.
	 * For each conflict, a detail object is created and added to the conflictDetails array.
	 * This method uses recursion to run on domains and subdomains.
	 * @param Domain $domainA
	 * @param Domain $domainB
	 * @param MasterConflictDetailsArray $conflictDetails
	 * @return ConflictDetails
	 */
	private function compareValuesDomains($domainA, $domainB, $conflictDetails){
		for ($i = 0; $i < count($domainA['fields']); $i++) { // for each field
			$fieldA = $domainA['fields'][$i];
			$fieldB = $domainB['fields'][$i];
			if(isset($fieldA['value']) && isset($fieldB['value'])){  // if they both have a value field
				$valA = $fieldA['value'];
				$valB = $fieldB['value'];
				if($valA !== $valB) {             // if there is a conflict
					$conflictDetails[] = [                 // add it to the conflict details
							"field" => $fieldA['name'],
							"valA" => $valA,
							"valB" => $valB
					];
				}
			}
		}
		// For each subdomain
		for ($i = 0; $i < count($domainA['subDomains']); $i++){ // for each subdomain
			$domainAA = $domainA['subDomains'][$i];
			$domainBB = $domainB['subDomains'][$i];
			if($conflict = $this->compareValuesDomains($domainAA, $domainBB, $conflictDetails)){ // recursively call this method
				$conflictDetails[] = $conflict;
			}
		}
	
		if(isset($conflictDetails)){
			return $conflictDetails;
		}
	}

	/**
	 * Creates a conflict entry in the database.
	 * Consists of the two assignments in conflict,
	 * the type of conflict ( see conflict definitions ), and
	 * a conflict details object which contains detailed information about the conflict
	 * @param SubmissionObject $submissionA
	 * @param SubmissionObject $submissionB
	 * @param ConflictType $type
	 * @param ConflictDetails $details
	 * @return ConflictObject[]
	 */
	private function createConflict($submissionA, $submissionB, $type, $details){
        $detailsJSON = json_encode($details); // Convert the PHP details object to JSON

        /* Escape and quote data for SQL insertion */
        $submissionA = $this->database->quote($submissionA);
        $submissionB = $this->database->quote($submissionB);
        $type = $this->database->quote($type);
        $detailsJSON = $this->database->quote($detailsJSON);

        $conflict = [
			'aid1' => $submissionA['assignmentID'],
			'aid2' => $submissionB['assignmentID'],
			'type' => $type,
			'details' => $detailsJSON
		];
		$SQL = "INSERT INTO conflicts";
		$SQL .= " (aid1, aid2, type, details)";
		$SQL .= " VALUES ($submissionA, $submissionB, $type, $detailsJSON)";

		$conflictID = $this->database->insert($SQL);
		return $conflict;
	}
}
?>