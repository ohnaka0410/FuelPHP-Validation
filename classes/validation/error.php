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
 * Validation error
 *
 * Contains all the information about a validation error
 *
 * @package     Fuel
 * @subpackage  Validation
 * @author      ohnaka
 * @license     MIT License
 * @copyright   2018 ohnaka
 * @link        https://github.com/ohnaka0410/FuelPHP-Validation
 */
class Validation_Error extends \Fuel\Core\Validation_Error
{
	/**
	 * Replace templating tags with values
	 *
	 * @param   mixed  $msg  error message to parse
	 * @return  string
	 */
	protected function _replace_tags($msg)
	{
		// prepare label & value
		$label    = is_array($this->field->label) ? $this->field->label['label'] : $this->field->label;
		$value    = is_array($this->value) ? implode(', ', $this->value) : $this->value;
		if (\Config::get('validation.quote_labels', false) and strpos($label, ' ') !== false)
		{
			// put the label in quotes if it contains spaces
			$label = '"'.$label.'"';
		}

		// setup find & replace arrays
		$find     = array(':field', ':label', ':value', ':rule');
		$replace  = array($this->field->name, $label, $value, $this->rule);

		// add the params to the find & replace arrays
		foreach($this->params as $key => $val)
		{
			// Convert array (as far as possible)
			if (is_array($val))
			{
				$result = '';
				foreach ($val as $v)
				{
					if (is_array($v))
					{
						$v = '(array)';
					}
					elseif (is_object($v))
					{
						$v = '(object)';
					}
					elseif (is_bool($v))
					{
						$v = $v ? 'true' : 'false';
					}
					$v = \Lang::get('validation.param.'.$v, array(), $v);
					$result .= empty($result) ? $v : (', '.$v);
				}
				$val = $result;
			}
			elseif (is_bool($val))
			{
				$val = $val ? 'true' : 'false';
			}
			// Convert object with __toString or just the classname
			elseif (is_object($val))
			{
				$val = method_exists($val, '__toString') ? (string) $val : get_class($val);
			}

			$find[]     = ':param:'.($key + 1);
			$replace[]  = \Lang::get('validation.param.'.$val, array(), $val);
		}

		// execute find & replace and return
		return str_replace($find, $replace, $msg);
	}
}
