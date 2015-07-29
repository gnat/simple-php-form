<?php 
	require('../SimplePHPForm.class.php'); 
	
	$form = new SimplePHPForm();
	$form->Add('text', 'sirname', '', array('required'), 'Name', '', 'Your name is required.');
	$form->Add('text', 'email', '', array('required', 'email'), 'Email', '', 'Your email is required.');

	if($form->Validate()) // Did the form validate successfully?
	{
		// Success ! Send an email or register user in a database somewhere...
		$form->Reset(); // Reset to default form.
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
