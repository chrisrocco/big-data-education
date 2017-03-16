angular
	.module("paper-coder")
	.factory("viewer.service", ViewerService)
	
/**
 * This Service is responsible for handling the viewing of domains inside of a modal window.
 * It manages the all of the viewing data, and provides an API for interacting with it.
 * Independent of the HTML view itself.
 * It allows the HTML element to be registered as an observer, which will be notified of any outside changes to the data.
 */
function ViewerService() {
	var SCOPES = { "CONST":0, "STUDY":1 };
	
	var observer;
	var visible = false;
	var viewScope = SCOPES.CONST;
	var viewStack = [];
	
	var service = {
		setView: setView,
		getView: getView,
		getViewScope: getViewScope,
		getViewStack: getViewStack,
		previous: previous,
		clearStack: clearStack,
		registerObserver: registerObserver
	}
	return service;
	
	//////////////////////////////////////////////////////
	function setView(domainObject){
		viewStack.push(domainObject);
		console.log("Viewing Object"); console.log(getView());
		console.log("View Stack"); console.log(viewStack);
		viewScope = domainObject.scope;
		observer.notify();
	}
	function previous(){
		viewStack.pop();
		observer.notify();
	}
	function clearStack(){
		viewStack = [];
	}
	
	function getView(){
		return viewStack[viewStack.length - 1];
	}
	function getViewStack(){
		return viewStack;
	}
	function getViewScope(){
		return viewScope;
	}
	function registerObserver(observerObject){
		observer = observerObject;
	}
}