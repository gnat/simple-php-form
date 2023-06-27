<?php 
	require('../SimplePHPForm.php'); 
	
	$form = new SimplePHPForm();
	$form->add('name', 'text', '', ['required'], 'Name', '', 'Your name is required.');
	$form->add('email', 'text', '', ['required', 'email'], 'Email', '', 'Your email is required.');

	if($form->validate()) // Did the form validate successfully?
	{
		// Get data: $form->get('name'); ...
		// Success ! Send an email or register user in a database somewhere...
		$form->reset(); // Reset to default form.
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>SimplePHPForm Basic Example</title>
		<link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_default.css" />
	</head>
	<body>
		<?php echo $form->display(); ?>
	</body>
</html>	
