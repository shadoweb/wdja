<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function delByValue($arr, $value) {
    $keys = array_keys($arr, $value);
    if (!empty($keys)) {
        foreach ($keys as $key) {
            unset($arr[$key]);
        }
    }
    return $arr;
}

function pp_get_module_select($module='')
{
  global $variable;
  $tary = ii_get_valid_module();
  if (is_array($tary))
  {
    $tmpstr = '';
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    foreach ($tary as $key => $val)
    {
      if (!ii_isnull($module) && $val == $module) $tmprstr = $option_selected;
      else $tmprstr = $option_unselected;
      if (!ii_isnull($variable[ii_cvgenre($val) . '.nglobal'])) {
        $tmprstr = str_replace('{$explain}', '(' . mm_get_genre_title($val) . ')' , $tmprstr);
        $tmprstr = str_replace('{$value}', $val, $tmprstr);
      }
      else continue;
      $tmpstr .= $tmprstr;
    }
    return $tmpstr;
  }
}

function pp_get_xml_root($module)
{
  global $ngenre;
  $tmpstr = ii_get_actual_route($ngenre);
  if (ii_right($tmpstr, 1) != '/') $tmpstr .= '/';
  $tmproot = 'common/language/';
  $tmpstr = $tmpstr . $tmproot . $module;
  return $tmpstr;
}

function pp_get_const_define($key)
{
    $info=file_get_contents('../../common/incfiles/const.inc.php');
    preg_match_all("/define\('(.*?)','(.*?)'\);/",$info,$arr);
    for($i=0;$i<count($arr[1]);$i++) {
         if ($key == $arr[1][$i]) return $arr[2][$i];
    }
}

function pp_update_const_define($config)
{
    $bool=false;
    $info=file_get_contents('../../common/incfiles/const.inc.php');
    foreach($config as $key => $val) {
        $old_inf = "/define\('".$key."','(.*?)'\);/";
        $new_inf = "define('".$key."','".$val."');";
        if(defined($key)) $info = preg_replace($old_inf,$new_inf,$info);
    }
    $res = file_put_contents('../../common/incfiles/const.inc.php',$info);
    if ($res) $bool=true;
    return $bool;
}

function pp_update_const_config($config)
{
    $bool=false;
    $info=file_get_contents('../../common/incfiles/const.inc.php');
    foreach($config as $key => $val) {
        $old_inf = "/[$]".$key."(.*?)=(.*?)'(.*?)';/";
        $new_inf = "$".$key." = '".$val."';";
        $info = preg_replace($old_inf,$new_inf,$info);
    }
    $res = file_put_contents('../../common/incfiles/const.inc.php',$info);
    if ($res) $bool=true;
    return $bool;
}

