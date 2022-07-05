<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function pp_check_isregister_close()
{
  global $ngenre, $variable;
  if (ii_get_num($variable[ii_cvgenre($ngenre) . '.register_close']) == 1) mm_imessage(ii_itake('module.register_close', 'lng'), -1);
}

function pp_check_islostpassword_close()
{
  global $ngenre, $variable;
  if (ii_get_num($variable[ii_cvgenre($ngenre) . '.lostpassword_close']) == 1) mm_imessage(ii_itake('module.lostpassword_close', 'lng'), -1);
}

function wdja_cms_module_logindisp()
{
  $tbackurl = $_GET['backurl'];
  if (ii_isnull($tbackurl)) $tbackurl = ii_get_actual_route('./');
  if (!mm_check_token()) mm_imessage(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl);
  mm_check_valcode($tbackurl);
  $tusername = ii_get_safecode($_POST['username']);
  $tpassword = md5(ii_get_safecode($_POST['password']));
  if (ap_check_username($tusername, $tpassword))
  {
    mm_client_redirect($tbackurl);
  }
  else mm_imessage(ii_itake('global.lng_error.login', 'lng'), $tbackurl);
}

function wdja_cms_module_logoutdisp()
{
  $tbackurl = $_GET['backurl'];
  if (ii_isnull($tbackurl)) $tbackurl = ii_get_actual_route('./');
  header("Set-Cookie:".APP_NAME."user[userid]='';path =".COOKIES_PATH.";expires=".gmdate('D, d M Y H:i:s \G\M\T', time()-1).";",false);
  header("Set-Cookie:".APP_NAME."user[username]='';path =".COOKIES_PATH.";expires=".gmdate('D, d M Y H:i:s \G\M\T', time()-1).";",false);
  header("Set-Cookie:".APP_NAME."user[password]='';path =".COOKIES_PATH.";expires=".gmdate('D, d M Y H:i:s \G\M\T', time()-1).";",false);
  unset($_SESSION[APP_NAME . 'username']);
  mm_client_redirect($tbackurl);
}

