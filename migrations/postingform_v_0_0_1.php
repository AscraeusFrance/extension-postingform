<?php
/**
*
* Package phpBB Extens/**
*
* @package phpBB Extension - Posting Form
* @copyright (c) 2015 FoFa (http://www/ascraeus-france.fr)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace fofa\postingform\migrations;

class postingform_v_0_3_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['postingform_version']) && version_compare($this->config['postingform_version'], '0.3.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.add', array('postingform_version', '0.3.0')),
		);
	}

	public function revert_data()
	{
		// Remove
		return array(
			array('config.remove', array('postingform_version')),
		);
	}
}