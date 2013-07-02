<?php

/*
	Simple PHP Form
	
	Open source automatic PHP form handling module with validation, helpers, warnings and more. 
	Supports text fields, text areas, dropdowns, checkboxes, radio buttons and hidden fields.

	Validation flags supported: required, email, phone, number, lengthmax *, lengthmin *, sizemax *, sizemin *

	See ./examples/basic.php and ./examples/advanced.php and ./examples/centered.php for usage.

	Copyright © Nathaniel Sabanski. Released under the BSD License.

	Author Home: http://geenat.com
	Source: http://github.com/gnat/simple-php-form
*/

class SimplePHPForm
{
	const STATE_NEW = 0;
	const STATE_SUCCESS = 1;
	const STATE_VALIDATE = 2;
	const STATE_FAIL = 3;
	const STATE_ERROR = 4;
	const STATE_DUPLICATE = 5;
	
	var $state = 0;
	var $input_list = array();

	var $url_action = '';

	var $message_new = 'Registration Form';
	var $message_success = 'Form submitted successfully!';
	var $message_success_2 ='You should receive a confirmation email shortly!';
	var $message_fail = 'Oops! We had trouble accepting your form. Details below.';
	var $message_error = 'You have discovered an internal error. Please contact us!';
	var $message_duplicate = 'You are already registered. If there is an issue, please contact us.';
	
	function SimplePHPForm($url_action = '')
	{
		// Set custom <form> action.
		$this->url_action = $url_action;
	}

	function Add($type, $name, $data, $data_validation_flags, $text_title, $text_help, $text_error)
	{
		// Set default data.
		$data_default = $data;

		// Get form submission data from $_POST if it exists.
		if(isset($_POST['simplephpform_'.$name]))  
		{
			$data = $_POST['simplephpform_'.$name]; 
			$this->state = self::STATE_VALIDATE; // We've got data during this pass. Form should be validated.
		}

		$this->input_list[$name] = new SimplePHPFormInput($type, $name, $data, $data_validation_flags, $data_default, $text_title, $text_help, $text_error);
	
		// Special logic for checkbox types because browsers simply do not $_POST them if they are unchecked.
		if($type == 'checkbox' && $this->state != self::STATE_NEW)
		{
			if(isset($_POST['simplephpform_'.$name]))
				$this->input_list[$name]->data = true;
			else
				$this->input_list[$name]->data = false;
		}
	}

	function AddDropdownEntry($name, $data, $value)
	{
		$this->input_list[$name]->dropdown_entries[$value] = $data;
	}

	function AddRadioButton($name, $value, $data)
	{
		$this->input_list[$name]->radio_entries[$value] = $data;
	}

	function DisplayState()
	{
		$output = '';

		if($this->state == self::STATE_NEW) {
			$output = '<div class="simplephpform_state_untouched">'.$this->message_new.'</div>';
		} if($this->state == self::STATE_SUCCESS) {
			$output = '<div class="simplephpform_state_success">'.$this->message_success.'</div> <p>'.$this->message_success_2.'</p> <br />';
		} if($this->state == self::STATE_FAIL) {
			$output = '<div class="simplephpform_state_fail">'.$this->message_fail.'</div>';			
		} if($this->state == self::STATE_ERROR) {
			$output = '<div class="simplephpform_state_fail">'.$this->message_error.'</div>';
		} if($this->state == self::STATE_DUPLICATE) {
			$output = '<div class="simplephpform_state_success">'.$this->message_duplicate.'</div>';
		}

		return $output."\n";
	}

