<?php

use PHPUnit\Framework\TestCase;

class SimplePHPFormTest extends TestCase
{
	public function testInit()
	{
		$form = new SimplePHPForm("http://google.com");
		$this->assertStringContainsStringIgnoringCase('<form method="post" action="http://google.com" class="simplephpform">', $form->display());
	}

	public function testDisplayState()
	{
		$form = new SimplePHPForm();
		$this->assertStringContainsStringIgnoringCase('simplephpform_state_untouched', $form->displayState());
	}

	public function testAddText()
	{
		$form = new SimplePHPForm();
		$form->add('name', 'text', '', ['required'], 'Name', '', 'Your name is required.');
		$this->assertStringContainsStringIgnoringCase('Name', $form->display('name'));
	}

	public function testAddDropdown()
	{
		$form = new SimplePHPForm();
		$form->add('race', 'dropdown', '', ['required'], 'Your Race', '', 'Your selection is required.');
		$form->addDropdownEntry('race', 'Ready to roll out! (Terran)', 'terran');
		$form->addDropdownEntry('race', 'My life for Auir! (Protoss)', 'protoss');
		$form->addDropdownEntry('race', 'Heres for the swarm! (Zerg)', 'zerg');
		$form->addDropdownEntry('race', 'Ballin out of control! (Random)', 'random');
		$this->assertStringContainsStringIgnoringCase('<select name="simplephpform_race">', $form->display());
		$this->assertStringContainsStringIgnoringCase('<option value="terran">Ready to roll out! (Terran)</option>', $form->display());
	}

	public function testAddRadioButton()
	{
		$form = new SimplePHPForm();
		$form->add('beverage', 'radio', '', ['required'], 'Preferred Beverage', '', 'Your selection is required.');
		$form->addRadioButton('beverage', 'Coffee', 0);
		$form->addRadioButton('beverage', 'Tea', 1);
		$form->addRadioButton('beverage', 'Bawls', 2);
		$this->assertStringContainsStringIgnoringCase('<label><input type="radio" name="simplephpform_beverage" value="1" > Tea</label>', $form->display());
	}

	public function testAddTextArea()
	{
		$form = new SimplePHPForm();
		$form->add('suggestions', 'textarea', '', [''], 'Suggestion Box', 'Have your voice heard!', '');
		$this->assertStringContainsStringIgnoringCase('<textarea name="simplephpform_suggestions"', $form->display());
	}

	public function testAddCheckbox()
	{
		$form = new SimplePHPForm();
		$form->add('notify', 'checkbox', true, [''], 'Notify me of future events in my area.', '', '');
		$this->assertStringContainsStringIgnoringCase('<input type="checkbox" name="simplephpform_notify"', $form->display());
	}

	public function testValidateExists()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->validateExists("Hello World"));
		$this->assertFalse($form->validateExists(null));
	}

	public function testValidateEmail()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->validateEmail("billyg@microsoft.com"));
		$this->assertTrue($form->validateEmail("blah__@google.co.uk"));
		$this->assertFalse($form->validateEmail("blah__2"));
		$this->assertFalse($form->validateEmail("@bA84.com"));
	}

	public function testValidatePhone()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->validatePhone("123 123 4568"));
		$this->assertTrue($form->validatePhone("1(299)A 222 2222"));
		$this->assertFalse($form->validatePhone("99999999999999999999999999999999"));
		$this->assertFalse($form->validatePhone("Blah blah"));
	}

	public function testValidateNumber()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->validateNumber(12315));
		$this->assertTrue($form->validateNumber("-2393939"));
		$this->assertFalse($form->validateNumber("0000bbbb"));
		$this->assertFalse($form->validateNumber("Blah blah"));
	}

	public function testValidateLengthMax()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->validateLengthMax("a992a92a", 10));
		$this->assertTrue($form->validateLengthMax(32352, 10));
		$this->assertFalse($form->validateLengthMax("asdasd", 1));
		$this->assertFalse($form->validateLengthMax("asd243asd", 6));
	}

	public function testValidateLengthMin()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->validateLengthMin("a992a92a", -50));
		$this->assertTrue($form->validateLengthMin(32352, 4));
		$this->assertFalse($form->validateLengthMin("asdasd", 10));
		$this->assertFalse($form->validateLengthMin("asd243asd", 9999));
	}

	public function testValidateSizeMax()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->validateSizeMax(20, 20));
		$this->assertTrue($form->validateSizeMax(1, 50));
		$this->assertFalse($form->validateSizeMax(30, 1));
	}

	public function testValidateSizeMin()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->validateSizeMin(20, 20));
		$this->assertTrue($form->validateSizeMin(1, -50));
		$this->assertFalse($form->validateSizeMin(0, 1));
	}
}
