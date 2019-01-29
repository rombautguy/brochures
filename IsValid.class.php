<?php
/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
 * THIS NOTICE MUST REMAIN AT THE TOP OF THIS SCRIPT
 *
 * File: SwiftValid.class.php
 *
 * This script is provided by Cloverswift Solutions you are free to use
 * it and modify it for all personal and commercial applications.
 *
 * Please contact webdesign@cloverswift.com if you would like to make a
 * donation for supporting php scripts.
 *
 * All functions except IsValid::Email written by Tom H. 
 * -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

// Change this to TRUE to enable parameter and return debugging output
define("DEBUG", FALSE);

// You probably shouldn't touch this but if you really want to, you can
// change this to create your own default setting for whether fields are required
define("DEFAULT_REQ", FALSE);

class IsValid
{
	/* ------------------------------------------------------
	FUNCTION: Email
	
	MODIFIED:
		5/24/2009 by Tom H. - added checkDNS and isRequired parameters
	
	INPUTS:
		email - raw email address to validate
		checkDNS - boolean, TRUE if you want to ping the DNS and validate the domain exists
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the email address has the email address format, FALSE otherwise
	
	DESCRIPTION:
	Validate an E-Mail Address with PHP, the Right Way
	June 1st, 2007 by Douglas Lovell in
	http://www.linuxjournal.com/article/9585
	
	IETF documents, RFC 1035 ?Domain Implementation and Specification?, RFC 2234 ?ABNF for Syntax Specifications?, RFC 2821 ?Simple Mail Transfer Protocol?, RFC 2822 ?Internet Message Format?, in addition to RFC 3696 (referenced earlier), all contain information relevant to e-mail address validation. RFC 2822 supersedes RFC 822 ?Standard for ARPA Internet Text Messages? and makes it obsolete.
	
	Following are the requirements for an e-mail address, with relevant references:
	
	   1.      An e-mail address consists of local part and domain separated by an at sign (@) character (RFC 2822 3.4.1).
	   2.      The local part may consist of alphabetic and numeric characters, and the following characters: !, #, $, %, &, ', *, +, -, /, =, ?, ^, _, `, {, |, } and ~, possibly with dot separators (.), inside, but not at the start, end or next to another dot separator (RFC 2822 3.2.4).
	   3.      The local part may consist of a quoted string?that is, anything within quotes ("), including spaces (RFC 2822 3.2.5).
	   4.      Quoted pairs (such as \@) are valid components of a local part, though an obsolete form from RFC 822 (RFC 2822 4.4).
	   5.      The maximum length of a local part is 64 characters (RFC 2821 4.5.3.1).
	   6.      A domain consists of labels separated by dot separators (RFC1035 2.3.1).
	   7.      Domain labels start with an alphabetic character followed by zero or more alphabetic characters, numeric characters or the hyphen (-), ending with an alphabetic or numeric character (RFC 1035 2.3.1).
	   8.      The maximum length of a label is 63 characters (RFC 1035 2.3.1).
	   9.      The maximum length of a domain is 255 characters (RFC 2821 4.5.3.1).
	  10.      The domain must be fully qualified and resolvable to a type A or type MX DNS address record (RFC 2821 3.6). 
	
	Requirement number four covers a now obsolete form that is arguably permissive. Agents issuing new addresses could legitimately disallow it; however, an existing address that uses this form remains a valid address.
	
	The standard assumes a seven-bit character encoding, not multibyte characters. Consequently, according to RFC 2234, ?alphabetic? corresponds to the Latin alphabet character ranges a?z and A?Z. Likewise, ?numeric? refers to the digits 0?9. The lovely international standard Unicode alphabets are not accommodated?not even encoded as UTF-8. ASCII still rules here. 
	------------------------------------------------------ */
	public static function EmailAddress($email, $checkDNS=FALSE, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;

		$email = trim($email);

		if ($email === '')

		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else if (($atIndex = strrpos($email, "@")) === FALSE)
		{
			$isValid = FALSE;
		}
		else
		{
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			
			if ($localLen < 1 || $localLen > 64)
			{
				// local part length exceeded
				$isValid = FALSE;
			}
			else if ($domainLen < 1 || $domainLen > 255)
			{
				// domain part length exceeded
				$isValid = FALSE;
			}
			else if ($local[0] == '.' || $local[$localLen-1] == '.')
			{
				// local part starts or ends with '.'
				$isValid = FALSE;
			}
			else if (preg_match('/\\.\\./', $local))
			{
				// local part has two consecutive dots
				$isValid = FALSE;
			}
			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			{
				// character not valid in domain part
				$isValid = FALSE;
			}
			else if (preg_match('/\\.\\./', $domain))
			{
				// domain part has two consecutive dots
				$isValid = FALSE;
			}
			else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
			{
				// character not valid in local part unless 
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
				{
					$isValid = FALSE;
				}
			}
			
			if ($checkDNS === TRUE)
			{
				if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
				{
					// domain not found in DNS
					$isValid = FALSE;
				}
			}	
		}
		
		if (DEBUG) echo "EMAIL: [$email], VALID: [$isValid], CHECKDNS: [$checkDNS], REQUIRED: [$isRequired]<br />\n";
		
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: RequiredString
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		string - value to check if empty string and proper length if specified
		minChars - the string must be at least this long (zero means no min)
		maxChars - the string must be at most this long (zero means no max)
		
	RETURNS:
		FALSE if the input is a string and is empty, TRUE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function RequiredString($string, $minChars=0, $maxChars=0)
	{
		$isValid = TRUE;
		$length = FALSE;
		
		if (($string === '') || ($string === NULL) || preg_match('/^\s+$/', $string) || !strlen($string))
		{
			return FALSE;
		}
		else
		{
			if ($minChars !== 0)
			{
				if (!$length) $length = strlen($string);
				if ($length < $minChars)
				{
					$isValid = FALSE;
				}
			}
			
			if ($maxChars !== 0)
			{
				if (!$length) $length = strlen($string);
				if ($length > $maxChars)
				{
					$isValid = FALSE;
				}
			}
		}
		
		return $isValid;
	}

	public static function isEmpty(&$var)
	{
		if (empty($var))
		{
			return TRUE;
		}
		else if (is_string($var) && preg_match('/\A\s+\z/', $var))
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	public static function isEmptyString(&$var)
	{
		if (preg_match('/\A\s+\z/', $var))
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	/* ------------------------------------------------------
	FUNCTION: Integer
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
		12/21/2009 TH - added output integer parameter
	
	INPUTS:
		inInteger - value to check, may be an integer with thousands separator
		outInteger - returns clean integer value without thousands sep or NULL if not passed in or FALSE if not an integer
		isUnsigned - boolean, TRUE if you require an unsigned integer
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is an integer, FALSE otherwise
		
	EXAMPLES:
		1,000   = TRUE
		  -58   = TRUE (if isUnsigned is FALSE)
		  390   = TRUE
		   56.0 = FALSE
		  089   = FALSE
		23,34   = FALSE
		 2334   = TRUE
		  56b   = FALSE
	
	DESCRIPTION:
		This routine input integer by removing the thousands separator
	   ------------------------------------------------------ */
	public static function Integer($inInteger, &$outInteger, $isUnsigned=FALSE, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
		$thousands_sep = ',';
		$pattern = '';

		if (!is_null($outInteger))
		{
			$outInteger = FALSE;
		}
		
		if ($inInteger === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else if ($inInteger != 0)
		{
			// We assume zero value can't have a sign, ie +0 or -0
			$pattern = '/^';
			if ($isUnsigned === FALSE)
			{
				$pattern .= '[-+]{0,1}';
			}
			else
			{
				$pattern .= '[+]{0,1}';
			}
			
			$pattern .= '[1-9]{1,1}\d{0,2}(((' . $thousands_sep . '\d{3,3})*)|(\d*))$/';
			
			if (preg_match($pattern, $inInteger) == 1)
			{
				// Make sure the caller even wants a conversion
				if (!is_null($outInteger))
				{
					// Ok we are valid but we may have a thousands separator
					// It's an integer but they included a thousands separator, fix it for them
					$outInteger = preg_replace('/' . $thousands_sep . '/', '', $inInteger);
				}
			}
			else
			{
				$isValid = FALSE;
			}
		}
		
		if (DEBUG) echo "INTEGER: [$inInteger], OUTPUT: [$outInteger], VALID: [$isValid], UNSIGNED: [$isUnsigned], REQUIRED: [$isRequired]<br />\n";
		
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: IntegerRange
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		inInteger - value to check, may be a string or integer
		minValue - min value integer can be, inclusive, may be FALSE to indicate none
		maxValue - max value integer can be, inclusive, may be FALSE to indicate none
		outInteger - returns clean integer value without thousands sep or NULL if not passed in or FALSE if not an integer
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is an integer and between min and max, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function IntegerRange($inInteger, $minValue, $maxValue, &$outInteger, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
		$minInteger = 0;
		$maxInteger = 0;

		if (!is_null($outInteger))
		{
			$outInteger = FALSE;
		}
		
		if ($inInteger === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			// We know it's not empty so require the three to be integers
			// so that we can compare their values
			$isValid = IsValid::Integer($inInteger, $outInteger, FALSE, TRUE);
			
			if ($isValid === TRUE)
			{
				// Do we test for a min value?
				if ($minValue !== FALSE)
				{
					$isMinValid = IsValid::Integer($minValue, $minInteger, FALSE, TRUE);
					if ($isMinValid === TRUE)
					{
						if ($inInteger < $minValue)
						{
							$isValid = FALSE;
						}
					}
				}
				
				// Do we test for a max value?
				if ($maxValue !== FALSE)
				{
					$isMaxValid = IsValid::Integer($maxValue, $maxInteger, FALSE, TRUE);
					if ($isMaxValid === TRUE)
					{
						if ($inInteger > $maxValue)
						{
							$isValid = FALSE;
						}
					}
				}
			}
		}
	
		if (DEBUG) echo "INTEGER_RANGE: [$inInteger], OUTPUT: [$outInteger], VALID: [$isValid], MIN: [$minValue], MAX: [$maxValue], REQUIRED: [$isRequired]<br />\n";
			
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: Decimal
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		integer - value to check, may be a string or integer
		numPrecision - max decimals the number can have, zero for no maximum
		isUnsigned - boolean, TRUE if you require an unsigned integer
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is an integer or decimal number with the precision given, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function Decimal($number, $numPrecision, $isUnsigned=FALSE, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
		$outInteger = 0;
		$precision = '';
		
		if ($number === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			// If we have a valid integer then we can just return true
			if (!IsValid::Integer($number, $outInteger, $isUnsigned, TRUE))
			{
				// It's not an integer so check for a decimal
				$pattern = '/^';
				if ($isUnsigned === FALSE)
				{
					$pattern .= '[-+]{0,1}';
				}
				else
				{
					$pattern .= '[+]{0,1}';
				}
				
				if ($numPrecision == 0)
				{
					$precision = '+';
				}
				else
				{
					$precision = '{1,' . $numPrecision . '}';
				}
				
				$pattern .= '\d*\.\d' . $precision . '$/';
				
				if (preg_match($pattern, $number) != 1)
				{
					$isValid = FALSE;
				}
			}
		}
	
		if (DEBUG) echo "DECIMAL: [$number], VALID: [$isValid], PRECISION: [$numPrecision], UNSIGNED: [$isUnsigned], REQUIRED: [$isRequired]<br />\n";
	
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: DecimalRange
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		number - value to check, may be a string or integer
		numPrecision - number of decimal places expected
		minValue - min value number can be, inclusive, may be FALSE for no min
		maxValue - max value number can be, inclusive, may be FALSE for no max
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if number is a decimal and within the range specified, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function DecimalRange($number, $numPrecision, $minValue, $maxValue, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
		
		if ($number === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			// We know it's not empty so test for valid decimal first
			$isValid = IsValid::Decimal($number, $numPrecision, FALSE, TRUE);
			if ($isValid === TRUE)
			{
				// Do we have a min value to test?
				if ($minValue !== FALSE)
				{
					$isMinValid = IsValid::Decimal($minValue, $numPrecision, FALSE, TRUE);
					if ($isMinValid === TRUE)
					{
						if ($number < $minValue)
						{
							$isValid = FALSE;
						}
					}
				}
				
				// Do we have a max value to test?
				if ($maxValue !== FALSE)
				{
					$isMaxValid = IsValid::Decimal($maxValue, $numPrecision, FALSE, TRUE);
					if ($isMaxValid === TRUE)
					{
						if ($number > $maxValue)
						{
							$isValid = FALSE;
						}
					}
				}
			}
		}
	
		if (DEBUG) echo "DECIMAL_RANGE: [$number], VALID: [$isValid], PRECISION: [$numPrecision], MIN: [$minValue], MAX: [$maxValue], REQUIRED: [$isRequired]<br />\n";
			
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: Currency
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		number - value to check, may be a string or integer
		isUnsigned - boolean, TRUE if you require an unsigned integer
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if valid, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function Currency($number, $isUnsigned=FALSE, $isRequired=DEFAULT_REQ)
	{
		return IsValid::Decimal($number, 2, $isUnsigned, $isRequired);
	}
	
	/* ------------------------------------------------------
	FUNCTION: Boolean
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		boolean - value to check
		trueValue - value which indicates a true value
		falseValue - value which indicates a false value
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is either one of trueValue or falseValue, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function Boolean($boolean, $trueValue, $falseValue, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
	
		if ($boolean === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			if (($boolean != $trueValue) && ($boolean != $falseValue))
			{
				$isValid = FALSE;
			}
		}
	
		if (DEBUG) echo "BOOLEAN: [$boolean], VALID: [$isValid], TRUE_VALUE: [$trueValue], FALSE_VALUE: [$falseValue], REQUIRED: [$isRequired]<br />\n";
	
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: String
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		string - value to check
		validChars - all characters which can be included in the string
		minChars - min string length
		maxChars - max string length
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if valid, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function String($string, $validChars, $minChars, $maxChars, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
	
		if ($string === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			if ($validChars === '')
			{
				$length = strlen($string);
				if (($length < $minChars) || ($length > $maxChars))
				{
					$isValid = FALSE;
				}
			}
			else
			{
				$pattern = '/^[' . $validChars . ']{' . $minChars . ',' . $maxChars . '}$/';
			
				if (preg_match($pattern, $string) != 1)
				{
					$isValid = FALSE;
				}
			}
		}
	
		if (DEBUG) echo "STRING: [$string], VALID: [$isValid], CHARS: [$validChars], MIN: [$minChars], MAX: [$maxChars], REQUIRED: [$isRequired]<br />\n";
	
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: USPhoneNumber
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		phone - number to check, US only right now
		sep - separation character, usually dash or dot
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is an phone, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function USPhoneNumber($phone, $sep, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
	
		if ($phone === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			$pattern = '/^\s*\d\d\d' . $sep . '\d\d\d' . $sep . '\d\d\d\d/';
		
			if (preg_match($pattern, $phone) != 1)
			{
				$isValid = FALSE;
			}
		}
	
		if (DEBUG) echo "PHONE: [$phone], VALID: [$isValid], SEP: [$sep], REQUIRED: [$isRequired]<br />\n";
	
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: UserName
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		username - value to check, may be a string or integer
		validChars - all characters which can be included in the username
		minChars - min username length
		maxChars - max username length
		allowEmail - TRUE/FALSE if email is a valid username
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is an integer, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function UserName($username, $validChars='a-zA-Z\d-_@.', $minChars=6, $maxChars=64, $allowEmail=TRUE, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
		
		if ($allowEmail)
		{
			// We allow email addresses as a username, check this first
			$isValid = self::EmailAddress($username, FALSE, $isRequired);
		}
		
		if ($isValid === FALSE)
		{
			// It is not an email so restrict them a little more
			$isValid = self::String($username, $validChars, $minChars, $maxChars, $isRequired);
		}
		
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: Password
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		pw - value to check
		validChars - all characters which can be included in the password
		minChars - min pw length
		maxChars - max pw length
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if valid, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function Password($pw, $validChars='', $minChars=8, $maxChars=32, $isRequired=DEFAULT_REQ)
	{
		return IsValid::String($pw, $validChars, $minChars, $maxChars, $isRequired);
	}
	
	/* ------------------------------------------------------
	FUNCTION: Date
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		date - value to check
		dateFormat - mm/dd/yyyy type of format (dashes or slashes allowed)
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if valid, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function Date($date, $dateFormat, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
		
		$yyyy = 0;
		$mm = 0;
		$dd = 0;
	
		if ($date === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			// This pattern isn't perfect because we could be passed something like y/y/m
			// but it doesn't matter because then when we check our date it will fail
			// because the missing one (in this case 'd') will be zero.
			$formatPattern = '/[ymd]{2,4}[\/-][md]{2}[\/-][ymd]{2,4}/';
			if (preg_match($formatPattern, $dateFormat) != 1)
			{
				$isValid = FALSE;
			}
			else
			{
				$datePattern = '/(\d{1,4})[\/-](\d{1,2})[\/-](\d{1,4})/';
				if (preg_match($datePattern, $date) != 1)
				{
					$isValid = FALSE;
				}
				else
				{
					$dateParts = preg_split('/[\/-]/', $date);
					$formatParts = preg_split('/[\/-]/', $dateFormat);
					
					// Go through and assign the value to the date character to which it represents
					// For example, $yyyy=2002 and $mm=3 and $dd=16
					for ($pos = 0; $pos < 3; $pos++)
					{
						$phpCode = '$' . $formatParts[$pos] . '="' . $dateParts[$pos] . '";';
						eval($phpCode);
					}		
				}
				
				// Let's avoid Y2K again shall we?
				if (strlen($yyyy) != 4)
				{
					$isValid = FALSE;
				}
				else
				{
					// Get rid of any extra zeros in front
					$dd = (int) ltrim($dd, '0');
					$mm = (int) ltrim($mm, '0');
					
					// We have all the date parts so now validate it
					if (checkdate($mm, $dd, $yyyy) === FALSE)
					{
						$isValid = FALSE;
					}
				}
			}
		}
	
		if (DEBUG) echo "DATE: [$date], VALID: [$isValid], DATE_FORMAT: [$dateFormat], REQUIRED: [$isRequired]<br />\n";
	
		return $isValid;
	}

	/* ------------------------------------------------------
	FUNCTION: SqlDate
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		date - value to check
		dateFormat - mm/dd/yyyy type of format (dashes or slashes allowed)
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if valid, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function SqlDate($date, $isRequired=DEFAULT_REQ)
	{
		return self::Date($date, 'yyyy-mm-dd', $isRequired);
	}

	/* ------------------------------------------------------
	FUNCTION: DateTime
	
	MODIFIED:
		11/08/2013 by Tom H. - created function
	
	INPUTS:
		datetime - value to check
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is a human readable date time string, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function DateTime($datetime, &$timestamp, &$sql_date_time, $isRequired=DEFAULT_REQ)
	{
		$isValid = FALSE;
		$timestamp = FALSE;
		$sql_date_time = FALSE;
		$matches = array();
		
		if ($datetime === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			$timestamp = strtotime($datetime);
			if ($timestamp !== FALSE)
			{
				$sql_date_time = date('Y-m-d H:i:s');
				$isValid = TRUE;
			}
		}
	
		if (DEBUG) echo "DATETIME: [$datetime], VALID: [$isValid], REQUIRED: [$isRequired]<br />\n";
	
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: SqlDateTime
	
	MODIFIED:
		5/24/2009 by Tom H. - created function
	
	INPUTS:
		datetime - value to check
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is an sql date time like YYYY-MM-DD HH:MM:SS, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function SqlDateTime($datetime, $isRequired=DEFAULT_REQ)
	{
		$isValid = FALSE;
		$matches = array();
		
		if ($datetime === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
				$datePattern = '/(\d\d\d\d)-(\d\d)-(\d\d) (\d{1,2}):(\d\d):(\d\d)/';
				if (preg_match($datePattern, $datetime, $matches) != 1)
				{
					$isValid = FALSE;
				}
				else
				{
					$year = (int) $matches[1];
					$month = (int) $matches[2];
					$day = (int) $matches[3];
					$hour = (int) $matches[4];
					$minute = (int) $matches[5];
					$second = (int) $matches[6];
					
					if (($month >= 1) && ($month <= 12) &&
						($day >= 1) && ($day <= 31) &&
						($hour >= 0) && ($hour <= 23) &&
						($minute >= 0) && ($minute <= 59) &&
						($second >= 0) && ($second <= 59))
					{
						$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
						if ($timestamp === FALSE)
						{
							$isValid = FALSE;
						}
						else
						{
							$isValid = TRUE;
						}
					}
				}			
		}
	
		if (DEBUG) echo "SQLDATETIME: [$datetime], VALID: [$isValid], REQUIRED: [$isRequired]<br />\n";
	
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: Time
	
	MODIFIED:
		10/15/2010 by Tom H. - created function
	
	INPUTS:
		time - string, value to check in a human readable format like 8pm, 11:15am, 9 PM, 07:13:50 am, etc.
		sqlTime - string, output of sql format time
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is a time string, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function Time($time, &$sqlTime, $isRequired=DEFAULT_REQ)
	{
		$isValid = TRUE;
		$sqlTime = '';
		
		if ($time === '')
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			$matches = array();
			$hour = 0;
			$minute = 0;
			$second = 0;
			$ampm = 0;
			$time = strtolower(preg_replace('/\s+/', '', $time));
			if (strpos($time, ':') === FALSE)
			{
				// We should have a format like this: 8pm, 11am, etc.
				if (preg_match('@(\d{1,2})(am|pm){0,1}@', $time, $matches) == 1)
				{
					$hour = $matches[1];
					$ampm = $matches[2];
				}
				else
				{
					$isValid = FALSE;
				}
			}
			else
			{
				// We should have a format like this: 6:15pm, 11:45am, 14:00, 07:15:50 PM, etc.
				if (preg_match('/^(\d{1,2}):(\d\d)(:(\d\d)){0,1}(am|pm){0,1}$/', $time, $matches) == 1)
				{
					$hour = $matches[1];
					$minute = $matches[2];
					$second = (isset($matches[4])) ? $matches[4] : 0;
					$ampm = (isset($matches[5])) ? $matches[5] : 0;
				}
				else
				{
					$isValid = FALSE;
				}
			}
			
			if ($isValid !== FALSE)
			{
				// So far we have a valid time, now we just check the ranges and adjust to military time (sql time)
				if (empty($ampm))
				{
					// User entered military time, hour 0-23
					if (($hour >= 0) && ($hour <= 23))
					{
						$sqlTime .= str_pad($hour, 2, '0', STR_PAD_LEFT);
					}
					else
					{
						$isValid = FALSE;
					}
				}
				else
				{
					// No military time, must do calculations for hour
					if (($hour >= 1) && ($hour <= 12))
					{
						if ($ampm == 'am')
						{
							if ($hour == 12) $hour = 0;
						}
						else if ($ampm == 'pm')
						{
							if ($hour < 12) $hour += 12;
						}
						
						$sqlTime .= str_pad($hour, 2, '0', STR_PAD_LEFT);
					}
					else
					{
						$isValid = FALSE;
					}
				}
				
				if ($isValid !== FALSE)
				{
					if (($minute >= 0) && ($minute <= 59))
					{
						$sqlTime .= ':' . str_pad($minute, 2, '0', STR_PAD_LEFT);
					}
					else
					{
						$isValid = FALSE;
					}
					
					if (($second >= 0) && ($second <= 59))
					{
						$sqlTime .= ':' . str_pad($second, 2, '0', STR_PAD_LEFT);
					}
					else
					{
						$isValid = FALSE;
					}
				}
			}
		}
	
		if (DEBUG) echo "TIME: [$time], VALID: [$isValid], SQL_TIME: [$sqlTime], REQUIRED: [$isRequired]<br />\n";
	
		if ($isValid === FALSE) $sqlTime = '';
		
		return $isValid;
	}
	
	/* ------------------------------------------------------
	FUNCTION: Url
	
	MODIFIED:
		09/20/2011 by Tom H. - created function
	
	INPUTS:
		url - string, value to check
		isRequired - boolean, TRUE if this field can not be empty
		
	RETURNS:
		TRUE if the input is a URL, FALSE otherwise
	
	DESCRIPTION:
	   ------------------------------------------------------ */
	public static function Url($url, $reqProtocol=TRUE, $isRequired=DEFAULT_REQ)
	{
		$isValid = FALSE;
		
		if (self::isEmpty($url))
		{
			if ($isRequired === TRUE)
			{
				$isValid = FALSE;
			}
		}
		else
		{
			if (substr($url, 0, 2) == '//')
			{
				// Default to secure photos
				$url = 'https:'.$url;
			}
			
			// First make sure no backslashes exist
			if (strpbrk($url, "\\") === FALSE)
			{
				$web_regex = '@\A';
				
				if ($reqProtocol === TRUE)
				{
					$web_regex .= '(http|https)://';
				}
				else
				{
					$web_regex .= '((http|https)://){0,1}';
				}
				
				$web_regex .= '([A-Z0-9][A-Z0-9_-]*)(\.([A-Z0-9_-]+)){1,}(:[0-9]+){0,1}@i';
				
				if (preg_match($web_regex, $url) == 1)
				{
					$isValid = TRUE;
				}
			}
		}
		
		if (DEBUG) echo "URL: [$url], VALID: [$isValid], REQUIRED: [$isRequired]<br />\n";
	
		return $isValid;
	}
} // END CLASS 
