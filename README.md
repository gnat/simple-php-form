📝 Simple PHP Form
==================

Automatic HTML forms with validation, helpers, warnings and more!

* Form fields: text fields, text areas, dropdowns, checkboxes, radio buttons and hidden fields.
* Validation flags: required, email, phone, number, lengthmax *, lengthmin *, sizemax *, sizemin *

```php
<?php 
  require('SimplePHPForm.php'); 
  
  $form = new SimplePHPForm();
  $form->Add('name', 'text', '', array('required'), 'Name', '', 'Your name is required.');
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

**Screenshot of Example**

<img src="http://i.imgur.com/PNtyxTl.png" alt="Simple PHP Form Example 1" />

## Keywords

Forms, User Feedback, Model View Controller, PHP 8 Compatible, PHP 8+, email, input, simple, lean.
