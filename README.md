📝 Simple PHP Form
==================
![workflow](https://github.com/gnat/simple-php-form/actions/workflows/test.yml/badge.svg)

Automatic HTML `<form>` with validation, messages, warnings and more!

* Form fields: `text`, `textarea`, `dropdown`, `checkbox`, `radio` and `hidden`.
* Validators: `required`, `email`, `phone`, `number`, `lengthmax *`, `lengthmin *`, `sizemax *`, `sizemin *`

```php
<?php 
  require('SimplePHPForm.php'); 
  
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
    <link rel="stylesheet" type="text/css" media="screen" href="css/simplephpform_default.css" />
  </head>
  <body>
    <?php echo $form->display(); ?>
  </body>
</html> 
```

## 🎁 Installation

This is a zero dependency library. Just drag `SimplePHPForm.php` into your project and `require('SimplePHPForm.php');`. 

Optionally add the assets from `examples/css` and `examples/images`

## 👁️ Examples + Screenshot

* Basic usage: **./examples/basic.php**
* Advanced usage: **./examples/advanced.php**
* Center-aligned usage: **./examples/centered.php**

<img src="http://i.imgur.com/PNtyxTl.png" alt="Simple PHP Form Example 1" />

## Keywords

Forms, User Feedback, Model View Controller, PHP 8 Compatible, PHP 8+, email, input, simple, lean.