function wdja_cms_module_registerdisp()
{
  global $ctype;
  $ctype = 'register';
  global $Err;
  if (!mm_check_token()) mm_imessage(ii_itake('global.lng_error.token_error', 'lng'), '-1');
  if (ap_check_isuser(stripslashes($_POST['username'])) == 1) mm_imessage(ii_itake('global.user:validator.check_username_1', 'lng'), '-1');
  if (!mm_ck_valcode()) $Err[count($Err)] = ii_itake('global.lng_error.valcode', 'lng');
  $tckstr = 'username:' . ii_itake('config.username', 'lng') . ',password:' . ii_itake('config.password', 'lng') . ',email:' . ii_itake('config.email', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  $tRegUserName = stripslashes($_POST['username']);
  $tRegLimit = '&,\',<,>,#,+,-,/,*,@,$,%,^,' . chr(32) . ',' . chr(9) . ',;';
  $tRegLimitAry = explode(',', $tRegLimit);
  foreach ($tRegLimitAry as $key => $val)
  {
    if (is_numeric(strpos($tRegUserName, $val)))
    {
      $Err[count($Err)] = ii_itake('module.insert_limit', 'lng');
      continue;
    }
  }
  if (ii_strlen($tRegUserName) < 2 || ii_strlen($tRegUserName) > 16) $Err[count($Err)] = ii_itake('module.insert_length', 'lng');
  if ($_POST['password'] != $_POST['cpassword']) $Err[count($Err)] = ii_itake('module.insert_checkout', 'lng');
  if (!ii_isvalidemail($_POST['email'])) $Err[count($Err)] = ii_itake('module.insert_email', 'lng');
  if (count($Err) > 0) mm_imessage($Err[0], '-1');
  if (!is_array($Err) || count($Err) == 0)
  {
    global $conn;
    global $ndatabase, $nidfield, $nfpre;
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('username') . "='$tRegUserName'";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs) $Err[count($Err)] = ii_itake('module.insert_exist', 'lng');
    else
    {
      $tsqlstr = "insert into $ndatabase (
      " . ii_cfname('username') . ",
      " . ii_cfname('password') . ",
      " . ii_cfname('email') . ",
      " . ii_cfname('openid') . ",
      " . ii_cfname('nickname') . ",
      " . ii_cfname('headimgurl') . ",
      " . ii_cfname('face') . ",
      " . ii_cfname('time') . "
      ) values (
      '$tRegUserName',
      '" . ii_md5($_POST['password']) . "',
      '" . ii_left(ii_cstr($_POST['email']), 50) . "',
      '" . ii_left(ii_cstr($_POST['openid']), 250) . "',
      '" . ii_left(ii_cstr($_POST['nickname']), 50) . "',
      '" . ii_left(ii_cstr($_POST['headimgurl']), 255) . "',
      '" . ii_left(ii_cstr($_POST['face']), 255) . "',
      '" . ii_now() . "'
      )";
      $trs = ii_conn_query($tsqlstr, $conn);
      if ($trs)
      {
        $userid = ii_conn_insert_id($conn);
        header("Set-Cookie:".APP_NAME."user[userid]=".$userid.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
        header("Set-Cookie:".APP_NAME."user[username]=".$tRegUserName.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
        header("Set-Cookie:".APP_NAME."user[password]=".md5($_POST['password']).";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
        $_SESSION[APP_NAME . 'username'] = $tRegUserName;
        mm_imessage(ii_itake('global.lng_public.succeed', 'lng'), ii_get_actual_route('./'));
      }
      else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');
    }
  }
}

function wdja_cms_module_lostpassworddisp()
{
  $tbackurl = $_GET['backurl'];
  if (!mm_check_token()) mm_imessage(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl);
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tusername = ii_get_safecode($_POST['username']);
  $temail = ii_get_safecode($_POST['email']);
  $tname = ii_get_safecode($_POST['name']);
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('username') . "='$tusername' and " . ii_cfname('email') . "='$temail' and " . ii_cfname('name') . "='$tname'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tpassword = ii_random(8);
    $tmd5password = md5($tpassword);
    $ttopic = ii_itake('module.lostpassword_topic', 'lng');
    $ttopic = str_replace('[]', '[' . ii_itake('global.module.web_title', 'lng') . ']', $ttopic);
    $tbody = ii_itake('module.lostpassword_body', 'lng');
    $tbody = str_replace('[name]', ii_htmlencode($tname), $tbody);
    $tbody = str_replace('[username]', ii_htmlencode($tusername), $tbody);
    $tbody = str_replace('[password]', ii_htmlencode($tpassword), $tbody);
    if (mm_sendemail($temail, $ttopic, $tbody))
    {
      $tsqlstr = "update $ndatabase set " . ii_cfname('password') . "='$tmd5password' where " . ii_cfname('username') . "='$tusername'";
      ii_conn_query($tsqlstr, $conn);
      mm_imessage(ii_itake('module.lostpassword_emailok', 'lng'));
    }
    else mm_imessage(ii_itake('module.lostpassword_emailerror', 'lng'));
  }
  else mm_imessage(ii_itake('module.lostpassword_infoerror', 'lng'), $tbackurl);
}

function wdja_cms_module_manage_informationdisp()
{
  $tbackurl = $_GET['backurl'];
  if (!mm_check_token()) mm_imessage(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl);
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $nusername;
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('username') . "='$nusername'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tsqlstr = "update $ndatabase set
    " . ii_cfname('email') . "='" . ii_left(ii_cstr($_POST['email']), 50) . "',
    " . ii_cfname('face') . "='" . ii_left(ii_cstr($_POST['face']), 255) . "',
    " . ii_cfname('sign') . "='" . ii_left(ii_cstr($_POST['sign']), 100) . "',
    " . ii_cfname('name') . "='" . ii_left(ii_cstr($_POST['name']), 50) . "',
    " . ii_cfname('sex') . "=" . ii_get_num($_POST['sex']) . ",
    " . ii_cfname('qq') . "=" . ii_get_num($_POST['qq']) . ",
    " . ii_cfname('phone') . "='" . ii_left(ii_cstr($_POST['phone']), 50) . "',
    " . ii_cfname('address') . "='" . ii_left(ii_cstr($_POST['address']), 255) . "'
    where " . ii_cfname('username') . "='$nusername'";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) mm_imessage(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl);
    else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl);
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl);
}

