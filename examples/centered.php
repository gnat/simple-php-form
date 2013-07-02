<?php 
	// Simple PHP Form - Centered Example. Example of a center-aligned form. 
	require('../SimplePHPForm.class.php'); 

	// Create new SimplePHPForm with custom action URL.
	$form = new SimplePHPForm('centered.php');

	// Add text inputs. (input type, name/id, default data, validation flags, label, helper message, validation warning message).
	$form->Add('text', 'realname', 'Default text.', array('required'), 'Name', '', 'Your name is required.');
	$form->Add('text', 'username', '', array('required'), 'Screen Name', '', 'Your screen name is required.');
	$form->Add('text', 'email', '', array('required', 'email'), 'Email', '', 'Your email is required.');
	$form->Add('text', 'phone', '', array('phone'), 'Phone Number', 'We\'ll send you a quick reminder the day before the event!', 'Your phone number must be valid.');

	// Add drop down list.
	$form->Add('dropdown', 'race', '', array('required'), 'Your Race', '', 'Your selection is required.');
	$form->AddDropdownEntry('race', 'Ready to roll out! (Terran)', 'terran');
	$form->AddDropdownEntry('race', 'My life for Auir! (Protoss)', 'protoss');
	$form->AddDropdownEntry('race', 'Here\'s for the swarm! (Zerg)', 'zerg');
	$form->AddDropdownEntry('race', 'Ballin out of control! (Random)', 'random');
	
	// Add radio button list.
	$form->Add('radio', 'beverage', '', array('required'), 'Preferred Beverage', '', 'Your selection is required.');
	$form->AddRadioButton('beverage', 'Coffee', 0);
	$form->AddRadioButton('beverage', 'Tea', 1);
	$form->AddRadioButton('beverage', 'Bawls', 2);

	// Add text area.
	$form->Add('textarea', 'suggestions', '', array(''), 'Suggestion Box', 'Have your voice heard!', '');
	
	// Add check box.
	$form->Add('checkbox', 'notify', true, array(''), 'Notify me of future gaming events in my area.', '', '');

	// Did the form validate successfully?
	if($form->Validate())
	{
		// Place successful form submission code here ...

		// Finally, reset the form, clearing it to the default state.
		$form->Reset();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SimplePHPForm Centered Example</title>
		<link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_default.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_center.css" />
    </head>
	<body>
		<div class="simplephpform_wrapper">
			<?php echo $form->Display(); ?>
		</div>
	</body>
</html>
