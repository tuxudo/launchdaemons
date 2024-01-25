<?php
return [
	/*
	|===============================================
	| LaunchDaemons - label ignore list
	|===============================================
	|
	| List of launchdaemon to be ignored when processing daemons
	| The list is processed using regex, examples:
	|
	| For example to skip all Apple daemons and agents
	| 'launchdaemon_ignorelist' = ['com.apple.*'];
	|
	| To ignore daemons in the /Users/ folder set
	| 'user_agents' to FALSE
	|
	*/
	'launchdaemon_ignorelist' => env('LAUNCHDAEMON_IGNORELIST'),
	'user_agents' => env('USER_AGENTS', true),
];