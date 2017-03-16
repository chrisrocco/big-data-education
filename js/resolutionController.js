angular.module("paperCoder").controller("paperCoderController", function($scope, $http) {
	/* FUNCTIONS */

	/* Functional Helpers */
	$scope.toggleScope = function(domainObj) {
		if (domainObj.scope == SCOPES.STUDY) {
			console.log("its const");
			$scope.setConstLevel(domainObj);
		} else {
			$scope.setStudyLevel(domainObj);
		}
		console.log(domainObj.scope);
	};
	$scope.setStudyLevel = function(domainObj) {
		//domainObj.scope = SCOPES.STUDY;
		setScope(domainObj, SCOPES.STUDY);
		for (var i = 0; i < $scope.experiment.studyArms.length; i++) {
			$scope.experiment.studyArms[i].subDomains.push(JSON.parse(JSON.stringify(domainObj)));
		}
	};
	$scope.setConstLevel = function(domainObj) {
		domainObj.scope = SCOPES.CONST;
		for (var i = 0; i < $scope.experiment.domains.length; i++) {
			if ($scope.experiment.domains[i].meta.name == domainObj.meta.name) {
				//$scope.experiment.domains[i].scope = SCOPES.CONST;
				setScope($scope.experiment.domains[i], SCOPES.CONST);
			}
		}
		for (var i = 0; i < $scope.experiment.studyArms.length; i++) {
			var index = $scope.experiment.studyArms[i].subDomains.indexOf(domainObj);
			$scope.experiment.studyArms[i].subDomains.splice(index, 1);
		}
	};
	// Recursively set scope value for domain and all of its subdomains. ( Helper )
	function setScope(domain, value) {
		domain.scope = value;
		for (var i = 0; i < domain.subDomains.length; i++) {
			setScope(domain.subDomains[i], value);
		}
	}

	$scope.newStudyArm = function() {
		// Create the object
		var studyArm = {
			scope : SCOPES.STUDY,
			meta : {
				label : "Study Arm "
			},
			fields : [],
			subDomains : []
		};
		// Add it to the study arm array
		$scope.experiment.studyArms.push(studyArm);
		// Set it's label to its index
		studyArm.meta.label += $scope.experiment.studyArms.length;
		// Add all domains flagged at study arm
		var studyArmDomainContent = JSON.stringify($scope.experiment.studyArms[0].subDomains);
		studyArm.subDomains = JSON.parse(studyArmDomainContent);
	}
	$scope.domainClicked = function(domainObj) {
		if ($scope.editing) {
			$scope.toggleScope(domainObj);
		} else {
			$scope.setModalObj(domainObj);
		}
	};
	$scope.studyArmClicked = function(armObj) {
		if ($scope.editing) {
			if ($scope.experiment.studyArms.length <= 1) {
				alert("You must have at least one study arm");
				return;
			}
			// Delete it
			var index = $scope.experiment.studyArms.indexOf(armObj);
			$scope.experiment.studyArms.splice(index, 1);
		} else {
			$scope.view
			$scope.setModalObj(armObj);
		}
	};

	/* Stack Management */
	$scope.view = SCOPES.CONST;
	$scope.modalStack = [];
	$scope.setModalObj = function(domainObj) {
		$scope.view = domainObj.scope;
		$scope.modalStack.push($scope.modalDomain);
		$scope.modalDomain = domainObj;
		$('#formModal').modal('show');
		setTimeout(function() {
			$('[data-toggle="tooltip"]').tooltip();
		/**/ console.log("Called.");
		}, 100);
	};
	$scope.prevModalObj = function() {
		$scope.modalDomain = $scope.modalStack.pop();
	};

	/* Server Communication */
	$scope.save = function(showModal) {
		console.log($scope.experiment);

		var snapShot = JSON.stringify($scope.experiment);
		snapShot = encodeURIComponent(snapShot); // Escape special characters;

		var postData = "assignment_ID=" + assignmentID;
		postData += "&account_ID=" + accountID;
		postData += "&paperJSON=" + snapShot;
		postData += "&completion=" + $scope.calculateCompletion();
		postData += "&done=" + $scope.done;

		DataService.execute("POST", "/submissions", postData, function(response) {
			console.log("Response...");
			console.log(response);
			if (showModal) {
				$('#saveModal').modal('show');
			}
		});
	};
	$scope.flagDone = function() {
		$scope.done = !$scope.done;
	};
	$scope.resetAssignment = function() {
		if (confirm("This will delete all information entered so far. This can not be undone.") == false) {
			return;
		}
		if (confirm("Are you sure?") == false) {
			return;
		}
		if (confirm("Are you really, really, sure?") == false) {
			return;
		}

		var deleteData = "assignment_ID=" + assignmentID;
		deleteData += "&account_ID=" + accountID;

		DataService.execute("DELETE", "/submissions", deleteData, function(response){
			window.location.reload();
		});
	};

	/* Analytic */
	$scope.isComplete = function(domainObj) {
		if (domainObj.fields.length == 0 && domainObj.subDomains.length == 0) {
			return false;
		}
		for (var i = 0; i < domainObj.fields.length; i++) {
			if (domainObj.fields[i].type == "range") {
				continue;
			}
			if (domainObj.fields[i].value === "" &&
				domainObj.fields[i].disabled != true) {
				return false;
			}
		}
		for (var j = 0; j < domainObj.subDomains.length; j++) {
			if ($scope.isComplete(domainObj.subDomains[j]) == false) {
				return false;
			}
		}
		return true;
	}
	$scope.factorIn = function(domain, index) {
		if (domain.fields.length > 0) {
			$scope.totalDomains++;
		}
		if ($scope.isComplete(domain)) {
			$scope.completeDomains++;
		}
		domain.subDomains.forEach($scope.factorIn);
	}
	$scope.calculateCompletion = function() {
		$scope.totalDomains = 0;
		$scope.completeDomains = 0;
		// For each constant domain
		$scope.experiment.domains.forEach($scope.factorIn);
		// For each study arm domain
		$scope.experiment.studyArms.forEach($scope.factorIn);

		var completion = ($scope.completeDomains / $scope.totalDomains) * 100;
		return Math.round(completion);
	}

	/* FIELDS */
	$scope.editing = false;
	$("#formModal").on("hidden.bs.modal", function() {
		// Reset the modal stack on modal close
		$scope.modalStack = [];
		$scope.save(false);
	});
	$scope.init = function() {
		if (save !== '') {
			$scope.experiment = JSON.parse(save);
		} else {
			$scope.experiment = {
				domains : [],
				studyArms : []
			};
			$scope.experiment.domains = initializeExperiment();
			$scope.newStudyArm();
		}
		$scope.done = done;
	};
	$scope.init();
});