function wdja_cms_admin_manage_editdisp_basic()
{
  global $nsaveimages;
  $tbackurl = $_GET['backurl'];
  if (!mm_check_token()) wdja_cms_admin_msg(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl, 1);
  $tburl = pp_get_xml_root('basic') . XML_SFX;
  $tnode = 'item';
  $tfield = 'disinfo,chinese';
  $tbase = 'language_list';
  $torder = 'web_name,web_logo,web_icon,subweb_switch,subweb_folder,web_state,web_state_tips,web_tongji,web_beian,web_copyright,runtime_switch,mirror_switch,mirror_url,contacts_name,contacts_tel,contacts_qq,contacts_weixin,contacts_email,contacts_address,';
  if (ii_right($torder, 1) == ',') $torder = ii_left($torder, (strlen($torder) - 1));
  if (file_exists($tburl) && (!ii_isnull($tnode)) && (!ii_isnull($tfield)) && (!ii_isnull($tbase)))
  {
    pp_update_const_define(
    array(
    'DEFAULT_URL' => $_POST['default_url'],
    'MOBILE_URL' => $_POST['mobile_url']
    ));
    $tmpstr = '';
    $tmode = ii_get_xrootatt($tburl, 'mode');
    $tfieldary = explode(',', $tfield);
    $torderary = explode(',', $torder);
    $tub = count($tfieldary);
    $tmpstr .= '<?xml version="1.0" encoding="' . CHARSET . '"?>' . CRLF;
    $tmpstr .= '<xml mode="' . $tmode . '" author="wdja">' . CRLF;
    $tmpstr .= '  <configure>' . CRLF;
    $tmpstr .= '    <node>' . $tnode . '</node>' . CRLF;
    $tmpstr .= '    <field>' . $tfield . '</field>' . CRLF;
    $tmpstr .= '    <base>' . $tbase . '</base>' . CRLF;
    $tmpstr .= '  </configure>' . CRLF;
    $tmpstr .= '  <' . $tbase . '>' . CRLF;
    foreach($torderary as $key => $val)
    {
      $tmpstr .= '    <' . $tnode . '>' . CRLF;
      $tmpstr .= '      <' . $tfieldary[0] . '><![CDATA[' . $val . ']]></' . $tfieldary[0] . '>' . CRLF;
      if ($nsaveimages == '1' && $val == 'content') $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . saveimages($_POST[$val]) . ']]></' . $tfieldary[1] . '>' . CRLF;
      else $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . $_POST[$val] . ']]></' . $tfieldary[1] . '>' . CRLF;
      $tmpstr .= '    </' . $tnode . '>' . CRLF;
    }
    $tmpstr .= '  </' . $tbase . '>' . CRLF;
    $tmpstr .= '</xml>';
    if (file_put_contents($tburl, $tmpstr)){
        sleep(1);
        wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_editdisp_core()
{
    $tbackurl = $_GET['backurl'];
    if (!mm_check_token()) wdja_cms_admin_msg(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl, 1);
    $api_id = pp_get_const_define('API_ID');
    $api_key = pp_get_const_define('API_KEY');
    $api_idstr = "abcdefghijklmnopqrstuvwxyz0123456789";
    $api_idrand = '';
    $api_keyrand = '';
    for ( $i = 0; $i < 16; $i++ ) {
      $api_idrand.= substr($api_idstr, mt_rand(0, strlen($api_idstr)-1), 1);  
    }
    if (ii_isnull($api_id) || ii_isnull($_POST['api_id'])) $api_id = $api_idrand;
    $api_keyrand = md5($api_id . SYS_NAME);
    if ($api_key != $api_keyrand) $api_key = $api_keyrand;
    $res = pp_update_const_define(
    array(
    'DEFAULT_LANGUAGE' => $_POST['default_language'],
    'DEFAULT_SKIN' => $_POST['default_skin'],
    'MOBILE_SKIN' => $_POST['mobile_skin'],
    'ROBOTS_SKIN' => $_POST['robots_skin'],
    'DB_HOST' => $_POST['db_host'],
    'DB_USERNAME' => $_POST['db_username'],
    'DB_PASSWORD' => $_POST['db_password'],
    'DB_DATABASE' => $_POST['db_database'],
    'API_ID' => $api_id,
    'API_KEY' => $api_key,
    'OSS_SWITCH' => $_POST['oss_switch'],
    'OSS_ID' => $_POST['oss_id'],
    'OSS_KEY' => $_POST['oss_key'],
    'OSS_POINT' => $_POST['oss_point'],
    'OSS_BUCKET' => $_POST['oss_bucket'],
    'OSS_BACK' => $_POST['oss_back']
    ));
    sleep(1);
    if ($res) wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_editdisp_seo()
{
  $tbackurl = $_GET['backurl'];
  if (!mm_check_token()) wdja_cms_admin_msg(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl, 1);
  $tburl = pp_get_xml_root('seo') . XML_SFX;
  $tnode = 'item';
  $tfield = 'disinfo,chinese';
  $tbase = 'language_list';
  $torder = 'web_topic,web_keywords,web_description,baidupush_url,baidupush_token,baidupush_switch,';
  if (ii_right($torder, 1) == ',') $torder = ii_left($torder, (strlen($torder) - 1));
  if (file_exists($tburl) && (!ii_isnull($tnode)) && (!ii_isnull($tfield)) && (!ii_isnull($tbase)))
  {
    $tmpstr = '';
    $tmode = ii_get_xrootatt($tburl, 'mode');
    $tfieldary = explode(',', $tfield);
    $torderary = explode(',', $torder);
    $tub = count($tfieldary);
    $tmpstr .= '<?xml version="1.0" encoding="' . CHARSET . '"?>' . CRLF;
    $tmpstr .= '<xml mode="' . $tmode . '" author="wdja">' . CRLF;
    $tmpstr .= '  <configure>' . CRLF;
    $tmpstr .= '    <node>' . $tnode . '</node>' . CRLF;
    $tmpstr .= '    <field>' . $tfield . '</field>' . CRLF;
    $tmpstr .= '    <base>' . $tbase . '</base>' . CRLF;
    $tmpstr .= '  </configure>' . CRLF;
    $tmpstr .= '  <' . $tbase . '>' . CRLF;
    foreach($torderary as $key => $val)
    {
      $tmpstr .= '    <' . $tnode . '>' . CRLF;
      $tmpstr .= '      <' . $tfieldary[0] . '><![CDATA[' . $val . ']]></' . $tfieldary[0] . '>' . CRLF;
      $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . $_POST[$val] . ']]></' . $tfieldary[1] . '>' . CRLF;
      $tmpstr .= '    </' . $tnode . '>' . CRLF;
    }
    $tmpstr .= '  </' . $tbase . '>' . CRLF;
    $tmpstr .= '</xml>';
    if (file_put_contents($tburl, $tmpstr)){
        sleep(1);
        wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_editdisp_pay()
{
    $tbackurl = $_GET['backurl'];
    if (!mm_check_token()) wdja_cms_admin_msg(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl, 1);
    $paytype_ary = $_POST['pay_type'];
    if(empty($paytype_ary)) $paytype_ary['0'] = '1';
    $_POST['pay_type'] = implode(",", $paytype_ary);
    $res = pp_update_const_config(
    array(
    'pay_type' => $_POST['pay_type'],
    'vip_user_price' => $_POST['vip_user_price'],
    'svip_user_price' => $_POST['svip_user_price'],
    'wxpayjs_id' => $_POST['wxpayjs_id'],
    'wxpayjs_key' => $_POST['wxpayjs_key'],
    'wxpayjs_notify' => $_POST['wxpayjs_notify'],
    'alipay_appid' => $_POST['alipay_appid'],
    'alipay_public_key' => $_POST['alipay_public_key'],
    'alipay_private_key' => $_POST['alipay_private_key'],
    'alipay_notify_url' => $_POST['alipay_notify_url'],
    'alipay_return_url' => $_POST['alipay_return_url'],
    'wxpay_token' => $_POST['wxpay_token'],
    'wxpay_appid' => $_POST['wxpay_appid'],
    'wxpay_appsecret' => $_POST['wxpay_appsecret'],
    'wxpay_encodingaeskey' => $_POST['wxpay_encodingaeskey'],
    'wxpay_notify' => $_POST['wxpay_notify'],
    'wxpay_mch_id' => $_POST['wxpay_mch_id'],
    'wxpay_mch_key' => $_POST['wxpay_mch_key'],
    'wxpay_ssl_key' => $_POST['wxpay_ssl_key'],
    'wxpay_ssl_cer' => $_POST['wxpay_ssl_cer'],
    'wxpay_cache_path' => $_POST['wxpay_cache_path']
    ));
    sleep(1);
    if ($res) wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_editdisp_email()
{
  $tbackurl = $_GET['backurl'];
  if (!mm_check_token()) wdja_cms_admin_msg(ii_itake('global.lng_error.token_error', 'lng'), $tbackurl, 1);
  $tburl = pp_get_xml_root('email') . XML_SFX;
  $tnode = 'item';
  $tfield = 'disinfo,chinese';
  $tbase = 'language_list';
  $torder = 'message_mail,message_title,message_body,order_mail,order_title,order_body,';
  if (ii_right($torder, 1) == ',') $torder = ii_left($torder, (strlen($torder) - 1));
  if (file_exists($tburl) && (!ii_isnull($tnode)) && (!ii_isnull($tfield)) && (!ii_isnull($tbase)))
  {
      pp_update_const_define(
        array(
        'SMTPTYPE' => $_POST['smtptype'],
        'SMTPCHARSET' => $_POST['smtpcharset'],
        'SMTPSERVER' => $_POST['smtpserver'],
        'SMTPPORT' => $_POST['smtpport'],
        'SMTPUSERNAME' => $_POST['smtpusername'],
        'SMTPPASSWORD' => $_POST['smtppassword'],
        'SMTPFROMNAME' => $_POST['smtpfromname']
      ));
    $tmpstr = '';
    $tmode = ii_get_xrootatt($tburl, 'mode');
    $tfieldary = explode(',', $tfield);
    $torderary = explode(',', $torder);
    $tub = count($tfieldary);
    $tmpstr .= '<?xml version="1.0" encoding="' . CHARSET . '"?>' . CRLF;
    $tmpstr .= '<xml mode="' . $tmode . '" author="wdja">' . CRLF;
    $tmpstr .= '  <configure>' . CRLF;
    $tmpstr .= '    <node>' . $tnode . '</node>' . CRLF;
    $tmpstr .= '    <field>' . $tfield . '</field>' . CRLF;
    $tmpstr .= '    <base>' . $tbase . '</base>' . CRLF;
    $tmpstr .= '  </configure>' . CRLF;
    $tmpstr .= '  <' . $tbase . '>' . CRLF;
    foreach($torderary as $key => $val)
    {
      $tmpstr .= '    <' . $tnode . '>' . CRLF;
      $tmpstr .= '      <' . $tfieldary[0] . '><![CDATA[' . $val . ']]></' . $tfieldary[0] . '>' . CRLF;
      $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . $_POST[$val] . ']]></' . $tfieldary[1] . '>' . CRLF;
      $tmpstr .= '    </' . $tnode . '>' . CRLF;
    }
    $tmpstr .= '  </' . $tbase . '>' . CRLF;
    $tmpstr .= '</xml>';
    if (file_put_contents($tburl, $tmpstr)){
        sleep(1);
        wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_action()
{
  switch($_GET['action'])
  {
    case 'basic':
      wdja_cms_admin_manage_editdisp_basic();
      break;
    case 'core':
      wdja_cms_admin_manage_editdisp_core();
      break;
    case 'seo':
      wdja_cms_admin_manage_editdisp_seo();
      break;
    case 'pay':
      wdja_cms_admin_manage_editdisp_pay();
      break;
    case 'email':
      wdja_cms_admin_manage_editdisp_email();
      break;
    case 'upload':
      uu_upload_files();
      break;
  }
}

function wdja_cms_admin_manage_edit_core()
{
  $tmpstr = ii_itake('manage.core', 'tpl');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_edit_pay()
{
  global $pay_type,$vip_user_price,$svip_user_price,$wxpayjs_id,$wxpayjs_key,$wxpayjs_notify,$alipay_appid,$alipay_public_key,$alipay_private_key,$alipay_notify_url,$alipay_return_url,$wxpay_token,$wxpay_appid,$wxpay_appsecret,$wxpay_encodingaeskey,$wxpay_notify,$wxpay_mch_id,$wxpay_mch_key,$wxpay_ssl_key,$wxpay_ssl_cer,$wxpay_cache_path;
  $tmpstr = ii_itake('manage.pay', 'tpl');
  $tmpstr = str_replace('{$pay_type}', $pay_type, $tmpstr);
  $tmpstr = str_replace('{$vip_user_price}', $vip_user_price, $tmpstr);
  $tmpstr = str_replace('{$svip_user_price}', $svip_user_price, $tmpstr);
  $tmpstr = str_replace('{$wxpayjs_id}', $wxpayjs_id, $tmpstr);
  $tmpstr = str_replace('{$wxpayjs_key}', $wxpayjs_key, $tmpstr);
  $tmpstr = str_replace('{$wxpayjs_notify}', $wxpayjs_notify, $tmpstr);
  $tmpstr = str_replace('{$alipay_appid}', $alipay_appid, $tmpstr);
  $tmpstr = str_replace('{$alipay_public_key}', $alipay_public_key, $tmpstr);
  $tmpstr = str_replace('{$alipay_private_key}', $alipay_private_key, $tmpstr);
  $tmpstr = str_replace('{$alipay_notify_url}', $alipay_notify_url, $tmpstr);
  $tmpstr = str_replace('{$alipay_return_url}', $alipay_return_url, $tmpstr);
  $tmpstr = str_replace('{$wxpay_token}', $wxpay_token, $tmpstr);
  $tmpstr = str_replace('{$wxpay_appid}', $wxpay_appid, $tmpstr);
  $tmpstr = str_replace('{$wxpay_appsecret}', $wxpay_appsecret, $tmpstr);
  $tmpstr = str_replace('{$wxpay_encodingaeskey}', $wxpay_encodingaeskey, $tmpstr);
  $tmpstr = str_replace('{$wxpay_notify}', $wxpay_notify, $tmpstr);
  $tmpstr = str_replace('{$wxpay_mch_id}', $wxpay_mch_id, $tmpstr);
  $tmpstr = str_replace('{$wxpay_mch_key}', $wxpay_mch_key, $tmpstr);
  $tmpstr = str_replace('{$wxpay_ssl_key}', $wxpay_ssl_key, $tmpstr);
  $tmpstr = str_replace('{$wxpay_ssl_cer}', $wxpay_ssl_cer, $tmpstr);
  $tmpstr = str_replace('{$wxpay_cache_path}', $wxpay_cache_path, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_edit($type = 'basic')
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $trootstr = pp_get_xml_root($type) . XML_SFX;
  if (file_exists($trootstr))
  {
    $tmpstr = ii_itake('manage.'.$type , 'tpl');
    $tdoc = new DOMDocument();
    $tdoc -> load($trootstr);
    $txpath = new DOMXPath($tdoc);
    $tquery = '//xml/configure/node';
    $tnode = $txpath -> query($tquery) -> item(0) -> nodeValue;
    $tquery = '//xml/configure/field';
    $tfield = $txpath -> query($tquery) -> item(0) -> nodeValue;
    $tquery = '//xml/configure/base';
    $tbase = $txpath -> query($tquery) -> item(0) -> nodeValue;
    $tfieldary = explode(',', $tfield);
    $tlength = count($tfieldary) - 1;
    $tquery = '//xml/' . $tbase . '/' . $tnode;
    $trests = $txpath -> query($tquery);
    foreach ($trests as $trest)
    {
      $tnodelength = $trest -> childNodes -> length;
      for ($i = 0; $i <= $tlength; $i += 1)
      {
        $ti = $i * 2 + 1;
        if ($ti < $tnodelength)
        {
          $nodeValue = $trest -> childNodes -> item($ti) -> nodeValue;
        }
        if ($i < $tlength) $k = ii_htmlencode($nodeValue);
        if ($i == $tlength) {
          if (ii_isnull($GLOBALS['RS_' . $k])) $GLOBALS['RS_' . $k] = $nodeValue;
          $gk = "{\$=ii_itake('global.support/global:".$type.".".$k."','lng')}";
          $tmpstr = str_replace('{$global_'.$k.'}', ii_htmlencode($gk), $tmpstr);
          $tmpstr = str_replace('{$'.$k.'}', ii_htmlencode($nodeValue), $tmpstr);
        }
      }
    }
    $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('manage.notexists', 'lng'), -1);
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'basic':
      return wdja_cms_admin_manage_edit('basic');
      break;
    case 'core':
      return wdja_cms_admin_manage_edit_core();
      break;
    case 'seo':
      return wdja_cms_admin_manage_edit('seo');
      break;
    case 'pay':
      return wdja_cms_admin_manage_edit_pay();
      break;
    case 'email':
      return wdja_cms_admin_manage_edit('email');
      break;
    case 'upload':
      uu_upload_files_html('upload_html');
      break;
    default:
      return wdja_cms_admin_manage_edit('basic');
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>