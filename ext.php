<?php
/**
*
* @package phpBB Extension - Posting Form
* @copyright (c) 2015 FoFa (http://www/ascraeus-france.fr)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace fofa\postingform;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* Main class for « Posting Form » Extension.
*/
class ext extends \phpbb\extension\base
{
	/**
	* Enable extension if pre-requis ok
	*
	* @return bool
	* @aceess public
	*/
	public function is_enableable()
	{
		// Configuration parameters
		$config = $this->container->get('config');

		// phpBB minima version required !
		if (!version_compare($config['version'], '3.1.6', '>='))
		{
			return false;
		}
		
		return true;
	}
}