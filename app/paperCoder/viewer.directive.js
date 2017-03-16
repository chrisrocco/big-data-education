angular
	.module('paper-coder')
	.directive('bdViewer', ViewerDirective)

ViewerDirective.$inject = ['viewer.service', 'paper-coder.service']
function ViewerDirective(viewerService, paperCoderService) {
	
	var ID = "test";
	
	return {
		restrict: 'E',
		link: function(scope, element, attrs) {
			scope.ID = ID; // Just so it's not hard-coded
			scope.editMode = false;
			scope.toggleEdit = function(){
				paperCoderService.toggleEditMode();
			}
			element.on("hidden.bs.modal", function() {	// Reset the modal stack on close
				viewerService.clearStack();
			});
			scope.back = function(){					// Go back one view
				viewerService.previous();
			}
			
			// Watch for updates in the viewer service using an observer pattern
			var observer = {};
			observer.notify = function(){
				scope.viewing = viewerService.getView();
				scope.viewScope = scope.viewing.scope;
				scope.viewStack = viewerService.getViewStack();
				$('#' + scope.ID).modal('show');
				setTimeout(function() {
					$('[data-toggle="tooltip"]').tooltip();
				}, 100);
			}
			viewerService.registerObserver(observer);
		},
		templateUrl: 'app/paperCoder/viewer.html',
	}
}