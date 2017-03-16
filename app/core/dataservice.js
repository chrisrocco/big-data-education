angular
	.module("core")
	.factory("dataservice", DataService)
	
DataService.$inject = ['$http', '$q'];
function DataService($http, $q){
	var API_base_path = "API/public/index.php";
	
	var service = {
		getConflict: getConflict,
		submit: submit,
		load: load,
	}
	return service;
	
	function getConflict(assignmentID){
		var path = "/API/public/index.php";
		var route = "/conflicts/" + assignmentID;

		return $http.get(path + route)
			.then(function(response){
				return response.data;
			})
			.catch(function(error){
				console.log("Get conflict failed for conflict with ID: " + ID);
			});
	}
	function submit(submission){
		$http.post("/submissions", submission)
			.then(function(response){
				console.log("Upload submission response: " + response);
			})
			.catch(function(error){
				console.log("Upload submission failed: " + error);
			});
	}
	function load(assignmentID){
		var params = "?assignmentID="+assignmentID;
		return $http.get( API_base_path + "/assignments/load"+ params )
			.then(function(response){
				return response.data;
			})
			.catch(function(error){
				console.log("Get submission failed for assignmentID: " + assignmentID);
			});
	}
}