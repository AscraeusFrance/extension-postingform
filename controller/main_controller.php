<?php
/**
*
* @package phpBB Extension - Posting Form
* @copyright (c) 2015 FoFa (http://www/ascraeus-france.fr)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace fofa\postingform\controller;

use Symfony\Component\HttpFoundation\Response;

class main_controller
{
	protected $config;
	protected $db;
	protected $auth;
	protected $template;
	protected $user;
	protected $helper;
	protected $phpbb_root_path;
	protected $php_ext;

	
	/*
	*	Constructor 
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\request\request_interface $request, \phpbb\pagination $pagination, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, $phpbb_root_path, $php_ext, $table_prefix)
	{
		$this->config = $config;
		$this->request = $request;
		$this->pagination = $pagination;
		$this->db = $db;
		$this->auth = $auth;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->table_prefix = $table_prefix;
	}

	/*
	*	public function
	*/
	public function main()
	{
		
	// Starting some vars
	$submit = $this->request->is_set_post('post'); // Did we press submit button ?

	$select_forum_id		= request_var('select_forum_id', 0);
	$select_post_type		= request_var('select_post_type', 0);
	$select_topic_id		= request_var('select_topic_id', 0);
	$select_post_title 		= utf8_normalize_nfc(request_var('select_post_title', '', true));
	$select_message 		= utf8_normalize_nfc(request_var('select_message', '', true));
	$select_post_time_day 	= utf8_normalize_nfc(request_var('select_post_time_day', '', true));
	$select_post_time_hour 	= utf8_normalize_nfc(request_var('select_post_time_hour', '', true));
	$select_user_type		= request_var('select_user_type', 0);
	$select_user_name 		= utf8_normalize_nfc(request_var('select_user_name', '', true));
	$select_user_mail 		= utf8_normalize_nfc(request_var('select_user_mail', '', true));
	$select_user_id			= request_var('select_user_id', 0);
	$select_user_day		= utf8_normalize_nfc(request_var('select_user_day', '', true));
	$select_user_time		= utf8_normalize_nfc(request_var('select_user_time', '', true));
	
	// Starting forums list
		$sql_forums_list = 'SELECT forum_id, forum_name, forum_type, forum_status, left_id, right_id
							FROM ' . FORUMS_TABLE . '
							WHERE forum_status = ' . ITEM_UNLOCKED . '
							ORDER BY left_id';
		
		$result_forums_list = $this->db->sql_query($sql_forums_list);
		
		while ($row = $this->db->sql_fetchrow($result_forums_list))
		{
			$this->template->assign_vars(array(
				'S_FORUMS_LIST'	=> ($row) ? true : false,
			));
			
			$this->template->assign_block_vars('forums_list', array(
				'FORUM_ID'			=> $row['forum_id'],
				'FORUM_NAME'		=> $row['forum_name'],
				'FORUM_SELECTED'	=> ($row['forum_id'] == $select_forum_id) ? ' selected="selected"' : '',
				'S_IS_CAT'			=> ($row['forum_type'] == FORUM_CAT) ? true : false,
				'S_IS_LINK'			=> ($row['forum_type'] == FORUM_LINK) ? true : false,
				'S_IS_POST'			=> ($row['forum_type'] == FORUM_POST) ? true : false,
			));
		}
		$this->db->sql_freeresult($result_forums_list);

	// Starting topics list
		if ($select_forum_id) // Only if a forum was selected
		{
			$sql_topics_list = 'SELECT topic_id, forum_id, topic_title, topic_time, topic_poster
			FROM ' . TOPICS_TABLE . '
			WHERE forum_id = ' . $select_forum_id;

			$result_topics_list = $this->db->sql_query($sql_topics_list);
			
			$topics_list = array();
			while ($row = $this->db->sql_fetchrow($result_topics_list))
			{
				$this->template->assign_vars(array(
					'S_TOPICS_LIST'	=> ($row) ? true : false,
				));
				
				$this->template->assign_block_vars('topics_list', array(
					'TOPIC_ID'			=> $row['topic_id'],
					'TOPIC_TITLE'		=> $row['topic_title'],
					'TOPIC_SELECTED'	=> ($row['topic_id'] == $select_topic_id) ? ' checked="checked"' : '',
				));
			}
			$this->db->sql_freeresult($result_topics_list);
		}
	
	// Starting topic/post title
		if ($select_post_type == 1)
		{	
			$findme = 'Re: ';
			$pos = strpos($select_post_title, $findme);
			
				if ($pos !== FALSE)
				{
					$select_post_title = '';
				}
				else
				{
					$select_post_title = $select_post_title;
				}	
		}
		else
		{
				if (!$select_topic_id)
				{
					$select_post_title = '';
				}
				else
				{
					$sql = 'SELECT topic_id, topic_title
						FROM ' . TOPICS_TABLE . '
						WHERE topic_id = ' . $select_topic_id;
					$result = $this->db->sql_query_limit($sql, 1);
					$row_topic = $this->db->sql_fetchrow($result);
					$this->db->sql_freeresult($result);
				
					$select_post_title = 'Re: ' . $row_topic['topic_title'];
				}	
		}

	// Starting time publication
		$date_day = explode('/', $select_post_time_day);
		$date_hour = explode(':', $select_post_time_hour);
		$date_timestamp = ($select_post_time_day && $select_post_time_hour) ? mktime($date_hour[0], $date_hour[1], $date_hour[2], $date_day[1], $date_day[0], $date_day[2]) : '';
		
	// Starting user registration time
		$user_day = explode('/', $select_user_day);
		$user_hour = explode(':', $select_user_time);
		$user_timestamp = ($select_user_day && $select_user_time) ? mktime($user_hour[0], $user_hour[1], $user_hour[2], $user_day[1], $user_day[0], $user_day[2]) : '';

	// Starting members list
		if ($select_user_type == 2)
		{
			$sql_users_list = 'SELECT user_id, user_type, username
						FROM ' . USERS_TABLE . '
						WHERE user_type <> ' . USER_IGNORE;
				
			$result_users_list = $this->db->sql_query($sql_users_list);
			
			$users_list = array();
			while ($row = $this->db->sql_fetchrow($result_users_list))
			{
				$this->template->assign_vars(array(
					'S_USERS_LIST'	=> ($row) ? true : false,
				));
				
				$this->template->assign_block_vars('users_list', array(
					'USER_ID'		=> $row['user_id'],
					'USER_NAME'		=> $row['username'],
					'USER_SELECTED'	=> ($row['user_id'] == $select_user_id) ? ' selected="selected"' : '',
				));			
			}
			$this->db->sql_freeresult($result_users_list);
		}

	// Starting form submission
	if ($submit)
	{
		// Starting errors
			$errors = array();
			if (!$select_forum_id)
			{
			$errors[] = $this->user->lang['NO_SELECTED_FORUM'];
			}
			
			if (!$select_post_type)
			{
			$errors[] = $this->user->lang['NO_SELECTED_POST_TYPE'];
			}

			if ($select_post_type == 2 && !$select_topic_id)
			{
			$errors[] = $this->user->lang['NO_SELECTED_TOPIC_ID'];
			}
			
			if ($select_post_type && !$select_post_title)
			{
			$errors[] = $this->user->lang['NO_SELECTED_POST_TITLE'];
			}
			
			if ($select_post_type && !$select_message)
			{
			$errors[] = $this->user->lang['NO_SELECTED_POST_TEXT'];
			}
			
			if ($select_post_type && !$select_post_time_day)
			{
			$errors[] = $this->user->lang['NO_SELECTED_POST_DAY'];
			}
			
			if ($select_post_type && !$select_post_time_hour)
			{
			$errors[] = $this->user->lang['NO_SELECTED_POST_TIME'];
			}
			
			if ($select_user_type == 2 && !$select_user_id)
			{
			$errors[] = $this->user->lang['NO_SELECTED_USER_ID'];
			}
			
			if ($select_user_type == 1 && !$select_user_day)
			{
			$errors[] = $this->user->lang['NO_SELECTED_USER_DAY'];
			}
			
			if ($select_user_type == 1 && !$select_user_time)
			{
			$errors[] = $this->user->lang['NO_SELECTED_USER_TIME'];
			}

			if ($select_user_type == 1)
			{
				// Did we entered an username ?
				if (!$select_user_name)
				{
				$errors[] = $this->user->lang['NO_SELECTED_USER_NAME'];
				}
				// If yes, is this username free ?
				else
				{
					$clean_username = utf8_clean_string($select_user_name);
					$sql = 'SELECT username
							FROM ' . USERS_TABLE . "
							WHERE username_clean = '" . $this->db->sql_escape($clean_username) . "'";
					$result = $this->db->sql_query($sql);
					$row = $this->db->sql_fetchrow($result);
					$this->db->sql_freeresult($result);

					if ($row)
					{
					$errors[] = $this->user->lang('USERNAME_TAKEN', $select_user_name);
					}
				}
				
				// Did we entered an email ?
				if (!$select_user_mail)
				{
				$errors[] = $this->user->lang['NO_SELECTED_USER_MAIL'];
				}
				// If yes, is this email valid ?
				else
				{
					if (filter_var($select_user_mail, FILTER_VALIDATE_EMAIL) === false)
					{
					$errors[] = $this->user->lang['SELECTED_USER_MAIL_INVALID'];
					}
				}
			}	
			
			$show_errors = implode('<br />', $errors);
		// Ending errors
		
		// No errors, let's continue
		if (!$show_errors)
		{
				// Members area
				if ($select_user_type == 1) // Did we decide to register a new member ?
				{
					if (!function_exists('user_add')) // Including our function to register our member...
					{
						include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
					}
					
					// Which is the default group ?
						$sql = 'SELECT group_id
								FROM ' . GROUPS_TABLE . "
								WHERE group_name = 'REGISTERED'
								AND group_type = " . GROUP_SPECIAL;
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$default_group_id = $row['group_id'];
						
					// User password... sent with our welcome message sent by email
						$string		= str_shuffle('abcdefghjkmnpqrstuvwxyz123456789ABCDEFGHJKMNPQRSTUVWXYZ');
						$string2	= substr( $string , 0 , 3 ); // prendre les 10 1ers caractères.
						$string3	= str_shuffle('abcdefghjkmnpqrstuvwxyz123456789ABCDEFGHJKMNPQRSTUVWXYZ');
						$string4	= substr( $string3 , 0 , 5 ); // prendre les 10 1ers caractères
						$string5	= str_shuffle('abcdefghjkmnpqrstuvwxyz123456789ABCDEFGHJKMNPQRSTUVWXYZ');
						$string6	= substr( $string5 , 0 , 5 ); // prendre les 10 1ers caractères.
						
						$pass		= $string2 . $string4 . $string6;
			
					// User vars...
						$user_row = array(
							'user_type'             		=> USER_NORMAL,
							'group_id'              		=> $default_group_id,
							'user_regdate'             		=> $user_timestamp,
							'username'              		=> $select_user_name,
							'username_clean'              	=> utf8_clean_string($select_user_name),
							'user_password'              	=> phpbb_hash($pass),
							'user_email'              		=> $select_user_mail,
							'user_lang'              		=> 'fr',
						);
						$user_id = user_add($user_row);
						
						$this->user->setup(array('common', 'ucp')); // Some language files necessary...
					
					// Email template...
						/* 
							sending a welcome mail to registered user
							no implemented 
						*/
			
						if ($user_id)
						{
							add_log('admin', 'LOG_USER_ADDEd_ACTIVATED', $user_row['username']);	
						}					

				}
				
				// Posting area
					// Let's override online user's session
						$backup = array ( 
							'user' => $this->user,
							'user_id' => $this->user->data['user_id'],
							'user_ip' => $this->user->data['user_ip'],
							'user_name' => $this->user->data['username'],							
							'auth' => $this->auth, 
						);
					
					// Let's apply new sessions datas
						$posting_user_id = ($select_user_type == 2) ? $select_user_id : $user_id;
						
						$sql = 'SELECT *
								FROM ' . USERS_TABLE . '
								WHERE user_id = ' . $posting_user_id;
						$result = $this->db->sql_query($sql);
						$row = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result);

						$this->user->data = array_merge($this->user->data, $row);
						$this->auth->acl($this->user->data);

						$this->user->ip = '0.0.0.0 ';
					
					// Let's include some important files
						include($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);
						include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
						include($this->phpbb_root_path . 'includes/message_parser.' . $this->php_ext);
			
					// Some post parameters
						$poll = $uid = $bitfield = $options = '';

						generate_text_for_storage($select_post_title, $uid, $bitfield, $options, false, false, false);
						generate_text_for_storage($select_message, $uid, $bitfield, $options, true, true, true);

					// Some vars to insert into DB
						$data = array(
							'topic_id'			=> ($select_post_type == 1) ? '' : $select_topic_id,
							'forum_id'  		=> $select_forum_id,
							'icon_id'  			=> false,
							'poster_id'			=> $row['user_id'],
							'enable_bbcode' 	=> true,
							'enable_smilies'	=> true,
							'enable_urls'  		=> true,
							'enable_sig'  		=> true,

							'message'  			=> $select_message,
							'message_md5'   	=> md5($select_message),

							'bbcode_bitfield'   => $bitfield,
							'bbcode_uid'  		=> $uid,

							'post_edit_locked'  => 0,
							'topic_title'  		=> $select_post_title,
							'notify_set'  		=> false,
							'notify' 			=> true,
							'post_time'   		=> time(),
							'forum_name'  		=> '',
							'enable_indexing'   => true,  
						);
						
					// Submitting our post
					$post_reply = ($select_post_type == 1) ? 'post' : 'reply';
					
					submit_post($post_reply, $select_post_title, $row['username'], POST_NORMAL, $poll, $data);

					$this->user->data = array_merge($this->user->data, $backup);
					$this->auth->acl($this->user->data);
					$this->user->ip = (empty($user->ip)) ? '' : $user->ip;

					$log_entry = ($post_reply == 'post') ? 'TOPIC' : 'POST';
					add_log('mod', $select_forum_id, $data['topic_id'], 'LOG_NEW_' . $log_entry . '_ADDED', $select_post_title, $row['username']);

					// Updating post_time in database
						$sql = 'UPDATE ' . POSTS_TABLE . '
								SET post_time = ' . (int) $date_timestamp . '
								WHERE post_id = ' . (int) $data['post_id'];
						$result = $this->db->sql_query($sql);
						
						// We include necessary files
							include_once($this->phpbb_root_path . 'includes/functions_admin.' . $this->php_ext);
							include_once($this->phpbb_root_path . 'includes/functions_mcp.' .$this->php_ext);
				
						// We synchronise all
							sync('topic', 'topic_id', $data['topic_id'], true);
							sync('forum', 'forum_id', $data['forum_id'], true);		

		}//no errors
	}
	else
	{
		// Starting form no sent
	}

	// Some vars to template
		$this->template->assign_vars(array(
			'TITRE_PAGE'	=> $this->user->lang('POSTING_FORM_TITLE'),
			'ERRORS_LIST'		=> ($submit && $show_errors) ? $show_errors : '',
			'SHOW_ERRORS_LIST'	=> ($submit && sizeof($errors)) ? true : false,
			'ERRORS_EXPLAIN'	=> ($submit && sizeof($errors)) ? $this->user->lang('ERRORS_EXPLAIN', count($errors)) : '',

			'FORUM_ID'		=> ($select_forum_id) ? true : false,
			'POST_TYPE'		=> ($select_post_type) ? true : false,
			'TOPIC_ID'		=> ($select_topic_id) ? true : false,
			
			'POST_TYPE_OPTION'	=> $select_post_type,
			
			'POST_TITLE_FIELD'	=> $select_post_title,
			'POST_TEXT_FIELD'	=> $select_message,
			'POST_TEXT_DAY'		=> $select_post_time_day,
			'POST_TEXT_HOUR'	=> $select_post_time_hour,
	
			'SHOW_POST_TYPE'	=> ($select_forum_id) ? true : false,
			
			'SHOW_POST_FIELDS'	=> ($select_post_type == 1 || ($select_post_type == 2 && $select_topic_id)),
			
			'SHOW_TOPICS_LIST'	=> ($select_forum_id && $select_post_type == 2) ? true : false,
			'SHOW_USERS_LIST'	=> ($select_user_type == 2) ? true : false,
			
			'USERS_TYPE_OPTION'	=> $select_user_type,
			'USER_NAME_FIELD'	=> $select_user_name,
			'USER_MAIL_FIELD'	=> $select_user_mail,
			'USER_DAY_FIELD'	=> $select_user_day,
			'USER_TIME_FIELD'	=> $select_user_time,
		));

		// Header
		page_header($this->user->lang('POSTING_FORM_TITLE'));
		
		// Template file
		$this->template->set_filenames(array(
			'body' => 'posting_form.html')
		);

		// Footer
		page_footer();

	}
}