/**
* @desc field directive that is specific to the paperCoder module. Keeps two way binding to a Field object.
* @example <bd-field field="fieldObject" type="text"></bd-field>
*/
angular
    .module('paper-coder')
    .directive('bdField', BigDataField);

BigDataField.$inject = ['paper-coder.service']
function BigDataField(paperCoderService) {
	return {
		restrict: 'E',
		scope: {
			field: "=field",
			readonly: "=readonly"
		},
		link: function(scope, element, attrs) {
		   scope.getContentUrl = function() {
               return 'app/paperCoder/field-' + scope.field.type + '.html';
           };
		   if(scope.readonly){

		   	console.log("Read Only: ", element);
		   }
		},
		templateUrl: 'app/paperCoder/field-wrapper.html',
		controller: BigDataFieldController
	}
}

BigDataFieldController.$inject = ['$scope', 'paper-coder.service'];
function BigDataFieldController($scope, paperCoderService){
	$scope.control = paperCoderService.control;
}