	function Display($name = '')
	{
		// No InputEntry specified? Return them all in the order they were defined.
		if($name == '')
		{
			$output = '';
			
			$output .= $this->DisplayState();
			$output .= '<form method="post" action="'.$this->url_action.'" class="simplephpform">';
			foreach($this->input_list as $input)
				$output .= $this->Display($input->name)."\n";
			$output .= '<input type="submit" value="Submit Form" class="simplephpform_submit" />';
			$output .= '</form>';
			
			return $output;
		}

		// Generate output if the specified Form Input exists.
		if(array_key_exists($name, $this->input_list))
		{
			$output = '';
			$type = strtolower(trim($this->input_list[$name]->type));
			
			if($type == 'textarea') // Text area.
			{
				$output .= '<div class="simplephpform_title">'.$this->input_list[$name]->text_title.'</div>'."\n";
				$output .= '<textarea name="simplephpform_'.$this->input_list[$name]->name.'" rows="'.$this->input_list[$name]->rows.'" cols="'.$this->input_list[$name]->columns.'">'.$this->input_list[$name]->data.'</textarea>'."\n";
				
				// Helper or error message?
				if($this->input_list[$name]->state != self::STATE_FAIL && $this->input_list[$name]->text_error != NULL)
					$output .= '<div class="simplephpform_error">'.$this->input_list[$name]->text_error.'</div>'."\n";
				else if($this->input_list[$name]->text_help != NULL)
					$output .= '<div class="simplephpform_help">'.$this->input_list[$name]->text_help.'</div>'."\n";
			}
			else if($type == 'dropdown') // Drop down menu.
			{
				$output .= '<div class="simplephpform_title">'.$this->input_list[$name]->text_title.'</div>'."\n";
				$output .= '<select name="simplephpform_'.$this->input_list[$name]->name.'">'."\n";
				
				foreach($this->input_list[$name]->dropdown_entries as $drop_down_value => $drop_down_name)
				{
					if($this->input_list[$name]->data == $drop_down_value)
						$output .= '<option value="'.$drop_down_value.'" selected="selected">'.$drop_down_name.'</option>'."\n";
					else
						$output .= '<option value="'.$drop_down_value.'">'.$drop_down_name.'</option>'."\n";
				}
					
				$output .= '</select>'."\n";
				
				// Helper or error message?
				if($this->input_list[$name]->state == self::STATE_FAIL)
					$output .= '<div class="simplephpform_error">'.$this->input_list[$name]->text_error.'</div>'."\n";
				else if($this->input_list[$name]->text_help != NULL)
					$output .= '<div class="simplephpform_help">'.$this->input_list[$name]->text_help.'</div>'."\n";
			}
			else if($type == 'radio') // Radio button.
			{
				$output .= '<div class="simplephpform_title">'.$this->input_list[$name]->text_title.'</div><div class="simplephpform_radiobox">'."\n";
				
				foreach($this->input_list[$name]->radio_entries as $radio_name => $radio_value)
				{
					if($this->input_list[$name]->data == $radio_value)
						$output .= '<label><input type="radio" name="simplephpform_'.$this->input_list[$name]->name.'" value="'.$radio_value.'" checked="checked" > '.$radio_name."</label></input>\n";
					else
						$output .= '<label><input type="radio" name="simplephpform_'.$this->input_list[$name]->name.'" value="'.$radio_value.'" > '.$radio_name."</label></input>\n";
				}

				$output .= '</div>';

				// Helper or error message?
				if($this->input_list[$name]->state == self::STATE_FAIL)
					$output .= '<div class="simplephpform_error">'.$this->input_list[$name]->text_error.'</div>'."\n";
				else if($this->input_list[$name]->text_help != NULL)
					$output .= '<div class="simplephpform_help">'.$this->input_list[$name]->text_help.'</div>'."\n";
			}
			else if($type == 'checkbox') // Check box. Never needs an error message. Will never need an error or info message.
			{
				$output .= '<div class="simplephpform_title"></div><div style="float: left; margin-bottom: 2px;">'."\n";

				if(boolval($this->input_list[$name]->data))
					$output .= '<label><input type="'.$this->input_list[$name]->type.'" name="simplephpform_'.$this->input_list[$name]->name.'" checked="checked" />'.$this->input_list[$name]->text_title."</label>\n";
				else
					$output .= '<label><input type="'.$this->input_list[$name]->type.'" name="simplephpform_'.$this->input_list[$name]->name.'" />'.$this->input_list[$name]->text_title."</label>\n";
				
				$output .= '</div>';
			}
			else if($type == 'hidden') // Hidden type, for metadata, etc.
			{
				$output .= '<input type="'.$this->input_list[$name]->type.'" name="simplephpform_'.$this->input_list[$name]->name.'" value="'.$this->input_list[$name]->data.'" />'."\n";
			}
			else // Default. Textbox, password, etc.
			{
				$output .= '<div class="simplephpform_title">'.$this->input_list[$name]->text_title.'</div>'."\n";

				$output .= '<label><input type="'.$this->input_list[$name]->type.'" name="simplephpform_'.$this->input_list[$name]->name.'" value="'.$this->input_list[$name]->data.'" />'."\n";
			
				if($this->input_list[$name]->state == self::STATE_FAIL)
					$output .= '<div class="simplephpform_error">'.$this->input_list[$name]->text_error.'</div>'."\n";
				else if($this->input_list[$name]->text_help != NULL)
					$output .= '<div class="simplephpform_help">'.$this->input_list[$name]->text_help.'</div>'."\n";

				$output .= '</label>';
			}	

			$output .= '<div class="simplephpform_clear"></div>';
			
			return $output;
		}
	}

	function Reset()
	{
		foreach($this->input_list as $input)
			$input->data = $input->data_default;
	}

