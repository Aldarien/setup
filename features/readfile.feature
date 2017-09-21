Feature: Read setup files
	In order to create the database table
	As a user
	Setup needs to know which models are wanted in the database
	
	Scenario: No setup files detected
		Given the setup directory
		When no setup files are detected
		Then do nothing
	
	Scenario: Setup files are detected
		Given the setup directory
		When one or more files are detected
		And the data is correct
		Then read them
		
	Scenario: Setup files incorrectly created
		Given the setup directory
		When one or more files are detected
		And the data is not correct
		Then throw Exception