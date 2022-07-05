<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function ap_get_userGroup($utid) {
    $str = '';
    $tmpstr = ii_itake('global.user:api.group', 'tpl');
    if($utid != '-99'){
        $tutids = explode(',',$utid);
        foreach($tutids as $tutid) {
            $group = ii_itake('global.user:sel_group.'.$tutid, 'sel');
            $str .= str_replace('{$group}', $group, $tmpstr);
        }
    }else{
        $str = str_replace('{$group}', ii_itake('global.user:api.open', 'lng'), $tmpstr);
    }
    return $str;
}

function ap_check_userType($utid) {
    //判断指定用户分组ID是否跟当前登录用户一致
    $bool = false;
    $tutids = explode(',',$utid);
    $tusername = ii_get_safecode($_COOKIE[APP_NAME . 'user']['username']);
    if (!ii_isnull($tusername)) {
        $tusergroup = ii_get_num(ap_get_userinfo('utype', $tusername));
        $tuserlock = ii_get_num(ap_get_userinfo('lock', $tusername));
    }else{
        $tuserlock = 0;//未锁定
        $tusergroup = -1;//未登录
    }
    foreach($tutids as $tutid) {
        if ($tutid <= $tusergroup && $tuserlock == 0) {
                $bool = true;
                break;
        }
    }
    return $bool;
}

function ap_check_isuser($username)
{
  global $conn;
  $tdatabase = mm_cndatabase(USER_FOLDER);
  $tidfield = mm_cnidfield(USER_FOLDER);
  $tfpre = mm_cnfpre(USER_FOLDER);
  $tusername = ii_get_safecode($username);
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'username') . "='$tusername'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    if ($trs[ii_cfnames($tfpre, 'lock')] == 1) $treturn = 2;
    else $treturn = 1;
  }
  else $treturn = 0;
  return $treturn;
}

