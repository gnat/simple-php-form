**Simple PHP Form**

Open source automatic PHP form handling module with validation, helpers, warnings and more. Supports text fields, text areas, dropdowns, checkboxes, radio buttons and hidden fields.

Validation flags supported: required, email, phone, number, lengthmax *, lengthmin *, sizemax *, sizemin *

See ./examples/basic.php and ./examples/advanced.php and ./examples/centered.php for usage.

Copyright © Nathaniel Sabanski. Released under the BSD License.

SQL for creating the Advanced Example table:

``` CREATE TABLE `attendees` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8; ```