angular
	.module("paper-coder")
	.factory("paper-coder.service", paperCoderService)
	
function paperCoderService(){
	var paper = {
			domains: [],
			studyArms: []
	};
	var editMode = false;

	var observers = [];
	var service = {
		paper: paper,
		newStudyArm: newStudyArm,
		deleteStudyArm: deleteStudyArm,
		toggleScope: toggleScope,
		setPaper: setPaper,
		armCount: armCount,
		toggleEditMode: toggleEditMode,
		getEditMode: getEditMode,
		registerObserver: registerObserver,
		calculateCompletion: calculateCompletion,
		isComplete: isComplete,
	}
	return service
	
	///////////////////////////
	function setPaper(paperObject){
		paper = paperObject;
	}
	function armCount(){
		return paper.studyArms.length;
	}
	function newStudyArm() {
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
		console.log("PAPER RIGHT NOW: ", paper);
		paper.studyArms.push(studyArm);
		// Set it's label to its index
		studyArm.meta.label += paper.studyArms.length;
		// Add all domains flagged at study arm
		var studyArmDomainContent = JSON.stringify(paper.studyArms[0].subDomains);
		studyArm.subDomains = JSON.parse(studyArmDomainContent);
	}
	function deleteStudyArm(studyArmObject){
		if(armCount() == 1) return;
		var index = paper.studyArms.indexOf(studyArmObject);
		paper.studyArms.splice(index, 1);
	}
	function toggleScope (domainObj) {
		if (domainObj.scope == SCOPES.STUDY) {
			setConstLevel(domainObj);
		} else {
			setStudyLevel(domainObj);
		}
	};
	function setStudyLevel (domainObj) {
		setScope(domainObj, SCOPES.STUDY);
		for (var i = 0; i < paper.studyArms.length; i++) {
			paper.studyArms[i].subDomains.push(JSON.parse(JSON.stringify(domainObj)));
		}
	};
	function setConstLevel (domainObj) {
		domainObj.scope = SCOPES.CONST;
		for (var i = 0; i < paper.domains.length; i++) {
			if (paper.domains[i].meta.name == domainObj.meta.name) {
				setScope(paper.domains[i], SCOPES.CONST);
			}
		}
		for (var i = 0; i < paper.studyArms.length; i++) {
			var index = paper.studyArms[i].subDomains.indexOf(domainObj);
			paper.studyArms[i].subDomains.splice(index, 1);
		}
	};
	function toggleEditMode(){
		editMode = !editMode;
		notifyObservers();
		console.log("Set edit mode to", editMode);
	}
	function getEditMode(){
		return editMode;
	}
	// Recursively set scope value for domain and all of its subdomains. ( Helper )
	function setScope(domain, value) {
		domain.scope = value;
		for (var i = 0; i < domain.subDomains.length; i++) {
			setScope(domain.subDomains[i], value);
		}
	}
	function registerObserver(observerObject){
		observers.push(observerObject);
	}
	function notifyObservers(){
		for(var i = 0; i < observers.length; i++){
			observers[i].notify();
		}
	}
	// Completion Detection
	var totalDomains = 0;
	var completeDomains = 0;
	function isComplete (domainObj) {
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
			if (isComplete(domainObj.subDomains[j]) == false) {
				return false;
			}
		}
		return true;
	}
	function factorIn(domain, index) {
		if (domain.fields.length > 0) {
			totalDomains++;
		}
		if (isComplete(domain)) {
			completeDomains++;
		}
		domain.subDomains.forEach(factorIn);
	}
	function calculateCompletion() {
		totalDomains = 0;
		completeDomains = 0;
		// For each constant domain
		paper.domains.forEach(factorIn);
		// For each study arm domain
		paper.studyArms.forEach(factorIn);

		var completion = (completeDomains / totalDomains) * 100;
		return Math.round(completion);
	}
}