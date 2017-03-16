function DataService(API_base_path){
	this.execute = function(method, route, data, callback){
		$.ajax({
			url: API_base_path + route,
			method: method,
			data: data,
			success: callback
		});
	}
}
var DataService = new DataService('API/public/index.php');