function wdja_cms_module_manage_passworddisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $nusername;
  $tbackurl = $_GET['backurl'];
  if (!mm_check_token()) mm_imessage(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl);
  $tpassword = md5($_POST['password']);
  $tnpassword = md5($_POST['npassword']);
  $tncpassword = md5($_POST['ncpassword']);
  if ($tnpassword != $tncpassword) mm_imessage(ii_itake('module.insert_checkout', 'lng'), $tbackurl);
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('username') . "='$nusername' and " . ii_cfname('password') . "='$tpassword'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tsqlstr = "update $ndatabase set " . ii_cfname('password') . "='$tnpassword' where " . ii_cfname('username') . "='$nusername'";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      header("Set-Cookie:".APP_NAME."user[password]=".$tnpassword.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
      mm_imessage(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl);
    }
    else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl);
  }
  else mm_imessage(ii_itake('module.insert_password', 'lng'), $tbackurl);
}

function wdja_cms_module_managedisp()
{
  switch($_GET['mtype'])
  {
    case 'information':
      wdja_cms_module_manage_informationdisp();
      break;
    case 'password':
      wdja_cms_module_manage_passworddisp();
      break;
  }
}

function wdja_cms_module_action()
{
  switch($_GET['action'])
  {
    case 'login':
      wdja_cms_module_logindisp();
      break;
    case 'logout':
      wdja_cms_module_logoutdisp();
      break;
    case 'register':
      pp_check_isregister_close();
      return wdja_cms_module_registerdisp();
      break;
    case 'lostpassword':
      pp_check_islostpassword_close();
      return wdja_cms_module_lostpassworddisp();
      break;
    case 'manage':
      wdja_cms_module_managedisp();
      break;
    case 'upload':
      ap_user_islogin();
      uu_upload_files();
      break;
  }
}

function wdja_cms_module_weixin_notify()
{
  $tbackurl = $_GET['backurl'];
  if (ii_isnull($tbackurl)) $tbackurl = ii_get_actual_route('./');
  mm_check_valcode($tbackurl);
  $userInfo = wxlogin_notify();
  //判断openid是否存在,如存在则生成登录信息和有效期
  //不存在则进行注册绑定
  if (is_array($userInfo)) {
    $openid = $userInfo['openid'];
    if (wxlogin_check_isuser($openid)) mm_client_redirect($tbackurl);
    else return wdja_cms_module_weixin_register($userInfo);
  }
  else  mm_client_redirect($tbackurl);
}

function wdja_cms_module_login()
{
  global $nvalidate, $ngenre;
  $tmpstr = ii_itake('module.login', 'tpl');
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_user_detail()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tusername = ii_get_safecode($_GET['username']);
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('username') . "='$tusername'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('module.user_detail', 'tpl');
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.not_exist', 'lng'), -1);
}

