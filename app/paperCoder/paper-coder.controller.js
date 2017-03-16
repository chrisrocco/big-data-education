angular
	.module("paper-coder")
	.controller("PaperCoderController", PaperCoderController);

PaperCoderController.$inject = ['$scope', '$http', '$log', 'paper-coder.service', 'viewer.service'];
function PaperCoderController($scope, $http, $log, paperCoderService, viewerService){
	var paperObserver = {};
	paperObserver.notify = function(){
		$scope.editMode = paperCoderService.getEditMode();
	}
	paperCoderService.registerObserver(paperObserver);
	
	$scope.simple = function(){
		alert("wow");
	}
	$scope.toggleEditMode = function(){
		console.log("Edit toggle called")
		paperCoderService.toggleEditMode();
	}
	$scope.newStudyArm = paperCoderService.newStudyArm;
	$scope.completion = 0;
	$scope.calculateCompletion = function(){
		return paperCoderService.calculateCompletion();
	}
	
	$scope.studyArmClicked = function(armObj) {
		if (paperCoderService.getEditMode()) {
			paperCoderService.deleteStudyArm(armObj);
		} else {
			viewerService.setView(armObj);
		}
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
			$log.log("Send data: "); $log.debug(postData);
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

	
	$scope.experiment = [];
	var loadData = "accountID="+accountID;
	loadData += "&assignmentID="+assignmentID;
	DataService.execute("GET", "/assignments/load", loadData, function(response){
		var data = JSON.parse(response);
		$log.log("Save data loaded from server:", data);
		
		if(data){
			var paper = JSON.parse(data['JSON']);
			$scope.done = data['done'];
			$scope.$apply(function(){
				$scope.experiment = paper;
			})
			paperCoderService.setPaper(paper);
		} else {
			var paper = {
					domains : [],
					studyArms : []
			};
			paper.domains = initializeExperiment();
			$scope.$apply(function(){
				$scope.experiment = paper;
			})
			paperCoderService.setPaper(paper);
			paperCoderService.newStudyArm();
			$scope.done = false;
		}
	});
	
}