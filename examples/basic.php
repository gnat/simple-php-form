<?php 
	require('../SimplePHPForm.php'); 
	
	$form = new SimplePHPForm();
	$form->Add('surname', 'text', '', array('required'), 'Name', '', 'Your name is required.');
	$form->Add('email', 'text', '', array('required', 'email'), 'Email', '', 'Your email is required.');

	if($form->Validate()) // Did the form validate successfully?
	{
		// Get data: $form->Get('name'); ...
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
