<?php
/**
*
* @package phpBB Extension - Nom Extension
* @copyright (c) 2015 Votre Pseudo/**
*
* @package phpBB Extension - Posting Form
* @copyright (c) 2015 FoFa (http://www/ascraeus-france.fr)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace fofa\postingform\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
/**
* Assign functions defined in this class to event listeners in the core
*
* @return array
* @static
* @access public
*/
    static public function getSubscribedEvents()
    {
        return array(
            'core.user_setup'                        => 'load_language_on_setup',
        );
    }
 
    public function load_language_on_setup($event)
    {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = array(
            'ext_name' => 'fofa/postingform',
            'lang_set' => 'common',
        );
        $event['lang_set_ext'] = $lang_set_ext;
    }
	
	public function add_page_header_link($event)
	{
		$this->template->assign_vars(array(
			'U_POSTINGFORM'			=> $this->helper->route('fofa_postingform'),
		));
	}
	
}