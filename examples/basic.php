<?php 
	// Simple PHP Form - Basic Example. The minimum amount of code to get running. 
	require('../SimplePHPForm.php'); 
	
	$form = new SimplePHPForm();

	// Add Inputs. (input type, name/id, default data, validation flags, label, helper message, validation warning message).
	$form->Add('text', 'sirname', '', array('required'), 'Name', '', 'Your name is required.');
	$form->Add('text', 'email', '', array('required', 'email'), 'Email', '', 'Your email is required.');

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
		<title>SimplePHPForm Basic Example</title>
		<link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_default.css" />
    </head>
	<body>
		<?php echo $form->Display(); ?>
	</body>
</html>	