function ap_check_userlogin()
{
    //检查用户是否登录,session不为空
  if (!ii_isnull($_SESSION[APP_NAME . 'username'] && !ii_isnull($_SESSION[APP_NAME . 'password'])))
  {
      $tusername = $_SESSION[APP_NAME . 'username'];
      $tuserid = $_SESSION[APP_NAME . 'userid'];
      $tpassword = $_SESSION[APP_NAME . 'password'];
      header("Set-Cookie:".APP_NAME."user[username]=".$tusername.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
      header("Set-Cookie:".APP_NAME."user[userid]=".$tuserid.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
      header("Set-Cookie:".APP_NAME."user[password]=".$tpassword.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
      if (ap_check_username($tusername, $tpassword))
      {
        return true;
      }
  }
  else
  {
    //检查用户是否登录,cookie不为空
    $tuserid = ii_get_safecode($_COOKIE[APP_NAME . 'user']['userid']);
    $tusername = ii_get_safecode($_COOKIE[APP_NAME . 'user']['username']);
    $tpassword = ii_get_safecode($_COOKIE[APP_NAME . 'user']['password']);
    if (!ii_isnull($tusername) && !ii_isnull($tpassword))
    {
      if (ap_check_username($tusername, $tpassword))
      {
        $_SESSION[APP_NAME . 'username'] = $tusername;
        $_SESSION[APP_NAME . 'password'] = $tpassword;
        $_SESSION[APP_NAME . 'userid'] = $tuserid;
        return true;
      }
      else return false;
    }
    else return false;
  }
}

function ap_check_username($username, $password)
{
  global $conn;
  $tdatabase = mm_cndatabase(USER_FOLDER);
  $tidfield = mm_cnidfield(USER_FOLDER);
  $tfpre = mm_cnfpre(USER_FOLDER);
  $tusername = ii_get_safecode($username);
  $tpassword = ii_get_safecode($password);
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'username') . "='$tusername' and " . ii_cfnames($tfpre, 'password') . "='$tpassword' and " . ii_cfnames($tfpre, 'lock') . "=0";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tuserid = $trs[$tidfield];
    header("Set-Cookie:".APP_NAME."user[userid]=".$trs[$tidfield].";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
    header("Set-Cookie:".APP_NAME."user[username]=".$tusername.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
    header("Set-Cookie:".APP_NAME."user[password]=".$tpassword.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
    $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, 'pretime') . "=" . ii_cfnames($tfpre, 'lasttime') . "," . ii_cfnames($tfpre, 'lasttime') . "='" . ii_now() . "' where " . ii_cfnames($tfpre, 'username') . "='$tusername'";
    $trs = ii_conn_query($tsqlstr, $conn);
    $_SESSION[APP_NAME . 'username'] = $tusername;
    $_SESSION[APP_NAME . 'password'] = $tpassword;
    $_SESSION[APP_NAME . 'userid'] = $tuserid;
    return true;
  }
  else return false;
}

function ap_get_userid($username)
{
  global $conn;
  $tusername = ii_get_safecode($username);
  $tdatabase = mm_cndatabase(USER_FOLDER);
  $tidfield = mm_cnidfield(USER_FOLDER);
  $tfpre = mm_cnfpre(USER_FOLDER);
  $tsqlstr = "select " . $tidfield . " from $tdatabase where " . ii_cfnames($tfpre, 'username') . "='$tusername'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) return $trs[$tidfield];
}

function ap_get_userinfo($strers, $username)
{
  global $conn;
  $tstrers = ii_get_safecode($strers);
  $tusername = ii_get_safecode($username);
  $tdatabase = mm_cndatabase(USER_FOLDER);
  $tidfield = mm_cnidfield(USER_FOLDER);
  $tfpre = mm_cnfpre(USER_FOLDER);
  $tsqlstr = "select " . ii_cfnames($tfpre, $tstrers) . " from $tdatabase where " . ii_cfnames($tfpre, 'username') . "='$tusername'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) return $trs[ii_cfnames($tfpre, $tstrers)];
}

function ap_sel_usergroup($name, $type, $value)
{
  return ii_show_xmlinfo_select('global.' . USER_FOLDER . ':sel_group.all', $value, $name . ':' . $type);
}

function ap_user_login($tpl = '')
{
  global $nvalidate;
  if (!ii_isnull($tpl)) $tmpstr = @ii_itake($tpl, 'tpl');
  if (ii_isnull($tmpstr)) $tmpstr = ii_itake('global.' . USER_FOLDER . ':api.login', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
    $tmpary = explode('{@@}', $tmpastr);
    if (count($tmpary) != 2) return;
    if (!ap_check_userlogin()) $tmprstr = $tmpary[0];
    else $tmprstr = $tmpary[1];
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
    return $tmpstr;
  }
}

function ap_user_data_member_side()
{
  $tmpstr = ii_itake('global.' . USER_FOLDER . ':module.data_member_side', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tmpary = ii_itake('global.' . USER_FOLDER . ':member_menu.all', 'lng', 1);
  if (is_array($tmpary))
  {
    foreach($tmpary as $key => $val)
    {
      $tmptstr = str_replace('{$href}', $key, $tmpastr);
      $tmptstr = str_replace('{$explain}', $val, $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function ap_user_islogin($url = '')
{
  global $nurl;
  if (ii_isnull($url)) $turl = ii_get_actual_route(USER_FOLDER) . '/?type=login&backurl=' . urlencode($nurl);
  else $turl = ii_get_actual_route(USER_FOLDER) . '/?type=login&backurl=' . urlencode($url);
  if (!ap_check_userlogin()) mm_imessage(ii_itake('global.' . USER_FOLDER . ':api.nologin', 'lng'), $turl);
}

function ap_user_init()
{
  global $nusername,$nuserid;
  if (ap_check_userlogin()) $nusername = ii_get_safecode($_SESSION[APP_NAME . 'username']);
  if (ap_check_userlogin()) $nuserid = ii_get_safecode($_SESSION[APP_NAME . 'userid']);
}

function ap_update_userproperty($strers, $value, $type, $username)
{
  global $conn;
  $tstrers = ii_get_safecode($strers);
  $tusername = ii_get_safecode($username);
  $tdatabase = mm_cndatabase(USER_FOLDER);
  $tidfield = mm_cnidfield(USER_FOLDER);
  $tfpre = mm_cnfpre(USER_FOLDER);
  switch($type)
  {
    case 0:
      $tvalue = ii_get_num($value);
      $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, $tstrers) . "=" . ii_cfnames($tfpre, $tstrers) . "+$tvalue where " . ii_cfnames($tfpre, 'username') . "='$tusername'";
      break;
    case 1:
      $tvalue = ii_get_num($value);
      $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, $tstrers) . "=$tvalue where " . ii_cfnames($tfpre, 'username') . "='$tusername'";
      break;
    case 2:
      $tvalue = ii_get_safecode($value);
      $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, $tstrers) . "='$tvalue' where " . ii_cfnames($tfpre, 'username') . "='$tusername'";
      break;
  }
  if (!ii_isnull($tsqlstr)) return ii_conn_query($tsqlstr, $conn);
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>