Simple PHP Form
===============

Automatic PHP HTML Form generator class. Handles validation, helpers, warnings and more. Supports text fields, text areas, dropdowns, checkboxes, radio buttons and hidden fields.

Validation types supported: required, email, phone, number, lengthmax *, lengthmin *, sizemax *, sizemin *

```php
<?php 
  require('SimplePHPForm.class.php'); 
  
  $form = new SimplePHPForm();
  $form->Add('text', 'name', '', array('required'), 'Name', '', 'Your name is required.');
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
```

**Example Scripts**

<ul>
<li>Basic usage: <strong>./examples/basic.php</strong></li>
<li>Advanced usage: <strong>./examples/advanced.php</strong></li>
<li>Center-aligned usage: <strong>./examples/centered.php</strong></li>
</ul>

**Screenshot of Advanced Example**

<img src="http://i.imgur.com/nlanA5R.png" alt="Simple PHP Form Example 1" />

**SQL for creating the Advanced Example table:**

<pre><code> CREATE TABLE `attendees` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8; </code></pre>

Copyright © Nathaniel Sabanski. Released under the zlib/libpng license.



