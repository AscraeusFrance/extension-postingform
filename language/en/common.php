<?php
/**
*
* @package phpBB Extension - Posting Form
* @copyright (c) 2015 FoFa (http://www/ascraeus-france.fr)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

$lang = array_merge($lang, array(
	// Form title
	'POSTING_FORM_TITLE'	=> 'Posting Form',
	
	// Forums list
	'FORUM_SELECT_TITLE'	=> 'Select a forum',
	'NO_FORUM'				=> 'Sorry, but there is no available forum.',
	
	// Post types
	'POST_TYPE_TITLE'		=> 'Post type',
	'POST_TYPE_NEW'			=> 'New topic',
	'POST_TYPE_REPLY'			=> 'Reply to a topic',
	
	// Post fields
	'POST_TITLE'			=> 'Post title',
	'POST_MESSAGE'			=> 'Message',
	'POST_MESSAGE_EXPLAIN'	=> 'Please insert only text. No HTML nor BBCodes will be parsed',
	'POST_DATE'			=> 'Date',
	'POST_DATE_EXPLAIN'			=> 'Format <strong>JJ/MM/AAAA</strong>',
	'POST_TIME'			=> 'Hour',
	'POST_TIME_EXPLAIN'			=> 'Format <strong>HH:MM:SS</strong>',
	
	// Users management
	'USERS_TYPE_TITLE'		=> 'User type',
	'USERS_TYPE_NEW'		=> 'New user',
	'USERS_TYPE_REGISTERED'	=> 'Already registered',
	'USER_SELECT_TITLE'		=> 'Select a user',
	'NO_USER_REGISTERED'	=> 'Sorry, but there is no available registered user.',
	'USER_NAME_FIELD'		=> 'Pseudo',
	'USER_MAIL_FIELD'		=> 'E-mail',
	'USER_NO_SUCCESSFUL'	=> 'Registering new user failed',
	'USER_SUCCESSFUL'		=> 'New user successfully registered',
	'USER_DAY'				=> 'Registration day',
	'USER_DAY_EXPLAIN'		=> 'Format <strong>JJ/MM/AAAA</strong>',
	'USER_TIME'				=> 'Registration hour',
	'USER_TIME_EXPLAIN'		=> 'Format <strong>HH:MM:SS</strong>',
	
	// Topics list
	'TOPIC_SELECT_TITLE'	=> 'Select a topic',
	'NO_TOPIC'				=> 'Sorry, but there is no available topic.',
	
	// Errors
	'ERRORS_EXPLAIN'			=> array(
		1	=> 'There is %d error. Correct it and send once again the form.<br />',
		2	=> 'There are %d errors. Correct them and send once again the form.<br />',
	),
	'NO_SELECTED_FORUM'			=> 'No forum selected',
	'NO_SELECTED_POST_TYPE'		=> 'No post type selected',
	'NO_SELECTED_TOPIC_ID'		=> 'No topic selected',
	'NO_SELECTED_POST_TITLE'	=> 'No post title entered',
	'NO_SELECTED_POST_TEXT'		=> 'No message text entered',
	'NO_SELECTED_POST_DAY'		=> 'No date selected',
	'NO_SELECTED_POST_TIME'		=> 'No hour selected',
	'NO_SELECTED_USER_ID'		=> 'No user selected',
	'NO_SELECTED_USER_NAME'		=> 'No username entered',
	'NO_SELECTED_USER_DAY'		=> 'No registration date entered',
	'NO_SELECTED_USER_TIME'		=> 'No registration hour entered',
	'USERNAME_TAKEN'			=> 'User name « <strong>%s</strong> » already taken',
	'SELECTED_USER_MAIL_INVALID' => 'Invalid e-mail entered',
	
	// Logs
	'LOG_USER_ADDEd_ACTIVATED'	=> '<strong>Added and activated user</strong><br />» %s',
	'LOG_NEW_TOPIC_ADDED'		=> '<strong>Posted a new topic « %1$s » in name of « %2$s »',
	'LOG_NEW_POST_ADDED'		=> '<strong>Answered a topic « %1$s » in name of « %2$s »',
)); 