	function Validate()
	{
		// Was this form submitted? Or is this page new?
		if($this->state == self::STATE_NEW)
			return false; // Invalid by default.
		
		// Set state as successfull first, then run validation test gauntlet ...
		$this->state = self::STATE_SUCCESS;

		foreach($this->input_list as $input)
		{
			// Set individual input entry state successful at first, then run validation test gauntlet ...
			$input->state = self::STATE_SUCCESS;

			// What validation tests need to be run?
			for($i = 0; $i < count($input->data_validation_flags); $i += 1)
				if(!empty($input->data_validation_flags[$i]))
				{
					// Sanitize flag by stripping whitespace, and making lowercase.
					$flag = strtolower(trim($input->data_validation_flags[$i])); 
					
					// *** If we have a test for this flag, run it! ***
					
					// Test: Is the entry required?
					if($flag == 'required')
						if(!$this->ValidateExists($input->data))
							$input->state = self::STATE_FAIL;
							
					// Test: Is the entry an email?
					if($flag == 'email')
						if(!$this->ValidateEmail($input->data))
							$input->state = self::STATE_FAIL;
							
					// Test: Is the entry a phone number?
					if($flag == 'phone')
						if(!$this->ValidatePhone($input->data))
							$input->state = self::STATE_FAIL;

					// Test: Is the entry a number?
					if($flag == 'number')
						if(!$this->ValidateNumber($input->data))
							$input->state = self::STATE_FAIL;

					// Process multi-part flags.
					$flag_parts = explode(' ', $flag);
					
					// Test: Is there a max string length?
					if($flag_parts[0] == 'lengthmax')
						if(isset($flag_parts[1]))
							if(!$this->ValidateLengthMax($input->data, $flag_parts[1]))
								$input->state = self::STATE_FAIL;
					
					// Test: Is there a min string length?
					if($flag_parts[0] == 'lengthmin')
						if(isset($flag_parts[1]))
							if(!$this->ValidateLengthMin($input->data, $flag_parts[1]))
								$input->state = self::STATE_FAIL;

					// Test: Is there a max number size?
					if($flag_parts[0] == 'sizemax')
						if(isset($flag_parts[1]))
							if(!$this->ValidateSizeMax($input->data, $flag_parts[1]))
								$input->state = self::STATE_FAIL;
					
					// Test: Is there a min number size?
					if($flag_parts[0] == 'sizemin')
						if(isset($flag_parts[1]))
							if(!$this->ValidateSizeMin($input->data, $flag_parts[1]))
								$input->state = self::STATE_FAIL;	

				}
		}
		
		// Did ALL individual input entries validate successfully? If no, set form state to fail.
		foreach($this->input_list as $input)
			if($input->state == self::STATE_FAIL)
				$this->state = self::STATE_FAIL;
				
		// No input entries? Also fail.
		if(count($this->input_list) < 1)
			$this->state = self::STATE_FAIL;
	
		if($this->state == self::STATE_SUCCESS)
			return true;
		else
			return false;
	}

	function ValidateExists($data)
	{
		if($data != '')
			return true;
		else
			return false;
	}

	function ValidateEmail($data)
	{
		if(strlen($data) < 5 || strpos($data, '@') == false || strpos($data, '.') == false || stripos($data, ' ') != false)
			return false;
		else
			return true;
	}

	function ValidatePhone($data)
	{
		if(!intval($data) || strlen($data) < 10 || strlen($data) > 30)
			return false;
		else
			return true;
	}

	function ValidateNumber($data)
	{
		if(is_numeric($data))
			return true;
		else
			return false;
	}

	function ValidateLengthMax($data, $size)
	{
		if(strlen($data) > $size)
			return false;
		else
			return true;
	}

	function ValidateLengthMin($data, $size)
	{
		if(strlen($data) < $size)
			return false;
		else
			return true;
	}

	function ValidateSizeMax($data, $size)
	{
		if(is_numeric($data))
			if($data > $size)
				return false;

		return true;
	}

	function ValidateSizeMin($data, $size)
	{
		if(is_numeric($data))
			if($data < $size)
				return false;

		return true;
	}
}

class SimplePHPFormInput
{
	var $type = 'text';
	var $name = NULL;
	var $data = '';
	var $data_default = '';
	var $data_validation_flags = array();
	var $state = SimplePHPForm::STATE_NEW;
	
	var $text_title = '';
	var $text_help = '';
	var $text_error = '';
	
	// Special variables used for specific input types.
	var $rows = 3;
	var $columns = 30;
	var $dropdown_entries = array();
	var $radio_entries = array();
	
	function __construct($type, $name, $data, $data_validation_flags, $data_default, $text_title, $text_help, $text_error)
	{
		$this->type = $type;
		$this->name = $name;
		$this->data = $data;
		$this->data_default = $data_default;
		$this->data_validation_flags = $data_validation_flags;
		
		$this->text_title = $text_title;
		$this->text_help = $text_help;
		$this->text_error = $text_error;

		// If checkbox, $data needs to be true or false.
		if($type == 'checkbox')
		{
			$this->data = boolval($data);
			$this->data_default = boolval($data_default);
		}
	}
}

// For PHP < 5.5.0
if (!function_exists('boolval')) 
{
	function boolval($in) 
	{
		$out = false;
		
		if(is_string($in))
			$in = strtolower($in);
		
		if (in_array($in, array('false', 'no', 'n', '0', 'off', false, 0), true) || !$in)
			$out = false;
		    
		if (in_array($in, array('true', 'yes', 'y', '1', 'on', true, 1), true))
			$out = true;

		return $out;
	}
}
