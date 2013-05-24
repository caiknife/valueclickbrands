<?php
/***************************************************************************
 *                                login.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: login.php,v 1.1 2013/04/15 10:57:31 rock Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
   $sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
   $sid = '';
}

if ( $_GET['logout'] == 'true' && '' != $_COOKIE['DIU']){
   include('links.inc.php');
   include(__INCLUDE_ROOT.'etc/const.inc.php');
   setcookie("DIU","",-9999,"/",".".COOKIE_HOSTNAME);
   header('Location: /login.php?logout=true&sid='.$_GET['sid']);
   exit();                                       
/*
   setcookie('phpbb2mysql_data','',-9999,"/","forum.cm2.ace");
   setcookie('phpbb2mysql_t','',-9999,"/","forum.cm2.ace");
   setcookie('phpbb2mysql_sid','',-9999,"/","forum.cm2.ace");

   setcookie('phpbb2mysql_data','',-9999,"/","forum.couponmountain.com");
   setcookie('phpbb2mysql_t','',-9999,"/","forum.couponmountain.com");
   setcookie('phpbb2mysql_sid','',-9999,"/","forum.couponmountain.com");

   setcookie('phpbb2mysql_data','',-9999,"/","forums.couponmountain.com");
   setcookie('phpbb2mysql_t','',-9999,"/","forums.couponmountain.com");
   setcookie('phpbb2mysql_sid','',-9999,"/","forums.couponmountain.com");
*/
}

if( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout']) || (isset($_COOKIE['DIU']) && $_COOKIE['DIU'] ))
{
   //if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($_COOKIE['DIU'])) && !$userdata['session_logged_in'] )
   if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || ( isset($_COOKIE['DIU']) && '' != base64_decode($_COOKIE['DIU']) ) ) )
   {
      if ( isset($_COOKIE['DIU']) && $HTTP_POST_VARS['admin'] != 'login' ){
         $username = isset($_COOKIE['DIU']) ? base64_decode($_COOKIE['DIU']) : '';
         $sql = "SELECT Forum, Email, Password
            FROM Customer
            WHERE Email = '" . str_replace("\'", "''", $username) . "'";
         if ( !($result = $db->sql_query($sql)) )
         {
            message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
         }
         $password = '';
         if( $row = $db->sql_fetchrow($result) ){
            $password = $row['Password'];
            $username = $row['Forum'];
            $useremail= $row['Email'];
         }

         $sql = "SELECT user_id, username, user_password, user_active, user_level
            FROM " . USERS_TABLE . "
            WHERE user_email = '" . str_replace("\'", "''", $useremail) . "'";
         if ( !($result = $db->sql_query($sql)) )
         {
            message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
         }

         if( !($row = $db->sql_fetchrow($result)) || $row['username'] != $username ){
            if ( $row['username'] != $username && '' != $row['username'] ){
               $sql = "UPDATE " . USERS_TABLE . " SET username='".$username."', user_password='".md5($password)."' WHERE user_email='".$useremail."'";
               $db->sql_query($sql);
            }
            else{
               $sql = "SELECT MAX(user_id) AS max_user_id FROM " . USERS_TABLE;
               if ( !($result = $db->sql_query($sql)) )
               {
                  message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
               }
               $row = $db->sql_fetchrow($result);
               $sql = "INSERT INTO " . USERS_TABLE . " (user_id, username, user_email, user_password) values(".($row['max_user_id']+1).",'".$username."','".$useremail."','".md5($password)."')";
               $db->sql_query($sql);
            }
         }

      }
      else if ( isset($HTTP_POST_VARS['username']) ){
         $username = isset($HTTP_POST_VARS['username']) ? $HTTP_POST_VARS['username'] : '';
         $password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';
      }
      else if ( isset($HTTP_GET_VARS['username']) ){
         $username = isset($HTTP_GET_VARS['username']) ? $HTTP_GET_VARS['username'] : '';
         $password = isset($HTTP_GET_VARS['password']) ? $HTTP_GET_VARS['password'] : '';
      }

      $sql = "SELECT user_id, username, user_password, user_active, user_level
         FROM " . USERS_TABLE . "
         WHERE username = '" . str_replace("\'", "''", $username) . "'";
      if ( !($result = $db->sql_query($sql)) )
      {
         message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
      }

      if( $row = $db->sql_fetchrow($result) )
      {
         if( $row['user_level'] != ADMIN && $board_config['board_disable'] )
         {
            redirect(append_sid("index.$phpEx", true));
         }
         else
         {
            if( md5($password) == $row['user_password'] && $row['user_active'] )
            {
               //$autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;
               $autologin  = 0;

               $session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin);

               if( $session_id )
               {
                  $url = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : "index.$phpEx";
                  redirect(append_sid($url, true));
               }
               else
               {
                  message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
               }
            }
            else
            {
               $redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : '';
               $redirect = str_replace("?", "&", $redirect);

               $template->assign_vars(array(
                  'META' => '<meta http-equiv="refresh" content="3;url=' . "login.$phpEx?redirect=$redirect&amp;sid=" . $userdata['session_id'] . '">')
               );

               $message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . "login.$phpEx?redirect=$redirect&amp;sid=" . $userdata['session_id'] . '">', '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

               message_die(GENERAL_MESSAGE, $message);
            }
         }
      }
      else
      {
         $redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : "";
         $redirect = str_replace("?", "&", $redirect);

         $template->assign_vars(array(
            'META' => '<meta http-equiv="refresh" content="3;url=' . "login.$phpEx?redirect=$redirect&amp;sid=" . $userdata['session_id'] . '">')
         );

         $message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . "login.$phpEx?redirect=$redirect&amp;sid=" . $userdata['session_id'] . '">', '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

         message_die(GENERAL_MESSAGE, $message);
      }
   }
   else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $userdata['session_logged_in'] )
   {
      // session id check
      if ($sid == '' || $sid != $userdata['session_id'])
      {
         message_die(GENERAL_ERROR, 'Invalid_session');
      }

      if( $userdata['session_logged_in'] )
      {
         session_end($userdata['session_id'], $userdata['user_id']);
      }

      if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
      {
         $url = (!empty($HTTP_POST_VARS['redirect'])) ? $HTTP_POST_VARS['redirect'] : $HTTP_GET_VARS['redirect'];
         redirect(append_sid($url, true));
      }
      else
      {
         redirect(append_sid("index.$phpEx", true));
      }
   }
   else
   {
      $url = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : "index.$phpEx";
      redirect(append_sid($url, true));
   }
}
else
{
   //
   // Do a full login page dohickey if
   // user not already logged in
   //
   if( !$userdata['session_logged_in'] )
   {
      $page_title = $lang['Login'];
      include($phpbb_root_path . 'includes/page_header.'.$phpEx);

      $template->set_filenames(array(
         'body' => 'login_body.tpl')
      );

      if( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
      {
         $forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

         if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
         {
            $forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
            $forward_match = explode('&', $forward_to);

            if(count($forward_match) > 1)
            {
               $forward_page = '';

               for($i = 1; $i < count($forward_match); $i++)
               {
                  if( !ereg("sid=", $forward_match[$i]) )
                  {
                     if( $forward_page != '' )
                     {
                        $forward_page .= '&';
                     }
                     $forward_page .= $forward_match[$i];
                  }
               }
               $forward_page = $forward_match[0] . '?' . $forward_page;
            }
            else
            {
               $forward_page = $forward_match[0];
            }
         }
      }
      else
      {
         $forward_page = '';
      }

      $username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';

      $s_hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="redirect" value="' . $forward_page . '" />';

      make_jumpbox('viewforum.'.$phpEx, $forum_id);
      $template->assign_vars(array(
         'USERNAME' => $username,

         'L_ENTER_PASSWORD' => $lang['Enter_password'],
         'L_SEND_PASSWORD' => $lang['Forgotten_password'],

         'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),

         'S_HIDDEN_FIELDS' => $s_hidden_fields)
      );

      $template->pparse('body');

      include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
   }
   else
   {
      redirect(append_sid("index.$phpEx", true));
   }

}

?>
