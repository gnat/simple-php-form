<?php 
	// Simple PHP Form - Centered Example. 
	// Advanced example with center-aligned form.
	require('../SimplePHPForm.php'); 

	// Create new SimplePHPForm with custom action URL.
	$form = new SimplePHPForm('centered.php');

	// Add text inputs. (input type, name/id, default data, validation flags, label, helper message, validation warning message).
	$form->add('realname', 'text', 'Default text.', ['required'], 'Name', '', 'Your name is required.');
	$form->add('username', 'text', '', ['required'], 'Screen Name', '', 'Your screen name is required.');
	$form->add('email', 'text', '', ['required', 'email'], 'Email', '', 'Your email is required.');
	$form->add('phone', 'text', '', ['phone'], 'Phone Number', 'We\'ll send you a quick reminder the day before the event!', 'Your phone number must be valid.');

	// Add drop down list.
	$form->add('race', 'dropdown', '', ['required'], 'Your Race', '', 'Your selection is required.');
	$form->addDropdownEntry('race', 'Ready to roll out! (Terran)', 'terran');
	$form->addDropdownEntry('race', 'My life for Auir! (Protoss)', 'protoss');
	$form->addDropdownEntry('race', 'Here\'s for the swarm! (Zerg)', 'zerg');
	$form->addDropdownEntry('race', 'Ballin out of control! (Random)', 'random');
	
	// Add radio button list.
	$form->add('beverage', 'radio', '', ['required'], 'Preferred Beverage', '', 'Your selection is required.');
	$form->addRadioButton('beverage', 'Coffee', 0);
	$form->addRadioButton('beverage', 'Tea', 1);
	$form->addRadioButton('beverage', 'Bawls', 2);

	// Add text area.
	$form->add('suggestions', 'textarea', '', [''], 'Suggestion Box', 'Have your voice heard!', '');
	
	// Add check box.
	$form->add('notify', 'checkbox', true, [''], 'Notify me of future gaming events in my area.', '', '');

	// Did the form validate successfully?
	if($form->validate())
	{
		// Get data: $form->get('name'); ...
		// Place successful form submission code here ... (Send an email, register in a database, whatever ...)
		// Finally, reset the form, clearing it to the default state.
		$form->reset();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>SimplePHPForm Centered Example</title>
		<link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_default.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_center.css" />
	</head>
	<body>
		<div class="simplephpform_wrapper">
			<?php echo $form->display(); ?>
		</div>
	</body>
</html>
