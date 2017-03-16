angular
	.module("paper-coder")
	.controller("ResolutionUtility", ResolutionUtility);

ResolutionUtility.$inject = ['$scope', '$http', '$log', 'paper-coder.service', 'viewer.service', 'dataservice'];
function ResolutionUtility($scope, $http, $log, paperCoderService, viewerService, dataservice){
    var paperObserver = {};
    paperObserver.notify = function(){
        $scope.editMode = paperCoderService.getEditMode();
    }
    paperCoderService.registerObserver(paperObserver);

    $scope.completion = 0;

    $scope.simple = function(){
        alert("wow");
    }
    $scope.toggleEditMode = function(){
        console.log("Edit toggle called")
        paperCoderService.toggleEditMode();
    }
    $scope.newStudyArm = paperCoderService.newStudyArm;
    $scope.calculateCompletion = paperCoderService.calculateCompletion;

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
        postData += "&completion=" + $scope.calculateCompletion($scope.experiment);
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

	/* Initialization */
    $scope.experiment = [];
    var loadData = "accountID="+accountID;
    loadData += "&assignmentID="+assignmentID;

    dataservice.load(assignmentID).then(function(response){
        var data = response;
        submissionID = data.ID;
        $log.log("Save data loaded from server:", data);

        if(data){
            var paper = JSON.parse(data['JSON']);
            $scope.done = data['done'];
            $scope.experiment = paper;
            paperCoderService.setPaper(paper);
            DataService.execute("GET", "/conflicts/preview/"+submissionID, {}, function(response){
                $scope.$apply(function(){
                    $scope.otherPaper = JSON.parse(response);
                    console.log("Other Paper: ", $scope.otherPaper);
                });
            });
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

	/*
	 DataService.execute("GET", "/assignments/load", loadData, function(response){
	 var data = JSON.parse(response);
	 submissionID = data.ID;
	 $log.log("Save data loaded from server:", data);

	 if(data){
	 var paper = JSON.parse(data['JSON']);
	 $scope.done = data['done'];
	 $scope.$apply(function(){
	 $scope.experiment = paper;
	 })
	 paperCoderService.setPaper(paper);
	 DataService.execute("GET", "/conflicts/preview/"+submissionID, {}, function(response){
	 $scope.$apply(function(){
	 $scope.otherPaper = JSON.parse(response);
	 console.log("Other Paper: ", $scope.otherPaper);
	 });
	 });
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
	 */
}

