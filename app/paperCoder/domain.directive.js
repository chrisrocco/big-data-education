/**
* @desc domain directive that is specific to the paperCoder module. Keeps two way binding to a Domain object.
* 		It handles its own click events and completion detection
* @example <bd-domain domain="domainObject"></bd-domain>
*/
angular
    .module('paper-coder')
    .directive('bdDomain', BigDataDomain);

BigDataDomain.$inject = ['viewer.service', 'paper-coder.service'];
function BigDataDomain(viewerService, paperCoderService) {
	return {
		restrict: 'E',
		scope: {
			domain: "=domain",
		},
		link: function(scope, element, attrs) {
			// Handle my own click event
			scope.clicked = function(){
				if(scope.editMode){
					paperCoderService.toggleScope(scope.domain);
				} else {
					viewerService.setView(scope.domain);
				}
			};
			// Pass competion detection to the paper coder service
			scope.isComplete = paperCoderService.isComplete;
			// Watch for global changes to edit mode
			var paperObserver = {};
			paperObserver.notify = function(){
				scope.editMode = paperCoderService.getEditMode();
			}
			paperCoderService.registerObserver(paperObserver);
		},
		templateUrl: 'app/paperCoder/domain.html'
	}
}