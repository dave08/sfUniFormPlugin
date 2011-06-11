# Usage

Use this plugin by first initializing the scripts used:

	include_uniform();

Then by inserting this to display the fields:

	echo uniform_render($form, array(
		'type',
		'=Materials' => array(
			'medium_id','surface_id'
		),
		'=Dimensions' => array(
			'width','height'
		),
		'price',
		'status'
	));

The sub arrays are for multi field sections and an '=' sign indicates if you want to use the alternate layout for those fields.

Alternatively the UniForm class could be used.

This plugin has been locally unit tested for most of what it does using the sfPhpUnit plugin and my own classes for db inclusion, so I just included the tests to see the functionality for now, maybe I'll be including a fork to sfPhpUnitPlugin and some proper tests w/ less external dependencies.

# TODO

* Support automatic indication of required values "<em>*</em>"
* Support data-default-value="Placeholder text"
? size="35" maxlength="50" are used specially in the framework?
* Support different text field sizes
* %help% %error% and %hiddenfields% support in multi field row