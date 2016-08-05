<?php 

	// Simple PHP Form - Advanced Example. 
	// Full featured example showing off many configuration options and form submission code sample.
	require('../SimplePHPForm.php'); 
	
	// Create new SimplePHPForm with custom action URL.
	$form = new SimplePHPForm('advanced.php');

	// Custom status messages.
	$form->message_new = 'Event Registration';
	$form->message_success = 'Form submitted successfully!';
	$form->message_success_2 = 'You should receive a confirmation email shortly!';
	$form->message_fail = 'Oops! We had trouble accepting your form. Details below.';
	$form->message_error = 'You have discovered an internal error. Please contact us!';

	// Add text inputs. (input type, name/id, default data, validation flags, label, helper message, validation warning message).
	$form->Add('text', 'realname', 'Default text.', array('required'), 'Name', '', 'Your name is required.');
	$form->Add('text', 'username', '', array('required', 'lengthmin 2'), 'Screen Name', '', 'Your screen name is required.');
	$form->Add('text', 'email', '', array('required', 'email'), 'Email', '', 'Your email is required.');
	$form->Add('text', 'phone', '', array('phone'), 'Phone Number', 'We\'ll send you a quick reminder the day before the event!', 'Your phone number must be valid.');
	
	// Add text area.
	$form->Add('textarea', 'suggestions', '', array(''), 'Suggestion Box', 'Have your voice heard!', '');
	
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

	// Add check box.
	$form->Add('checkbox', 'notify', true, array(''), 'Notify me of future gaming events in my area.', '', '');

	// Did the form validate successfully?
	if($form->Validate())
	{
		// Place successful form submission code here. This example: 
		// 1. Checks for email duplicate in database.
		// 2. Stores new attendee in your database.
		// 3. Sends a email notification.

		// Want to create the table yourself? Use this code.

		/*
			CREATE TABLE `attendees` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `realname` varchar(45) NOT NULL,
			  `username` varchar(45) NOT NULL,
			  `email` varchar(45) NOT NULL,
			  `phone` varchar(45) NOT NULL,
			  `race` varchar(45) NOT NULL,
			  `beverage` int(10) unsigned NOT NULL,
			  `suggestions` text NOT NULL,
			  `notify` tinyint(1) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		*/
		
		define('DB_HOST', 'localhost'); // Usually localhost.
		define('DB_HOST_PORT', 3306); // Default port is 3306.
		define('DB_NAME', 'website'); // Database name.
		define('DB_TABLE', 'attendees'); // Table name.
		define('DB_USER', 'root');
		define('DB_PASSWORD', 'password');

		define('MAIL_ACTIVE', false);
		define('MAIL_SMTP', 'mail.yoursite.com'); // Mail server.
		define('MAIL_FROM', 'contact@yoursite.com');
		define('MAIL_SUBJECT', "Registration Confirmation");
		define('MAIL_CONTENT', "Thank you for registering!  We look forward to seeing you on Friday, June 21st, 2013!");

		// Database has not been configured, just move on as example.
		if(DB_PASSWORD == 'password')
			$form->message_success_2 = 'However nothing will happen because your database has not been configured.';
		else
		{
			try 
			{
				// Connect to Database.
				$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_HOST_PORT, DB_USER, DB_PASSWORD, array( PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING ));
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

				// Is this email already registered?
				$query = $dbh->prepare("SELECT email FROM attendees WHERE email=:email LIMIT 1");
				$query->bindParam(':email', $form->input_list['email']->data, PDO::PARAM_STR, 45);
				$query->execute();

				if(count($query->fetchAll()) > 0)
					$form->state = SimplePHPForm::STATE_DUPLICATE;
				else
				{
					// Insert new user into database.
					$query = $dbh->prepare("INSERT INTO ".DB_TABLE."(realname,username,email,phone,race,beverage,suggestions,notify) VALUES (:realname,:username,:email,:phone,:race,:beverage,:suggestions,:notify) ON DUPLICATE KEY UPDATE realname=:realname,username=:username,email=:email,phone=:phone,race=:race,beverage=:beverage,suggestions=:suggestions,notify=:notify");
					$query->bindParam(':realname', $form->input_list['realname']->data, PDO::PARAM_STR, 45);
					$query->bindParam(':username', $form->input_list['username']->data, PDO::PARAM_STR, 45);
					$query->bindParam(':email', $form->input_list['email']->data, PDO::PARAM_STR, 45);
					$query->bindParam(':phone', $form->input_list['phone']->data, PDO::PARAM_STR, 45);
					$query->bindParam(':race', $form->input_list['race']->data, PDO::PARAM_STR, 45);
					$query->bindParam(':beverage', $form->input_list['beverage']->data, PDO::PARAM_INT);
					$query->bindParam(':suggestions', $form->input_list['suggestions']->data, PDO::PARAM_STR, 1000);
					$query->bindParam(':notify', $form->input_list['notify']->data, PDO::PARAM_BOOL);
					$query->execute();
				
					// Send a confirmation email!
					if(MAIL_ACTIVE)
					{
						ini_set("SMTP", MAIL_SMTP);
						ini_set("sendmail_from", MAIL_FROM);

						$header = "From: ".MAIL_FROM."\r\n"."Reply-To: ".MAIL_FROM."\r\n";
						
						mail($form->input_list['email']->data, MAIL_SUBJECT, MAIL_CONTENT, $header);
					}
				}

				$dbh = NULL; // Disconnect database.
			}
			catch(PDOException $e)
			{
				die('You have discovered an internal error. Please contact us!');
			}
		}

		// Finally, reset the form, clearing it to the default state.
		$form->Reset();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SimplePHPForm Advanced Example</title>
		<link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_default.css" />
    </head>
	<body>
		<?php echo $form->DisplayState(); ?>
		<form method="post" action="advanced.php" class="simplephpform">
			<?php 
				// Display each field individually. You can alternatively display them all as a full form with $form->Display();
				echo $form->Display('realname'); 
				echo $form->Display('username');
				echo $form->Display('email');
				echo $form->Display('phone');
				echo $form->Display('race');
				echo $form->Display('beverage');
				echo $form->Display('suggestions');
				echo $form->Display('notify');
			?>
			<input type="submit" value="Submit Form" class="simplephpform_submit" />
		</form>
	</body>
</html>
