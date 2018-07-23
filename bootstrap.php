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

/**
 * Fuel Validation Package.
 *
 * @package     Fuel
 * @subpackage  Validation
 * @author      ohnaka
 * @license     MIT License
 * @copyright   2018 ohnaka
 * @link        https://github.com/ohnaka0410/FuelPHP-Validation
 */

\Autoloader::add_core_namespace('Fuel\\Validation');

\Autoloader::add_classes(array(
	'Fuel\\Validation\\Validation' => __DIR__.'/classes/validation.php',
));
