<?php

use PHPUnit\Framework\TestCase;

class SimplePHPFormTest extends TestCase
{
	public function testInit()
	{
		$form = new SimplePHPForm("http://google.com");
		$this->assertContains('<form method="post" action="http://google.com" class="simplephpform">', $form->Display());
	}

	public function testDisplayState()
	{
		$form = new SimplePHPForm();
		$this->assertContains('simplephpform_state_untouched', $form->DisplayState());
	}

	public function testAddText()
	{
		$form = new SimplePHPForm();
		$form->Add('name', 'text', '', array('required'), 'Name', '', 'Your name is required.');
		$this->assertContains('Name', $form->Display('name'));
	}

	public function testAddDropdown()
	{
		$form = new SimplePHPForm();
		$form->Add('race', 'dropdown', '', array('required'), 'Your Race', '', 'Your selection is required.');
		$form->AddDropdownEntry('race', 'Ready to roll out! (Terran)', 'terran');
		$form->AddDropdownEntry('race', 'My life for Auir! (Protoss)', 'protoss');
		$form->AddDropdownEntry('race', 'Heres for the swarm! (Zerg)', 'zerg');
		$form->AddDropdownEntry('race', 'Ballin out of control! (Random)', 'random');
		$this->assertContains('<select name="simplephpform_race">', $form->Display());
		$this->assertContains('<option value="terran">Ready to roll out! (Terran)</option>', $form->Display());
	}

	public function testAddRadioButton()
	{
		$form = new SimplePHPForm();
		$form->Add('beverage', 'radio', '', array('required'), 'Preferred Beverage', '', 'Your selection is required.');
		$form->AddRadioButton('beverage', 'Coffee', 0);
		$form->AddRadioButton('beverage', 'Tea', 1);
		$form->AddRadioButton('beverage', 'Bawls', 2);
		$this->assertContains('<label><input type="radio" name="simplephpform_beverage" value="1" > Tea</label>', $form->Display());
	}

	public function testAddTextArea()
	{
		$form = new SimplePHPForm();
		$form->Add('suggestions', 'textarea', '', array(''), 'Suggestion Box', 'Have your voice heard!', '');
		$this->assertContains('<textarea name="simplephpform_suggestions"', $form->Display());
	}

	public function testAddCheckbox()
	{
		$form = new SimplePHPForm();
		$form->Add('notify', 'checkbox', true, array(''), 'Notify me of future events in my area.', '', '');
		$this->assertContains('<input type="checkbox" name="simplephpform_notify"', $form->Display());
	}

	public function testValidateExists()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->ValidateExists("Hello World"));
		$this->assertFalse($form->ValidateExists(null));
	}

	public function testValidateEmail()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->ValidateEmail("billyg@microsoft.com"));
		$this->assertTrue($form->ValidateEmail("blah__@google.co.uk"));
		$this->assertFalse($form->ValidateEmail("blah__2"));
		$this->assertFalse($form->ValidateEmail("@bA84.com"));
	}

	public function testValidatePhone()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->ValidatePhone("123 123 4568"));
		$this->assertTrue($form->ValidatePhone("1(299)A 222 2222"));
		$this->assertFalse($form->ValidatePhone("99999999999999999999999999999999"));
		$this->assertFalse($form->ValidatePhone("Blah blah"));
	}

	public function testValidateNumber()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->ValidateNumber(12315));
		$this->assertTrue($form->ValidateNumber("-2393939"));
		$this->assertFalse($form->ValidateNumber("0000bbbb"));
		$this->assertFalse($form->ValidateNumber("Blah blah"));
	}

	public function testValidateLengthMax()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->ValidateLengthMax("a992a92a", 10));
		$this->assertTrue($form->ValidateLengthMax(32352, 10));
		$this->assertFalse($form->ValidateLengthMax("asdasd", 1));
		$this->assertFalse($form->ValidateLengthMax("asd243asd", 6));
	}

	public function testValidateLengthMin()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->ValidateLengthMin("a992a92a", -50));
		$this->assertTrue($form->ValidateLengthMin(32352, 4));
		$this->assertFalse($form->ValidateLengthMin("asdasd", 10));
		$this->assertFalse($form->ValidateLengthMin("asd243asd", 9999));
	}

	public function testValidateSizeMax()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->ValidateSizeMax(20, 20));
		$this->assertTrue($form->ValidateSizeMax(1, 50));
		$this->assertFalse($form->ValidateSizeMax(30, 1));
	}

	public function testValidateSizeMin()
	{
		$form = new SimplePHPForm();
		$this->assertTrue($form->ValidateSizeMin(20, 20));
		$this->assertTrue($form->ValidateSizeMin(1, -50));
		$this->assertFalse($form->ValidateSizeMin(0, 1));
	}
}