function wdja_cms_module_lostpassword()
{
  global $nvalidate, $ngenre;
  $tmpstr = ii_itake('module.lostpassword', 'tpl');
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_weixin_register($userInfo)
{
  global $nvalidate;
  $topenid = $userInfo['openid'];
  $tnickname = $userInfo['nickname'];
  $theadimgurl = $userInfo['headimgurl'];
  $tmpstr = ii_itake('module.weixin_register', 'tpl');
  $tmpstr = str_replace('{$openid}', $topenid, $tmpstr);
  $tmpstr = str_replace('{$nickname}', $tnickname, $tmpstr);
  $tmpstr = str_replace('{$headimgurl}', $theadimgurl, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_register()
{
  global $nvalidate, $ngenre;
  $tmpstr = ii_itake('module.register', 'tpl');
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_manage_member()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $nusername;
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('username') . "='$nusername'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('module.manage_member', 'tpl');
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    if ($trs[ii_cfname('utype')] == 1 ) $buy_type = 'upvip';
    elseif ($trs[ii_cfname('utype')] == 2 ) $buy_type = 'topvip';
    else $buy_type = 'buyvip';
    $GLOBALS['RS_buy_type'] = $buy_type;
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$buy_type}', $buy_type, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}

function wdja_cms_module_manage_information()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $nusername;
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('username') . "='$nusername'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('module.manage_information', 'tpl');
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}

function wdja_cms_module_manage_password()
{
  return ii_ireplace('module.manage_password', 'tpl');
}

function wdja_cms_module_manage()
{
  switch(mm_ctype($_GET['mtype'], 1))
  {
    case 'member':
      return wdja_cms_module_manage_member();
      break;
    case 'information':
      return wdja_cms_module_manage_information();
      break;
    case 'password':
      return wdja_cms_module_manage_password();
      break;
    default:
      return wdja_cms_module_manage_member();
      break;
  }
}

function wdja_cms_module_premise()
{
  return ii_ireplace('module.premise', 'tpl');
}

function wdja_cms_module_wxpayjs($torderid,$title,$tprice,$body)
{
  global $nvalidate, $conn, $nurlpre;
  $notify_url = $nurlpre.'/user/payjs_notify.php';
  $callback_url = $nurlpre.'/user/';
  $result = wxpayjs($torderid,$title,$tprice,$body,$notify_url,$callback_url);
  if (is_array($result))
  {
    if ($result['return_code'] == 1 && $result['return_msg'] == 'SUCCESS') {
      if(ii_isMobileAgent()) {
        $tmpstr = ii_itake('module.mwxpayjs' , 'tpl');
        $tmpstr = str_replace('{$out_trade_no}', $result['out_trade_no'], $tmpstr);
        $tmpstr = str_replace('{$payjs_order_id}', $result['payjs_order_id'], $tmpstr);
        $tmpstr = str_replace('{$total_fee}', $result['total_fee']/100, $tmpstr);
        $tmpstr = str_replace('{$pay_url}', $result['h5_url'], $tmpstr);
        $tmpstr = ii_creplace($tmpstr);
        return $tmpstr;
      }else{
        $tmpstr = ii_itake('module.wxpayjs', 'tpl');
        $tmpstr = str_replace('{$out_trade_no}', $result['out_trade_no'], $tmpstr);
        $tmpstr = str_replace('{$payjs_order_id}', $result['payjs_order_id'], $tmpstr);
        $tmpstr = str_replace('{$total_fee}', $result['total_fee']/100, $tmpstr);
        $tmpstr = str_replace('{$qrcode}', $result['qrcode'], $tmpstr);
        $tmpstr = ii_creplace($tmpstr);
        return $tmpstr;
      }
    }
    else mm_imessage($result['return_msg'], '-1');
  }
  else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');
}

function wdja_cms_module_alipayjs($torderid,$title,$tprice,$body)
{
  global $nvalidate, $conn, $nurlpre;
  $notify_url = $nurlpre.'/user/payjs_notify.php';
  $callback_url = $nurlpre.'/user/';
  $result = alipayjs($torderid,$title,$tprice,$body,$notify_url,$callback_url);
  if (is_array($result))
  {
    if ($result['return_code'] == 1 && $result['return_msg'] == 'SUCCESS') {
      if(ii_isMobileAgent()) {
        $tmpstr = ii_itake('module.mwxpayjs' , 'tpl');
        $tmpstr = str_replace('{$out_trade_no}', $result['out_trade_no'], $tmpstr);
        $tmpstr = str_replace('{$payjs_order_id}', $result['payjs_order_id'], $tmpstr);
        $tmpstr = str_replace('{$total_fee}', $result['total_fee']/100, $tmpstr);
        $tmpstr = str_replace('{$pay_url}', $result['h5_url'], $tmpstr);
        $tmpstr = ii_creplace($tmpstr);
        return $tmpstr;
      }else{
        $tmpstr = ii_itake('module.alipayjs', 'tpl');
        $tmpstr = str_replace('{$out_trade_no}', $result['out_trade_no'], $tmpstr);
        $tmpstr = str_replace('{$payjs_order_id}', $result['payjs_order_id'], $tmpstr);
        $tmpstr = str_replace('{$total_fee}', $result['total_fee']/100, $tmpstr);
        $tmpstr = str_replace('{$qrcode}', $result['qrcode'], $tmpstr);
        $tmpstr = ii_creplace($tmpstr);
        return $tmpstr;
      }
    }
    else mm_imessage($result['return_msg'], '-1');
  }
  else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');
}

function wdja_cms_module_pay_success()
{
  global $conn,  $variable;
  $tbackurl = $_GET['backurl'];
  if (ii_isnull($tbackurl)) $tbackurl = ii_get_actual_route('./').'user/shopcart/?type=order_list';
  $ndatabase = $variable['user.shopcart.ndatabase'];
  $nidfield = $variable['user.shopcart.nidfield'];
  $nfpre = $variable['user.shopcart.nfpre'];
  $tprice = ii_get_num($_POST['price']);
  $torderid = ii_get_num($_POST['orderid']);
  $tid = ii_get_num($_POST['id']);
  if ($tprice == '0') {
    $tsqlstr = "update $ndatabase set " . ii_cfnames($nfpre,'prepaid') . "=1 where " . $nidfield . "='$tid' and " . ii_cfnames($nfpre, 'orderid') . "='$torderid' and " . ii_cfnames($nfpre, 'allprice') . "='$tprice' and " . ii_cfnames($nfpre,'prepaid') . "=0";
    $trs = ii_conn_query($tsqlstr, $conn);
    if (ii_conn_affected_rows($conn) > 0) mm_imessage(ii_itake('global.lng_public.pay_succeed', 'lng'), $tbackurl);
    else mm_imessage(ii_itake('global.lng_public.pay_failed', 'lng'), $tbackurl);
  }
  else mm_imessage(ii_itake('global.lng_public.pay_failed', 'lng'), $tbackurl);
}

function wdja_cms_module_pay()
{
  global $nvalidate;
  global $global_images_route, $nskin;
  $tweb_title = ii_itake('global.support/global:basic.web_name', 'lng');
  $genre = 'user/recharge';
  $tid = $_POST['id'];//订单ID
  $torderid = $_POST['orderid'];//post提交订单号
  $tprice = $_POST['price'];//post提交价格
  $orderid = mm_get_field($genre,$tid,'orderid');//订单表订单号
  $price = mm_get_field($genre,$tid,'price');//订单表价格
  if ($torderid != $orderid || $tprice != $price) mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');
  $tpayment = $_POST['payment'];//支付方式ID
  $title = ii_itake('global.user/recharge:module.order', 'lng').$torderid;
  $body = $tweb_title;
  switch($tpayment)
  {
    case '1':
      return wdja_cms_module_wxpayjs($torderid,$title,$tprice,$body);//个人微信payjs接口
      break;
    case '2':
      return wdja_cms_module_alipayjs($torderid,$title,$tprice,$body);//个人支付宝payjs接口
      break;
    case '3':
      return wxpay($torderid,$title,$tprice,$body);//企业微信
      break;
    case '4':
      return alipay($torderid,$title,$tprice,$body);//企业支付宝
      break;
    default:
      return wdja_cms_module_wxpayjs($torderid,$title,$tprice,$body);//个人微信payjs接口
      break;
  }
}

function wdja_cms_module_alipay_notify()
{
  global $conn, $variable;

  $genre = 'user/recharge';
  $ndatabase = $variable[ii_cvgenre($genre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($genre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($genre) . '.nfpre'];

  $alipay_data = alipay_notify();
  if (is_array($alipay_data)) {
    $torderid = $_GET['out_trade_no'];
    $tprice = ii_get_num($_GET['total_amount']);
    $ttrade_no = ii_get_num($_GET['trade_no']);
    $tseller_id = ii_get_num($_GET['seller_id']);
    $ttimestamp = ii_get_num($_GET['timestamp']);

    $tsqlstr = "update $ndatabase set " . ii_cfnames($nfpre,'state') . "=1," . ii_cfnames($nfpre,'prepaid') . "=1," . ii_cfnames($nfpre,'trade_no') . "='$ttrade_no'," . ii_cfnames($nfpre,'seller_id') . "='$tseller_id'," . ii_cfnames($nfpre,'timestamp') . "='$ttimestamp' where " . ii_cfnames($nfpre, 'orderid') . "='$torderid'";
    $trs = ii_conn_query($tsqlstr, $conn);
    if (ii_conn_affected_rows($conn) > 0) {
      $tusername = mm_get_field($genre,$tid,'username');
      $tuserid = ap_get_userid($tusername);//当前订单会员ID
      $tmoney = ap_get_userinfo('money', $tusername);//当前会员的余额
      if (mm_update_field('user',$tuserid,'money',$tmoney + $tprice)) {
        $nmoney = ap_get_userinfo('money', $tusername);//当前会员的最新余额
        if ($tprice == ($nmoney-$tmoney)) mm_update_field($genre,$tid,'lock',1);
      }
    }
  }
}

function wdja_cms_module_alipay_return()
{
  global $conn,$variable;
  $tbackurl = $_GET['backurl'];
  if (ii_isnull($tbackurl)) $tbackurl = ii_get_actual_route('./').'user/';
  $genre = 'user/recharge';
  $ndatabase = $variable[ii_cvgenre($genre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($genre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($genre) . '.nfpre'];

  $torderid = $_GET['out_trade_no'];
  $tprice = ii_get_num($_GET['total_amount']);
  $ttrade_no = ii_get_num($_GET['trade_no']);
  $tseller_id = ii_get_num($_GET['seller_id']);
  $ttimestamp = $_GET['timestamp'];

  $tid = mm_get_id($genre,$torderid,'orderid');//充值订单id
  $tprepaid = mm_get_field($genre,$tid,'prepaid');//充值订单支付状态
  if ($tprepaid == 1) mm_imessage(ii_itake('module.pay_finish', 'lng'), $tbackurl);
  $orderid = mm_get_field($genre,$tid,'orderid');//订单表订单号
  $price = mm_get_field($genre,$tid,'price');//订单表价格
  if ($torderid != $orderid || $tprice != $price) mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');

  $tsqlstr = "update $ndatabase set " . ii_cfnames($nfpre,'state') . "=1," . ii_cfnames($nfpre,'prepaid') . "=1," . ii_cfnames($nfpre,'trade_no') . "='$ttrade_no'," . ii_cfnames($nfpre,'seller_id') . "='$tseller_id'," . ii_cfnames($nfpre,'timestamp') . "='$ttimestamp' where " . ii_cfnames($nfpre, 'orderid') . "='$torderid'";
  $trs = ii_conn_query($tsqlstr, $conn);
  if (ii_conn_affected_rows($conn) > 0) {
    $tusername = mm_get_field($genre,$tid,'username');
    $tuserid = ap_get_userid($tusername);//当前订单会员ID
    $tmoney = ap_get_userinfo('money', $tusername);//当前会员的余额
    if (mm_update_field('user',$tuserid,'money',$tmoney + $tprice)) {
      $nmoney = ap_get_userinfo('money', $tusername);//当前会员的最新余额
      if (!bccomp($tprice,$nmoney-$tmoney)) {
        if (mm_update_field($genre,$tid,'lock',1)) mm_imessage(ii_itake('module.pay_success', 'lng'), $tbackurl);
      }
      else mm_imessage(ii_itake('module.pay_fail', 'lng'), $tbackurl);
    }
  }
  else mm_imessage(ii_itake('module.pay_fail', 'lng'), $tbackurl);
}

function wdja_cms_module_wxpay_notify()
{
  global $conn, $variable;
  $genre = 'user/recharge';
  $ndatabase = $variable[ii_cvgenre($genre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($genre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($genre) . '.nfpre'];
  $wxpay_data = wxpay_notify();
  if (is_array($wxpay_data)) {
    $torderid = $wxpay_data['out_trade_no'];
    $tsqlstr = "update $ndatabase set " . ii_cfnames($nfpre,'prepaid') . "=1 where " . ii_cfnames($nfpre, 'orderid') . "='$torderid'";
    $trs = ii_conn_query($tsqlstr, $conn);
  }
}

function wdja_cms_module_payjs_notify()
{
  global $conn,$variable;
  $genre = 'user/recharge';
  $ndatabase = $variable[ii_cvgenre($genre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($genre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($genre) . '.nfpre'];
  $result = array();
  $result = payjs_notify();
  if ($result['state'] == 1) {
    $data = $result['data'];
    $torderid = $data['out_trade_no'];
    $tid = mm_get_id($genre,$torderid,'orderid');//充值订单id
    $tprice = $data['total_fee']/100;
    $tseller_id = $data['payjs_order_id'];
    $ttimestamp = $data['time_end'];
    $tsqlstr = "update $ndatabase set " . ii_cfnames($nfpre,'state') . "=1," . ii_cfnames($nfpre,'state') . "=1," . ii_cfnames($nfpre,'prepaid') . "=1," . ii_cfnames($nfpre,'seller_id') . "='$tseller_id'," . ii_cfnames($nfpre,'timestamp') . "='$ttimestamp' where " . ii_cfnames($nfpre, 'orderid') . "='$torderid'";
    $trs = ii_conn_query($tsqlstr, $conn);
    if (ii_conn_affected_rows($conn) > 0) {
      $tusername = mm_get_field($genre,$tid,'username');
      $tuserid = ap_get_userid($tusername);//当前订单会员ID
      $tmoney = ap_get_userinfo('money', $tusername);//当前会员的余额
      if (mm_update_field('user',$tuserid,'money',$tmoney + $tprice)) {
        $nmoney = ap_get_userinfo('money', $tusername);//当前会员的最新余额
        if (!bccomp($tprice,$nmoney-$tmoney)) {
          if (mm_update_field($genre,$tid,'lock',1)) echo 'success';
        }
        else echo 'fail';
      }
    }
  }
  else echo 'fail';
}

function wdja_cms_module()
{
  $wxlogin_switch = ii_itake('global.support/global:weixin.wxlogin_switch','lng');
  switch(mm_ctype($_GET['type']))
  {
    case 'login':
      if (ii_isWeixin() && $wxlogin_switch == 1) return wxlogin();
      else return wdja_cms_module_login();
      break;
    case 'wxnotify':
      return wdja_cms_module_weixin_notify();
      break;
    case 'user_detail':
      ap_user_islogin();
      return wdja_cms_module_user_detail();
      break;
    case 'lostpassword':
      pp_check_islostpassword_close();
      return wdja_cms_module_lostpassword();
      break;
    case 'register':
      pp_check_isregister_close();
      return wdja_cms_module_register();
      break;
    case 'manage':
      ap_user_islogin();
      return wdja_cms_module_manage();
      break;
    case 'pay':
      ap_user_islogin();
      return wdja_cms_module_pay();
      break;
    case 'upload':
      ap_user_islogin();
      uu_upload_files_html('upload_html');
      break;
    default:
      ap_user_islogin();
      return wdja_cms_module_manage();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>