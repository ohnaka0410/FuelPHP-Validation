<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.1
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2018 Fuel Development Team
 * @link       http://fuelphp.com
 */

 namespace Fuel\Validation;

/**
 * Static object to allow static usage of validation through singleton.
 *
 * @package     Fuel
 * @subpackage  Validation
 * @author      ohnaka
 * @license     MIT License
 * @copyright   2018 ohnaka
 * @link        https://github.com/ohnaka0410/FuelPHP-Validation
 */
class Validation extends \Fuel\Core\Validation
{
	/* -------------------------------------------------------------------------------
	 * The validation methods
	 * ------------------------------------------------------------------------------- */

	/**
	 * Required
	 *
	 * Value may not be empty
	 *
	 * @param   mixed  $val
	 * @param   bool   $exclude_line_break
	 * @return  bool
	 */
	public function _validation_required($val, $exclude_line_break = false)
	{
		$val = (is_string($val) && $exclude_line_break) ? str_replace(array("\r\n", "\r", "\n"), '', $val) : $val;
		return parent::_validation_required($val);
	}

	/**
	 * Maximum string length
	 *
	 * @param   string  $val
	 * @param   int     $length
	 * @param   bool    $exclude_line_break
	 * @return  bool
	 */
	public function _validation_max_length($val, $length, $exclude_line_break = false)
	{
		$val = $exclude_line_break ? str_replace(array("\r\n", "\r", "\n"), '', $val) : $val;
		return parent::_validation_max_length($val, $length);
	}

	/**
	 * Full-width string
	 *
	 * @param   string  $val
	 * @return  bool
	 */
	public function _validation_valid_zen_kaku($val)
	{
		$pattern = "/^[^ -~｡-ﾟ\t]+$/u";
		return $this->_empty($val) || $this->_validation_match_pattern($val, $pattern);
	}

	/**
	 * Half-width string
	 *
	 * @param   string  $val
	 * @return  bool
	 */
	public function _validation_valid_han_kaku($val)
	{
		$pattern = "/^[ -~｡-ﾟ\t]+$/u";
		return $this->_empty($val) || $this->_validation_match_pattern($val, $pattern);
	}

	/**
	 * Validate email phone
	 *
	 * @param   string  $val
	 * @return  bool
	 */
	public function _validation_valid_phone($val)
	{
		$str = str_replace('-', '', $val);
		$pattern = "/^[0-9]+[\-0-9]*[0-9]+$/u";
		return $this->_empty($val) ||
			(
				$this->_validation_min_length($str, 9)
				&& $this->_validation_max_length($str, 11)
				&& $this->_validation_match_pattern($val, $pattern)
			)
		;
	}

	/**
	 * Validate email
	 *
	 * @param   string  $val
	 * @return  bool
	 */
	public function _validation_valid_email($val)
	{
		$pattern = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+([a-zA-Z0-9\._-]+)+$/";
		return $this->_empty($val) || $this->_validation_match_pattern($val, $pattern);
	}

	/**
	 * Validate email
	 *
	 * @param   string  $val
	 * @param   string  $separator
	 * @return  bool
	 */
	public function _validation_valid_emails($val, $separator = ',')
	{
		$pattern = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+([a-zA-Z0-9\._-]+)+$/";

		if ($this->_empty($val))
		{
			return true;
		}

		$emails = explode($separator, $val);

		foreach ($emails as $e)
		{
			if ( ! $this->_validation_match_pattern(trim($e), $pattern))
			{
				return false;
			}
		}
		return true;
	}


	/**
	 * Validate input string with many options
	 *
	 * @param   string        $val
	 * @param   string|array  $flags  either a named filter or combination of flags
	 * @return  bool
	 */
	public function _validation_valid_string($val, $flags = array('alpha', 'utf8'))
	{
		if ($this->_empty($val))
		{
			return true;
		}

		if ( ! is_array($flags))
		{
			if ($flags == 'alpha')
			{
				$flags = array('alpha', 'utf8');
			}
			elseif ($flags == 'alpha_numeric')
			{
				$flags = array('alpha', 'utf8', 'numeric');
			}
			elseif ($flags == 'specials')
			{
				$flags = array('specials', 'utf8');
			}
			elseif ($flags == 'url_safe')
			{
				$flags = array('alpha', 'numeric', 'dashes');
			}
			elseif ($flags == 'integer' or $flags == 'numeric')
			{
				$flags = array('numeric');
			}
			elseif ($flags == 'float')
			{
				$flags = array('numeric', 'dots');
			}
			elseif ($flags == 'quotes')
			{
				$flags = array('singlequotes', 'doublequotes');
			}
			elseif ($flags == 'slashes')
			{
				$flags = array('forwardslashes', 'backslashes');
			}
			elseif ($flags == 'all')
			{
				$flags = array('alpha', 'utf8', 'numeric', 'specials', 'spaces', 'newlines', 'tabs', 'punctuation', 'singlequotes', 'doublequotes', 'dashes', 'forwardslashes', 'backslashes', 'brackets', 'braces');
			}
			else
			{
				return false;
			}
		}

		if ( ! in_array('utf8', $flags))
		{
			$flags[] = 'utf8';
		}

		$pattern = ! in_array('uppercase', $flags) && in_array('alpha', $flags) ? 'a-z' : '';
		$pattern .= ! in_array('lowercase', $flags) && in_array('alpha', $flags) ? 'A-Z' : '';
		$pattern .= in_array('numeric', $flags) ? '0-9' : '';
		$pattern .= in_array('specials', $flags) ? '[:alpha:]' : '';
		$pattern .= in_array('spaces', $flags) ? ' ' : '';
		$pattern .= in_array('newlines', $flags) ? "\r\n" : '';
		$pattern .= in_array('tabs', $flags) ? "\t" : '';
		$pattern .= in_array('dots', $flags) && ! in_array('punctuation', $flags) ? '\.' : '';
		$pattern .= in_array('commas', $flags) && ! in_array('punctuation', $flags) ? ',' : '';
		$pattern .= in_array('punctuation', $flags) ? "\.,\!\?:;\&" : '';
		$pattern .= in_array('dashes', $flags) ? '_\-' : '';
		$pattern .= in_array('forwardslashes', $flags) ? '\/' : '';
		$pattern .= in_array('backslashes', $flags) ? '\\\\' : '';
		$pattern .= in_array('singlequotes', $flags) ? "'" : '';
		$pattern .= in_array('doublequotes', $flags) ? "\"" : '';
		$pattern .= in_array('brackets', $flags) ? "\(\)" : '';
		$pattern .= in_array('braces', $flags) ? "\{\}" : '';
		$pattern .= in_array('hyphen', $flags) ? "-" : '';
		$pattern .= in_array('katakana', $flags) ? "ァ-タダ-ヶ" : '';
		$pattern = empty($pattern) ? '/^(.*)$/' : ('/^(['.$pattern.'])+$/');
		$pattern .= in_array('utf8', $flags) || in_array('specials', $flags) ? 'u' : '';
		return preg_match($pattern, $val) > 0;
	}
}

/* end of file validation.php */
