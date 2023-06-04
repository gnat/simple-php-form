📝 Simple PHP Form
==================

Automatic HTML forms with validation, helpers, warnings and more!

* Form fields: text fields, text areas, dropdowns, checkboxes, radio buttons and hidden fields.
* Validation flags: required, email, phone, number, lengthmax *, lengthmin *, sizemax *, sizemin *

```php
<?php 
  require('SimplePHPForm.php'); 
  
  $form = new SimplePHPForm();
  $form->add('name', 'text', '', array('required'), 'Name', '', 'Your name is required.');
  $form->add('email', 'text', '', array('required', 'email'), 'Email', '', 'Your email is required.');

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
    <link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_default.css" />
  </head>
  <body>
    <?php echo $form->display(); ?>
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
