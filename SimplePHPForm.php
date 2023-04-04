<?php
/**
* Simple PHP Form
*
* Automatic form handling with validation, helpers, warnings and more. 
* Form fields: text fields, text areas, dropdowns, checkboxes, radio buttons and hidden fields.
* Validation flags: required, email, phone, number, lengthmax *, lengthmin *, sizemax *, sizemin *
* 
* See ./examples/basic.php and ./examples/advanced.php and ./examples/centered.php for usage.
*/
class SimplePHPForm
{
	const STATE_NEW = 0;
	const STATE_SUCCESS = 1;
	const STATE_VALIDATE = 2;
	const STATE_FAIL = 3;
	const STATE_ERROR = 4;
	const STATE_DUPLICATE = 5;
	
	function __construct(
			public $url_action='',
			public $message_new='Registration Form',
			public $message_success='Form submitted successfully! You should receive a confirmation email shortly!',
			public $message_fail='Oops! We had trouble accepting your form. Details below.',
			public $message_error='You have discovered an internal error. Please contact us!',
			public $message_duplicate='You are already registered. If there is an issue, please contact us.',
			public $state=self::STATE_NEW,
			public $input_list=[]
		)
	{
		// Using PHP 8 constructor promotion.
	}

	/**
	* Add new field to form.
	* @param string $name Field name which can be refernced internally.
	* @param string $type Field type.
	* @param string $data Default data displayed in field.
	* @param string $data_validation_flags Data entered will be sanitized against this list of tests.
	* @param string $text_title Title of field.
	* @param string $text_help Helper text which is displayed to the User at fill-out time.
	* @param string $text_error Error text which is displayed to the User when validation fails.
	*/
	function Add($name, $type, $data, $data_validation_flags, $text_title, $text_help, $text_error)
	{
		// Set default data.
		$data_default = $data;

		// Get form submission data from $_POST if it exists.
		if(isset($_POST['simplephpform_'.$name]))  
		{
			$data = $_POST['simplephpform_'.$name]; 
			$this->state = self::STATE_VALIDATE; // We've got data during this pass. Form should be validated.
		}

		$this->input_list[$name] = new SimplePHPFormInput($name, $type, $data, $data_validation_flags, $data_default, $text_title, $text_help, $text_error);
	
		// Special logic for checkbox types because browsers simply do not $_POST them if they are unchecked.
		if($type == 'checkbox' && $this->state != self::STATE_NEW)
		{
			if(isset($_POST['simplephpform_'.$name]))
				$this->input_list[$name]->data = true;
			else
				$this->input_list[$name]->data = false;
		}
	}

	/**
	* Add new dropdown field to form.
	* @param string $name Field name which can be refernced internally.
	* @param string $data Default data for the field.
	* @param string $value Default display value for the field.
	*/
	function AddDropdownEntry($name, $data, $value)
	{
		$this->input_list[$name]->dropdown_entries[$value] = $data;
	}

	/**
	* Add new radio button field to form.
	* @param string $name Field name which can be refernced internally.
	* @param string $value Default display value for the field.
	* @param string $data Default data for the field.
	*/
	function AddRadioButton($name, $value, $data)
	{
		$this->input_list[$name]->radio_entries[$value] = $data;
	}

	// Display form state to User.
	function DisplayState()
	{
		$output = '';

		if($this->state == self::STATE_NEW) {
			$output = '<div class="simplephpform_state_untouched">'.$this->message_new.'</div>';
		} if($this->state == self::STATE_SUCCESS) {
			$output = '<div class="simplephpform_state_success">'.$this->message_success.'</div> <br />';
		} if($this->state == self::STATE_FAIL) {
			$output = '<div class="simplephpform_state_fail">'.$this->message_fail.'</div>';			
		} if($this->state == self::STATE_ERROR) {
			$output = '<div class="simplephpform_state_fail">'.$this->message_error.'</div>';
		} if($this->state == self::STATE_DUPLICATE) {
			$output = '<div class="simplephpform_state_success">'.$this->message_duplicate.'</div>';
		}

		return $output."\n";
	}

	// Display form field.
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

	// Reset all form data to defaults.
	function Reset()
	{
		foreach($this->input_list as $input)
			$input->data = $input->data_default;
	}
	
	// Get a value from the form.
	function Get($name)
	{
		if(isset($this->input_list[$name]->data))
			return $this->input_list[$name]->data;
		return null;
	}

	// Validate form data.
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

	// Does data exist?
	function ValidateExists($data)
	{
		if($data != '')
			return true;
		return false;
	}

	// Valid email?
	function ValidateEmail($data)
	{
		if(strlen($data) > 5 && strstr($data, '@') && strstr($data, '.') && strstr($data, ' ') == false)
			return true;
		return false;
	}

	// Valid phone number?
	function ValidatePhone($data)
	{
		if(intval($data) && strlen($data) > 10 && strlen($data) < 30)
			return true;
		return true;
	}

	// Valid number?
	function ValidateNumber($data)
	{
		if(is_numeric($data))
			return true;
		return false;
	}

	// Valid maximum string length?
	function ValidateLengthMax($data, $size)
	{
		if(strlen($data) < $size)
			return true;
		return false;
	}

	// Valid minimum string length?
	function ValidateLengthMin($data, $size)
	{
		if(strlen($data) > $size)
			return true;
		return false;
	}

	// Valid maximum number size?
	function ValidateSizeMax($data, $size)
	{
		if(is_numeric($data))
			if($data < $size)
				return true;
		return false;
	}

	// Valid minimum number size?
	function ValidateSizeMin($data, $size)
	{
		if(is_numeric($data))
			if($data > $size)
				return true;
		return false;
	}
}

// Used internally by SimplePHPForm to hold field data.
class SimplePHPFormInput
{
	function __construct(
			public $name=NULL,
			public $type='text',
			public $data='',
			public $data_validation_flags=[],
			public $data_default='',
			public $text_title='',
			public $text_help='',
			public $text_error='',
			// Internal state.
			public $state=SimplePHPForm::STATE_NEW,
			// Special variables used for specific input types.
			public $rows=3,
			public $columns=30,
			public $dropdown_entries=[],
			public $radio_entries=[]
		)
	{
		// Using PHP 8 constructor promotion.
		// If checkbox, $data needs to be true or false.
		if($type == 'checkbox')
		{
			$this->data = boolval($data);
			$this->data_default = boolval($data_default);
		}
	}
}
