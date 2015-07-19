
<?php

session_start();

// Validate user input Intergers using Filter
function validateInteger($value, $errorInputName)
{
	if (filter_var($value, FILTER_VALIDATE_INT) === 0||!filter_var($value, FILTER_VALIDATE_INT) === false||$value=='')
	{


	}
	else
	{
		// Set session error to be displayed on search page on redirect.
		$_SESSION["formInputError"][$errorInputName] ="Error, input must be a interger.";
	}

}

// Validate that the user input string lenght is within the correct range
function validateStringLength($string, $errorInputName)
{
	if (strlen($string)>50)
	{
		// Set session error to be displayed on search page on redirect.
		$_SESSION["formInputError"][$errorInputName] ="Error, input must be less than 50 characters.";
	}
}

// Validate user string inputs using Regex and filter
// Characters allowed are (0-9,a-z,A_Z, Spaces, ', _)

function validateString($string, $errorInputName)
{
	if (!filter_var($string, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9\s'_]+$/"))))
	{
		// Set session error to be displayed on search page on redirect.
		$_SESSION["formInputError"][$errorInputName] ="Error, search string has incorrect characters.";
	}

}

// Strip unnecessary characters and backslashes and make special chars HTML entities
// Done to all inputs to stop Cross-site Scripting attacks.
function clean_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// Get form inputs and validate input based on what type of input it is.
function getFormInput($inputName, $sqlQueryStart, $sqlQueryEnd, $inputType)
{
	$queryName='';
	if(isset($_GET[$inputName])&&($_GET[$inputName]!='')&&$_GET[$inputName]!="All")
	{
		$input= clean_input($_GET[$inputName]);
		if ($inputType == "Interger")
		{
			validateInteger($input, $inputName."Error");
		}
		elseif($inputType == "String")
		{
			validateStringLength($input, $inputName."Error");
			validateString($input, $inputName."Error");
		}

		$queryName = $sqlQueryStart.$input.$sqlQueryEnd;
	}

	return $queryName;
}
?>
