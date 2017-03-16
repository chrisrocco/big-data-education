angular
	.module("core")
	.directive("bdPanel", Panel)
	
function Panel(){
	return {
		scope: {},
	    controllerAs: 'ctrl',
	    bindToController: {
	      title: '=title'
	    },
		transclude: true,
		templateUrl: "app/core/panel.html",
		controller: function(){}
	}
}