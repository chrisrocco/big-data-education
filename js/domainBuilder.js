function Domain(name, label, faicon){
		this.meta = {
			"name": name,
			"label": label,
			"faicon": faicon
		};
		this.fields = [];
		this.subDomains = [];
		this.addField = function(fieldObj){
			this.fields.push(fieldObj);
		};
		this.addSubdomain = function(domainObj){
			this.subDomains.push(domainObj);
		};
		this.tooltip = function(text){
			this.tooltip = text;
		};
}
function Field(name, label, placeholder, faicon, type){
		this.name = name;
		this.label = label;
		this.placeholder = placeholder;
		this.faicon = faicon;
		this.type = type;
		this.value = "";
		this.tooltip = function(text){
			this.tooltip = text;
		};
}
function configSelect(fieldObj, optionsArray){
	fieldObj['options'] = optionsArray;
}
function configBoolean(fieldObj, trueOption, falseOption){
	fieldObj.trueOption = trueOption;
	fieldObj.falseOption = falseOption;
}
function configRange(fieldObj, minimum, maximum){
	fieldObj.minimum = minimum;
	fieldObj.maximum = maximum;
}
function initializeExperiment(){
	// Acclimation Period
	//removed and renamed to adaptation - Jan 30, 2017 - ws
/*	var acclimationPeriod = new Domain("acclimation", "Acclimation Period", "calendar");
		var ap = new Field(
		"acclimationPeriod",
		"Acclimation Period",
		"What did they do to the mice in the acclimation period?", 
		"question",
		"text");
	acclimationPeriod.addField(ap);*/

	//Adaptation
	var adaptationPeriod = new Domain("adaptation", "Adaptation", "calendar");
	adaptationPeriod.tooltip("This is a time before the scientists actually start the experiment where they try to get the mice familiar with something known as the acclimation period.");
		var acclimationPeriod = new Field(
		"acclimationPeriod",
		"Acclimation Period",
		"What did they do to the mice in the acclimation period?",
		"question",
		"text");
		acclimationPeriod.tooltip("Describe what the scientists did to the mice before the experiment started. These statements may look like “prior to the experiment, mice were maintained on a control diet for 2 weeks”, or “mice were allowed to acclimate for 1 week prior to receiving the treatment.”");
		var acclimationDuration = new Field(
		"acclimationDuration",
		"Acclimation Duration",
		"How many weeks did it last?",
		"question",
		"number");
		acclimationDuration.tooltip("Scientist say how long mice did something before the start of the experiment (examples: “from weaning until 8 months” would be reported “32”, assuming four weeks per month; “for the 1st week” would be reported as “1”; “mice were adapted to the facility for 1-2 weeks before start” would be reported as “1-2”)");
	
	adaptationPeriod.addField(acclimationPeriod);
	adaptationPeriod.addField(acclimationDuration);

	// Age
	var age = new Domain("age", "Age", "calendar-o");
	age.tooltip("The age of the mice at different points in the experiment");
		var ageAtStart = new Field(
		"ageAtStart", 
		"Age at Start", 
		"How many days old were the mice when the experiment started?", 
		"calendar-check-o", 
		"number");
		ageAtStart.tooltip("Scientists report how many weeks old the mice were when the experiment started. If there is an acclimation period, make sure to report only when the experiment started (example: “five week old mice were allowed to acclimate for 1 week prior to being assigned to exercise or control” – the age at start would be 6 weeks; “seven week old mice adapted to the facility for 1-2 weeks before assignment” would be “8-9”).");
		var ageAtWeight = new Field(
		"ageAtWeight", 
		"Age at Weight", 
		"How many days old were the mice when the final weight was recorded?", 
		"calendar-check-o", 
		"number");
		ageAtWeight.tooltip("The age of mice when the final weight was recorded can be presented in several ways. Sometimes they will just say (example: “weights were taken at 18 weeks”); sometimes you have to do math (example: if the age at start was 5 weeks, and the treatment lasted 12, then the age at final weight would be 17 weeks; if there was a 1 week acclimation period before starting treatment, it would be 18 weeks); sometimes, only a range is available (example: “mice were started on treatment between 6 and 8 weeks old, and kept on treatment for 8 weeks” is reported as “14-16”).");
	age.addField(ageAtStart);
	age.addField(ageAtWeight);
	// Animal Facility
	var animalFacility = new Domain("facility", "Animal Facility", "home");
	animalFacility.tooltip("The building where the mice were housed during the experiment.");
		var facilityName = new Field(
		"facilityName", 
		"Animal Facility Name", 
		"The name of where the mice where housed.", 
		"home", 
		"text");
		facilityName.tooltip("Scientists mention the name of the animal facility where mice were housed (example: the University of Alabama at Birmingham).");
		var facilityCityState = new Field(
		"facilityCityState", 
		"Animal Facility City and State", 
		"City and state (if available) of facility location. e.g. Birminham, AL", 
		"globe", 
		"text");
		facilityCityState.tooltip("Scientists may mention the city where the animal facility is located (example: if at UAB, then this would be ‘Birmingham, AL’). Sometimes they do not say, but if all of the authors are from the same institution, then we can guess that they were in the city where the authors are.");
		var facilityCountry = new Field(
		"facilityCountry", 
		"Animal Facility Country", 
		"What country is the building located in?", 
		"globe", 
		"text");
		facilityCountry.tooltip("Scientists may mention the country where the animal facility is located (example: if at UAB, then this would be ‘United States’). Sometimes they do not say, but if all of the authors are from the same institution, then we can guess that they were in the country where the authors are.");
		var animalLocations = new Field(
		"animalLocations", 
		"Animal Locations", 
		"Were mice housed in different cities or states?", 
		"globe", 
		"boolean");
		animalLocations.tooltip("When scientists say where mice were housed, they may mention one location or more than one location (example: Ohio State animal facility OR evaluated a three sites).");
		var pathogenFreeEnvironment = new Field(
		"pathogenFreeEnvironment",
		"Germ- or Pathogen-Free Environment",
		"Did the mice live in a germ- or pathogen-free environment?",
		"question",
		"select"
		);
		pathogenFreeEnvironment.tooltip("It’s important to make sure the research animals do not get sick, both for their well-being and for the quality of the research. In some cases, the scientists need their to be no bacteria (sometimes called “completely germ-free”); other times, they just need to be sure there are not disease like influenza (and they will report this as “specific pathogen free”). Occasionally they will indicate that the facility was not germ free. Remember if they do not report a status, disable the field; don’t assume it’s “not germ free.”");
			configSelect(pathogenFreeEnvironment, ['Completely germ-free','Specific pathogen-free','Not germ free']);
			configBoolean(animalLocations, "Yes", "No");
	animalFacility.addField(facilityName);
	animalFacility.addField(facilityCityState);
	animalFacility.addField(facilityCountry);
	animalFacility.addField(animalLocations);
	animalFacility.addField(pathogenFreeEnvironment);
	// Cage
	var cage = new Domain("cage", "Cage", "archive");
	cage.tooltip("The scientists describe information about the type of cage conditions mice were housed in, such as cage type or air flow.");
		var cageType = new Field(
		"cageType", 
		"Cage Type", 
		"What type of cage were the mice housed in?", 
		"archive", 
		"select");
		cageType.tooltip("Scientists say what type of cage mice were placed in (example: “polycarbonate standard filter top cages” = polycarbonate cages).");
		var airCirculation = new Field(
		"airCirculation", 
		"Air Circulation", 
		"What type of air circulation occurred?", 
		"", 
		"text");
		airCirculation.tooltip("Describes how air is exchanged in the animal facility or cages (example: “ventilation rack system used” or “filter top cages”).");
		var beddingMaterial = new Field(
		"beddingMaterial", 
		"Bedding Material", 
		"What type of bedding material was used in the cages?", 
		"bed", 
		"text");
		beddingMaterial.tooltip("Describes the type of material used to line the bottom of the cages (e.g. wood chips, sawdust, etc.)");
		var changeFreq = new Field(
		"changeFrequency", 
		"Change Frequency", 
		"How often was the bedding material changed in days?", 
		"repeat", 
		"number");
		changeFreq.tooltip("Describes how often the bedding material was changed (example: “cages changed weekly” would be “weekly”; “cages changed every other day” would be “<7 days”; terms like “biweekly” can mean “every other week” or “twice per week”, so read carefully to see if you can distinguish between the two definitions).");
		var enrichmentType = new Field(
		"enrichmentType", 
		"Enrichment Type", 
		"What type of enrichment did they provide the mice with?", 
		"futbol-o", 
		"text");
		enrichmentType.tooltip("Scientists may say that mice were given material for hiding (example: a piece of PVC pipe), destruction (example: paper squares), or chewing (example: Nylabone).");
		configSelect(cageType,['Open cages','Polycarbonate cages','Soft filter-top cages','Plastic pens','Microisolator cages','Other']);
	cage.addField(cageType);
	cage.addField(airCirculation);
	cage.addField(beddingMaterial);
	cage.addField(changeFreq);
	cage.addField(enrichmentType);


	// Lighting
	var lighting = new Domain("lighting", "Lighting", "lightbulb-o");
	lighting.tooltip("The amount of time lights were turned on or off in the animal facility.");
		var lightingSchedule = new Field(
		"lightingSchedule", 
		"Lighting Schedule", 
		"Was the light schedule constant or did it change?", 
		"lightbulb-o", 
		"boolean");
		lightingSchedule.tooltip("Scientists say whether the amount of time the lights were turned on or off stayed the same (constant) or changed (variable) during the experiment (example: “mice were housed with a 12 hour light/dark cycle” is constant; “mice were switched every two weeks to a light cycle starting at 6 AM or 12 AM” is variable).");
			configBoolean(lightingSchedule, "Constant", "Changing");
		var lightHours = new Field(
		"lightHours", 
		"Light Hours", 
		"How long were the lights kept on in hours?", 
		"lightbulb-o", 
		"number");
		lightHours.tooltip("Scientist say how long the light cycle lasted in hours (examples: “a 12:12 light-dark cycle” = 12; “14 h light/10 h dark” = 14).");
		var darkHours = new Field(
		"darkHours", 
		"Dark Hours", 
		"How long were the lights turned off in hours?", 
		"lightbulb-o", 
		"number");
		darkHours.tooltip("Scientist say how long the dark cycle lasted in hours (examples: “a 12:12 light-dark cycle” = 12; “14 h light/10 h dark” = 10). Sometimes they will only report hours of light, in which case we assume a 24 hour day (example: if they say “a 12 hour light cycle”, we will assume 12 hours of dark).");
		var lightStartTime = new Field(
		"lightStartTime", 
		"Light Start Time", 
		"What time were the lights turned on in military time?", 
		"lightbulb-o", 
		"number");
		lightStartTime.tooltip("Scientist say when the light cycle started (example: “the light cycle started at 6 a.m.”, “0600h”, or “06:00” would be reported as 0600 without a colon in the time)");
	lighting.addField(lightingSchedule);
	lighting.addField(lightHours);
	lighting.addField(darkHours);
	lighting.addField(lightStartTime);
	// Temperature
	var temperature = new Domain("temperature", "Climate", "thermometer-half");
	temperature.tooltip("The temperature of the animal facility");
		var constantTemperature = new Field(
		"constantTemperature", 
		"Constant Temperature", 
		"Did the temperature stay the same, or did it change?", 
		"thermometer-half", 
		"boolean");
		constantTemperature.tooltip("Scientists say whether the temperature was purposefully changed (variable) or was held constant during the experiment (example: “temperature controlled between 25 and 27 degrees Celsius” is “Constant”, even though there was a temperature range because they tried to hold it constant; “mice were intermittently exposed to cold temperature” is “Changing”).");
			configBoolean(constantTemperature, "Constant", "Changing");
		var temperatureRange = new Field(
		"temperatureRange",
		"Temperature Range",
		"What was the range of temperature in Celsius?",
		"thermometer-half",
		"range");
		temperatureRange.tooltip("Scientists say the temperature of the animal room or facility (example: “22±1oC” should be reported as “21 – 23”; “22-24 oC” should be reported as “22 – 24”; “22 oC” should be reported as “22”). If it is reported in Fahrenheit, use an internet search to convert it to Celsius (example: type “73 Fahrenheit in Celsius” into Google).");
			configRange(temperatureRange, 0, 60);
		var humidity = new Field(
		"facilityHumidity",
		"Humidity",
		"What was the humidity of the faciliity?",
		"thermometer-half",
		"range");
		humidity.tooltip("Scientists say the percent humidity of the animal room or facility (example: “50% humidity” should be reported as “50”; “humidity-controlled room 55±10%” should be reported as “45 - 65”).");
			configRange(humidity, 0, 75);
	temperature.addField(humidity);
	temperature.addField(constantTemperature);
	temperature.addField(temperatureRange);
	// Treatment Duration
	var treatmentDuration = new Domain("treatmentDuration", "Treatment Length", "clock-o");
	treatmentDuration.tooltip("How long the treatment lasted during the experiment.");
		var daysOnTreatment = new Field(
		"daysOnTreatment",
		"Weeks on Treatment",
		"How many weeks did the mice recieve treatment?",
		"calendar",
		"number");
		daysOnTreatment.tooltip("Scientists say how long the experiment lasted (examples: “mice were started on a 28-day treatment” is reported as 4 weeks; “the experiment was 8 weeks in duration” is reported as = 8; “the mice were euthanized after 8 or 19 weeks on treatment” is an indication that there are two separate study arms here, with one group studied for 8 weeks and the other for 19 weeks).");
	treatmentDuration.addField(daysOnTreatment);
	// Exercise
	var exercise = new Domain("exercise", "Exercise", "bolt");
	exercise.tooltip("The opportunity to be physically active using equipment.");
		var exerciseType = new Field(
		"exerciseType",
		"Exercise Type",
		"What type of exercise did the mice perform?",
		"bolt",
		"text");
		exerciseType.tooltip("Describes the type of exercise mice were exposed to (examples: exercise wheel, rotarod, hamster wheel etc.).");
		var exerciseFreq = new Field(
		"exerciseFreq",
		"Exercise Frequency",
		"How often did the mice exercise or have access?",
		"bolt",
		"select");
		exerciseFreq.tooltip("(examples: “four sessions spanning 2 weeks” would be “2-6 times per week”; “mice were housed in a cage in the presence of a non-load bearing hamster wheel” means it was always around, so coded as “continuously”)");
		var forcedExercise = new Field(
		"forcedExcecise",
		"Forced Excercise",
		"Were the mice forced to excercise?",
		"bolt",
		"boolean");
		forcedExercise.tooltip("Describes whether the mice were allowed to exercise as they wanted, such as having an exercise wheel in their cage (“Available”), or if they were forced to exercise, such as being placed on rodent treadmill (“Forced”).");
			configSelect(exerciseFreq,['Continuously (ex: wheel in the cage','Multiple times per day','Daily','2-6 times per week','Weekly','2-4 times per month','Monthly','Less frequently than monthly']);
			configBoolean(forcedExercise, "Forced","Available");
	exercise.addField(exerciseType);
	exercise.addField(exerciseFreq);
	exercise.addField(forcedExercise);
	// Mice
	var mice = new Domain("mice", "Mice", "info");
	mice.tooltip("Describes details about the mice themselves, such as where they came from.");
		var vendorName = new Field(
		"vendorName",
		"Vendor Name",
		"What company or lab did the scientists get the mice from?",
		"question",
		"text");
		vendorName.tooltip("Scientists say the name of the company or lab where they got the mice from (examples: “obtained from the Experimental Animal Center, China Medical University” is reported as “China Medical University”; purchased from Jackson Laboratories = “Jackson Laboratories”)");
		var vendorCityState = new Field(
		"vendorCity",
		"Vendor City and State",
		"What city and state (if available) is the company or lab located in? (e.g. Birmingham, AL)",
		"globe",
		"text");
		vendorCityState.tooltip("Scientists may say where the company or lab is located right after they mention the name of the vendor (examples: “Jackson Laboratory (Bar Harbor, ME)” = “Bar Harbor, ME”; “Experimental Animal Center, China Medical University, Shenyang, China” = “Shenyang”). If only a country is listed, disable the field and enter the country in the “Vendor Country” field. (example: “Charles River (Charles River Laboratories, France)”).");
		var vendorCountry = new Field(
		"vendorCountry",
		"Vendor Country",
		"What country is the company or lab located in?",
		"globe",
		"text");
		vendorCountry.tooltip("Scientists may say where the company or lab is located right after they mention the name of the vendor (examples: “Jackson Laboratory (Bar Harbor, ME)” = “USA”; “Experimental Animal Center, China Medical University, Shenyang, China” = “China”; “Charles River (Charles River Laboratories, France)” = “France”).");
		var sex = new Field(
		"sex",
		"Sex",
		"Did the experiment use male, female, or mixture of sex mice?",
		"venus",
		"select");
		sex.tooltip("Scientists should say whether they used male or female rats, or a mix. Make sure if they used both male and female mice, but separated them in experiments, that you indicate that “sex” is a variable at the study arm level (example: “male and female mice were each given the treatment” is reported as male for one study arm and female for another).");
			configSelect(sex, ['Male', 'Female', 'Mixture']);
		var breed = new Field(
		"breed",
		"Breed/Background/Strain",
		"What is the name of the mouse strain?",
		"question",
		"text");
		breed.tooltip("Name given to separate different types of mice based on where they originated (e.g. C57BL/6, C57BL/6J, C57BL/6H).");
	mice.addField(vendorName);
	mice.addField(vendorCityState);
	mice.addField(vendorCountry);
	mice.addField(sex);
	mice.addField(breed);
	// Surgery
	var surgery = new Domain("surgery", "Surgery", "user-md");
	surgery.tooltip("Describes if mice went through a surgical procedure.");
		var surgeryType = new Field(
		"surgeryType",
		"Surgery Type",
		"What type of surgery was performed on the mice?",
		"question",
		"text");
		surgeryType.tooltip("Describe what type of surgery the mice underwent (examples: “ovariectomy”, “Roux-en-Y gastric bypass”, “cardiac stent”).");
	surgery.addField(surgeryType);
	// Single Compounds
	var singleCompounds = new Domain("singleCompounds", "Single Compounds", "medkit");
	singleCompounds.tooltip("Describes if mice received a compound (e.g. drug, pharmaceutical, supplement, etc.) as a part of their treatment.");
		var routeOfAdmin = new Field(
		"routeOfAdministration",
		"Route of Administration",
		"How was the compound given to the mice",
		"question",
		"select");
		routeOfAdmin.tooltip("Scientists describe how they gave the mice a compound (examples: “insulin was given i.p.” means it was injected intraperitoneally; “mice were given the drug p.o. by gavage”, which means given ‘per oral’ and was gavaged; “the compound was spread topically” is reported as topically).");
			configSelect(routeOfAdmin,['Injected','Water','Food','Topically (on the bodys surface','Gavage (forced into stomach)']);
		var compoundName = new Field(
		"compoundName",
		"Compound Name",
		"What is the name of the compound used?",
		"question",
		"text");
		compoundName.tooltip("Scientists describe what they gave (examples: “acetaminophen”; “compound GSK-86473”; “antibiotic”). If it appears to be a nutrient and is part of food or drink, consider moving it to the ‘diet’ category (example: “glucose” is a compound, but is typically considered part of the diet; “phytosterols”, although they exist in food, are typically given like a drug).");
		var compoundFreq = new Field(
		"compoundFrequency",
		"Frequency",
		"How often was the compound given?",
		"repeat",
		"select");
		compoundFreq.tooltip("How frequently the animal received the compound. (examples: “the compound was dissolved in the drinking water” means that it was around all the time, so it was “continuously”; “the first pellets had the treatments put on them, and after the animals completed these they received the rest of their food” means that they got the compound “daily”; “animals were injected every Wednesday” means they received it “weekly”");
			configSelect(compoundFreq,['Continuously (such as in water)','Multiple times per day','Daily','2-6 times per week','Weekly','2-4 times per month','Monthly','Less frequently than monthly']);
		var dosage = new Field(
		"dosage",
		"Dosage",
		"How much of the compound were mice given?",
		"question",
		"text");
		dosage.tooltip("Describe how much of the compound was given to each mouse. This can be described in a number of ways (examples: “the compound was dissolved in water at a concentration of 20% g/v” would be reported as “20% g/v”; “the animals were gavaged with 3 mg/kg body weight” would be reported as 3 mg/kg body weight; “100IU of insulin were delivered i.p.” would be reported as “100IU”),");
	singleCompounds.addField(routeOfAdmin);
	singleCompounds.addField(compoundName);
	singleCompounds.addField(compoundFreq);
	singleCompounds.addField(dosage);
	// Genetic Manipulation
	var geneticManipulation = new Domain("geneticManipulation", "Genetic Manipulation", "heartbeat");
	geneticManipulation.tooltip("Describes if the DNA of mice were changed.");
		var gmType = new Field(
		"geneticManipulationType",
		"Genetic Manipulation Type",
		"What type of genetic manipulation did mice go through?",
		"heartbeat",
		"text");
		gmType.tooltip("");
	geneticManipulation.addField(gmType);
	// Animal Approval Ethics
	var ethics = new Domain("ethics", "Ethical Statement", "thumbs-up");
	ethics.tooltip("Describes the approval to perform research on mice.");
		var ethicalStatement = new Field(
		"ethicalStatement",
		"Ethical Statement",
		"Did the scientists state that they received approval from an Ethical Review Committee (e.g. IACUC, IRB)?",
		"question",
		"boolean");
		ethicalStatement.tooltip("Scientists say whether they received approval from an Ethical Review Committee (examples: “IACUC – Institutional Animal Care and Use Committee , IRB – Institutional Review Board”, “Institutional Guideline for the Care and Use of Laboratory Animals”, “Dutch Law on Animal Experimentation”)");
			configBoolean(ethicalStatement,'Yes','No');
	ethics.addField(ethicalStatement);
	// Housing Density
	var housingDensity = new Domain("housingDensity", "Housing Density", "home");
	housingDensity.tooltip("Describes how many mice were placed in each cage.");
		var micePerCage = new Field(
		"micePerCage",
		"Mice Per Cage",
		"How many were in a single cage?",
		"hashtag",
		"number");
		micePerCage.tooltip("(examples: “single housed” = “1”, “individually housed” = “1”, “9 -10 per cage” is reported as “9-10”, “5 mice/cage” = “5”)");
	housingDensity.addField(micePerCage);
	// Mice in Treatment
	var miceInTreatment = new Domain("miceInTreatment", "Mice per Treatment", "hashtag");
	miceInTreatment.tooltip("Reports the number of mice included in each treatment group in the experiment.");
		var sampleSize = new Field(
		"sampleSize",
		"Sample Size",
		"How many mice (sample size, n) were included in the treatment?",
		"question",
		"number");
		sampleSize.tooltip("The number of mice given a particular treatment. This is not the total number in a study, but the number in each study arm. Sometimes it is reported as a range (example: “4-5 mice per treatment” is reported as “4-5”). The number of mice is not always the same between study arms, so one study arm might have 4 mice, while another could have 5. In that case, ‘Sample Size’ needs to be moved to the study arms.");
	miceInTreatment.addField(sampleSize);
	// Weight
	var weight = new Domain("weight", "Weight", "balance-scale");
	weight.tooltip("Reports the final weight of the mice");
		var whereReported = new Field(
		"whereReported",
		"Where in the paper is weight reported?",
		"",
		"question",
		"select");
		whereReported.tooltip("Weight can be reported in several ways. It can be reported in a table as numbers; in the text as numbers; in a figure, such as a bar chart or a line graph; or mentioned in generic terms in the text (example: “weight did not differ between groups”), without numbers. Sometimes it is in multiple places, or reported only as differences over time or between groups. And, sometimes, it is not reported at all.");
			configSelect(whereReported, [ 'A table with numbers',
										'Text, with numbers reported',
										'In a figure',
										'Mentioned generally in the text',
										'Presented as differences from baseline',
										'Presented as differences between groups']);
		var averageFinalWeight = new Field(
		"averageFinalWeight",
		"Average Final Weight",
		"What was the last reported average (mean) weight of the mice in grams?",
		"scale",
		"number");
		averageFinalWeight.tooltip("The weight as reported in the paper. If no numbers are reported, then disable this field. Hopefully numbers will be reported in a table or text. Do not estimate the numbers from figures. Do not report weights as differences from baseline or differences between groups.");
		var errorOfMeasurmentValue = new Field(
		"errorOfMeasurmentValue",
		"Error for the Final Weight",
		"What was the standard deviator (S.D.) or standard error (S.E.) of the last reported average (mean) weight of the mice in grams?",
		"question",
		"number");
		errorOfMeasurmentValue.tooltip("Scientist may report means followed by a “±” sign or parentheses and a number to show the error of measurement (example: “22 ± 3” is reported as “3”; “22 (3)” is also reported as “3”). If there is more than one number, then report them both with a dash or hyphen (example: “22[19,25]” is reported as “19-25”). If not reporting a final weight, do not report the error here.");
		var errorOfMeasurmentType = new Field(
		"errorOfMeasurmentType",
		"Error of Measurement Type",
		"What type of error of measurement was reported?",
		"question",
		"select");
		errorOfMeasurmentType.tooltip("Scientist say what type of error measurement they used. This can be represented as S.D. or sd for “standard deviation”; S.E. or s.e.m. for “standard error of the mean”; or CI for “confidence interval”. Sometimes the type of error is shown right next to the numbers (example: “22 ± 3 s.d.”); other times there is a ‘statistics’ or ‘analysis’ section in the paper that says what they used throughout the paper (example: “values are reported as means and standard deviations”). If not reporting a final weight, do not report the error type.");
			configSelect(errorOfMeasurmentType,['Standard Deviation (S.D. or s.d.)','Standard Error or Standard Error of the Mean (s.e. of S.E.M.','Confidence Interval (C.I.)']);
		/*var reportingOfWeight = new Field(
		"reportingOfWeight",
		"Reporting of Weight",
		"Was the average final weight reported in any other form (e.g. in a figure, differences between groups) besides a numerical value?",
		"question",
		"text");
		var dataGrouping = new Field(
		"dataGrouping",
		"Data Grouping",
		"Did the scientist report the average final weight in sub-groups within the study arm?",
		"question",
		"text");*/
	weight.addField(whereReported);
	weight.addField(averageFinalWeight);
	weight.addField(errorOfMeasurmentValue);
	weight.addField(errorOfMeasurmentType);
	/*weight.addField(reportingOfWeight);
	weight.addField(dataGrouping);*/
	// Diet
	var diet = new Domain("diet", "Diet", "cutlery");
	diet.tooltip("Describes what the mice ate");
		var dietType = new Field(
		"dietType",
		"Diet Type",
		"What diet were the mice placed on?",
		"question",
		"text");
		dietType.tooltip("Scientists write the type of diet mice were given (examples: low-fat control diet, high fat diet, standard lab diet, standard rodent chow)");
		var dietVendor = new Field(
		"dietVendor",
		"Vendor Name", 
		"What company or lab did the scientists get the diet from?", 
		"question", 
		"text");
		dietVendor.tooltip("Scientists write the name of the company where they ordered the diet for the mice (examples: “Research Diets, Inc.”; “Ao-boxing Biotech Company Ltd.”) or sometimes make it themselves (example: “diets were made in-house from purified ingredients”).");
		var dietID = new Field(
		"dietID", 
		"Vendor ID", 
		"What is the catalog number of the diet?", 
		"question", 
		"text");
		dietID.tooltip("Scientists may write the catalog number of the diet they used to feed the mice (examples: “Research Diets, D12451” should be reported as “D12451”; “catalog ID 5bV8” would be reported as “5bV8”)");
		var vendorCityState = new Field(
		"vendorCity", 
		"Vendor City/State", 
		"What city and state (if available) is the company or lab located in? (e.g. Birmingham, AL)", 
		"question", 
		"text");
		vendorCityState.tooltip("Scientist may write the city where the company or lab is located after mentioning the name of the company when describing the diet (example: “Research Diets, Inc.; New Brunswick, NJ” is reported as “New Brunswick, NJ”; “Beijing, China” is reported as “Beijing”; “Purina/Test Diet (St. Louis MO)” is reported as “St. Louis, MO”).");
		var vendorCountry = new Field(
		"vendorCountry",
		"Vendor Country",
		"What country is the company or lab located in?",
		"question",
		"text");
		vendorCountry.tooltip("Scientist may write the country where the company or lab is located after mentioning the name of the company when describing the diet (example: “Research Diets, Inc.; New Brunswick, NJ” is reported as “USA”; “Beijing, China” is reported as “China”; “Purina/Test Diet (St. Louis MO)” is reported as “USA”).");
		var feedingFreq = new Field(
		"feedingFrequency", 
		"Feeding Design", 
		"How were the mice fed?", 
		"question", 
		"select");
		feedingFreq.tooltip("Were the mice fed ad libitum, which means food is freely available to them to consume as much as they want; paired, which means the amount available to one animal depends on the amount that another animal ate; or fixed, which means the amount offered to each animal is a set amount determined by the researchers.");
			configSelect(feedingFreq,['Ad libitum','Paired','Fixed']);
		var dietComposition = new Domain("dietComposition", "Diet Composition", "cutlery");
			var percentEnergy = new Field(
			"percentEnergy", 
			"Percent by Energy", 
			"How did the scientists express the percentages?", 
			"percent", 
			"boolean");
			percentEnergy.tooltip("Scientists often describe the composition of diets in terms of percentages, but how the percentage is calculated is important. For instance, if they say a diet is 60% fat, is that 60% of energy or calories from fat (that is, 60% of all the food energy is from fat, known as “percent by energy”) or is it percent by mass or weight (that is, 60% of the weight of the food is fat, or “percent by weight”). Often, they won’t say, in which case, disable the field.");
				configBoolean(percentEnergy, 'Percent by energy','Percent by weight');
			var percentFat = new Field(
			"percentFat", 
			"Percent Fat", 
			"What percent of the diet was made of fat?", 
			"percent", 
			"number");
			percentFat.tooltip("Scientists may write what percent of the diet is made of fat (example: “10% fat diet” = “10”; “60% kcal fat” = “60”). Sometimes only one macronutrient will be reported, which means we do not know the percent for the other two. If, however, they report two macronutrients, and no alcohol is in the diet, then we can get the third from subtraction (example: 60% carbohydrate and 20% fat means 10% protein to get to 100%).");
			var percentCarbs = new Field(
			"percentCarbohydrates", 
			"Percent Carbohydrates", 
			"What percent of the diet was made of carbohydrates?", 
			"percent", 
			"number");
			percentCarbs.tooltip("Scientists may write what percent of the diet is made of carbohydrates (example: “70% of calories from carbohydrates” = “70”; “20% carbohydrate diet” = “20”). Sometimes only one macronutrient will be reported, which means we do not know the percent for the other two. If, however, they report two macronutrients, and no alcohol is in the diet, then we can get the third from subtraction (example: 60% carbohydrate and 20% fat means 10% protein to get to 100%).");
			var percentProtein = new Field(
			"percentProtein", 
			"Percent Protein", 
			"What percent of the diet was made of protein?", 
			"percent", 
			"number");
			percentProtein.tooltip("Scientists may write what percent of the diet was made of protein (example: “20% of calories from protein” = “20”; “20% kcal protein” = “20”). Sometimes only one macronutrient will be reported, which means we do not know the percent for the other two. If, however, they report two macronutrients, and no alcohol is in the diet, then we can get the third from subtraction (example: 60% carbohydrate and 20% fat means 10% protein to get to 100%).");
		dietComposition.addField(percentEnergy);
		dietComposition.addField(percentFat);
		dietComposition.addField(percentCarbs);
		dietComposition.addField(percentProtein);
	diet.addField(dietType);
	diet.addField(dietID);
	diet.addField(dietVendor);
	diet.addField(vendorCityState);
	diet.addField(feedingFreq);
	diet.addSubdomain(dietComposition);
	
	var domains = [];
	domains.push(adaptationPeriod);
	domains.push(age);
	domains.push(animalFacility);
	domains.push(cage);
	//domains.push(bedding);
	//domains.push(enrichment);
	domains.push(lighting);
	domains.push(temperature);
	domains.push(diet);
	domains.push(exercise);
	domains.push(treatmentDuration);
	domains.push(mice);
	domains.push(surgery);
	domains.push(singleCompounds);
	domains.push(geneticManipulation);
	domains.push(ethics);
	domains.push(housingDensity);
	domains.push(miceInTreatment);
	domains.push(weight);
	
	
	for(var i = 0; i < domains.length; i++){
		initScope(domains[i]);
	}
	//weight.scope = 0;
		
	console.log(domains);
	return domains;
}

function initScope(domain){
	domain.scope = 0;
	for(var i = 0; i < domain.subDomains.length; i++){
		initScope(domain.subDomains[i]);
	}
}
