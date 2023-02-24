<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************

function mm_get_content_image($genre,$content,$image) {
  global $global_images_route,$nskin;
  preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER); 
  $n = count($strResult[1]);
  if (strpos($image,'/noimg.gif') !== false || ii_isnull($image)) {
      if ($n > 0) {
          $img_url = $strResult[1][0]; 
       }else{
       $random = mt_rand(1, 25);
       $img_url = $global_images_route.'theme/'.$nskin.'/random/'. $random .'.jpg';
       }
    }else{
      $img_url = $image; 
      return $img_url;
    }
  return $img_url;
}

function mm_update_field($genre,$id,$field,$val)
{
  //更新任意字段
  global $conn, $variable;
  ii_conn_init();
  $bool = false;
  $tgenre = $genre;
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
  $tsqlstr = 'update '. $tdatabase.' set '.ii_cfnames($tfpre,$field).' = '.$val.' where '.$tidfield.' = ' .$id;
  $trs = ii_conn_query($tsqlstr, $conn);
  if (ii_conn_affected_rows($conn) > 0) $bool = true;
  return $bool;
}

function mm_search_field($genre,$field_val,$field,$id = '0')
{
  //查询字段值是否重复
  global $conn, $variable;
  ii_conn_init();
  $res = false;
  $tgenre = $genre;
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tmpstr = '';
  if ($id == '0') $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val.'"';
  else $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val.'" and ' . $tidfield . ' <> ' . $id;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $res = true;
  else $res = false;
  return $res;
}

function mm_get_field($genre,$id,$field)
{
  //获取模块任意字段
  global $conn, $variable;
  ii_conn_init();
  $tgenre = $genre;
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tmpstr = '';
  $result = '';
  if(!ii_isnull($tdatabase) && !ii_isnull($tidfield) && !ii_isnull($tfpre) && !ii_isnull($id)){
      $tsqlstr = 'select * from '. $tdatabase.' where '.$tidfield.' = ' .$id;
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      $result = $trs[ii_cfnames($tfpre,$field)];
  }
  return $result;
}

function mm_get_id($genre,$field_val,$field)
{
  //获取模块数据ID
  global $conn, $variable;
  ii_conn_init();
  $tgenre = $genre;
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tmpstr = '';
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val .'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  return $trs[$tidfield];
}

function mm_get_gallery($strers)
{
  if (!(ii_isnull($strers)))
  {
    $tpl = ii_itake('global.tpl_common.gallery', 'tpl');
    $tary = explode('#:#', $strers);
    $tmpstr = '';
    foreach ($tary as $key => $val)
    {
      if(!ii_isnull($val)){
        $tstr = $tpl;
        $tstr = str_replace('{$order}', $key, $tstr);
        $tstr = str_replace('{$url}', $val, $tstr);
        $tmpstr .= $tstr;
      }
    }
    return $tmpstr;
  }
}

function mm_cndatabase($genre, $strers = '')
{
  global $variable;
  if (ii_isnull($strers)) return $variable[$genre . '.ndatabase'];
  else return $variable[$genre . '.ndatabase_' . $strers];
}

function mm_cnidfield($genre, $strers = '')
{
  global $variable;
  if (ii_isnull($strers)) return $variable[$genre . '.nidfield'];
  else return $variable[$genre . '.nidfield_' . $strers];
}

function mm_cnfpre($genre, $strers = '')
{
  global $variable;
  if (ii_isnull($strers)) return $variable[$genre . '.nfpre'];
  else return $variable[$genre . '.nfpre_' . $strers];
}

function mm_cntitle($strers)
{
  global $ntitle,$ngenre;
  if (!ii_isnull($strers)) $ntitle = ii_htmlencode($strers);
  elseif (ii_isnull($ngenre)) $ntitle = ii_htmlencode($strers);//首页title
  else $ntitle = ii_htmlencode($strers) . SP_STR . $ntitle;
}

function mm_cnkeywords($strers)
{
  global $nkeywords;
  if (!ii_isnull($strers)) $nkeywords = ii_htmlencode($strers);
  else $nkeywords = $nkeywords;
}

function mm_cndescription($strers)
{
  global $ndescription;
  if (!ii_isnull($strers)) $ndescription = ii_htmlencode($strers);
  else $ndescription = $ndescription;
}

function mm_cnurl()
{
  global $nurl, $cnurl;
  if (ii_isnull($cnurl)) return $nurl;
  else return $cnurl;
}

function mm_ctype($types, $type = 0)
{
  if ($type == 0)
  {
    global $ctype;
    if (ii_isnull($ctype)) return $types;
    else return $ctype;
  }
  elseif ($type == 1)
  {
    global $cmtype;
    if (ii_isnull($cmtype)) return $types;
    else return $cmtype;
  }
}

function mm_client_alert($alert, $type)
{
  $str = trim($type); 
  $str = strip_tags($str);
  $str = htmlspecialchars($str);
  $type = addslashes($str);
  if (is_numeric($type)) $tdispose = 'history.go(' . $type . ')';
  else $tdispose = 'location.href=\'' . $type . '\'';
  $tstr = ii_ireplace('global.tpl_common.client_alert', 'tpl');
  $tstr = str_replace('{$alert}', $alert, $tstr);
  $tstr = str_replace('{$dispose}', $tdispose, $tstr);
  echo $tstr;
  exit();
}

function mm_client_redirect($url)
{
  $tstr = ii_ireplace('global.tpl_common.client_redirect', 'tpl');
  $tstr = str_replace('{$url}', $url, $tstr);
  echo $tstr;
  exit();
}

function mm_clear_show($msg, $type = 0)
{
  $thead = ii_ireplace('global.tpl_public.clear_head', 'tpl');
  if ($type == 1) $tbody = '<h1>' . SYS_NAME . '.' . $msg . '</h1>';
  else $tbody = $msg;
  $tfoot = ii_ireplace('global.tpl_public.clear_foot', 'tpl');
  $tstr = $thead . $tbody . $tfoot;
  echo $tstr;
  exit();
}

function mm_get_token() {
  $str = md5(microtime(true).'~!@#$%^&*(_)(*');
  $_SESSION['token'] = !ii_isnull($_SESSION['token']) ? $_SESSION['token']: $str;
  return $_SESSION['token'];
}

function mm_set_token() {
  $_SESSION['token'] = md5(microtime(true).'~!@#$%^&*(_)(*');
}

function mm_check_token() {
  $bool = $_REQUEST['token'] === $_SESSION['token'] ? true : false;
  mm_set_token();
  return $bool;
}

function mm_ck_valcode()
{
  $tbool = false;
  global $nvalidate;
  if ($nvalidate != 0)
  {
    if (strtolower($_POST['valcode']) == strtolower($_SESSION['valcode'])) $tbool = true;
    if (strtolower($_GET['valcode']) == strtolower($_SESSION['valcode'])) $tbool = true;
  }
  else $tbool = true;
  return $tbool;
}

function mm_check_valcode($strers)
{
  if (!mm_ck_valcode()) mm_client_alert(ii_itake('global.lng_error.valcode', 'lng'), $strers);
}

function mm_cvalhtml($template, $vals, $recurrence)
{
  $tmpstr = ii_ctemplate($template, $recurrence);
  if (ii_get_num($vals) == 0) $tmpstr = '';
  $tmpstr = str_replace(WDJA_CINFO, $tmpstr, $template);
  return $tmpstr;
}

function mm_cutepage_content($strers)
{
  if (ii_isnull($strers)) return;
  $tcp_page = ii_get_num($_GET['page']);
  if ($tcp_page < 1) $tcp_page = 1;
  $tary = explode('<!-- pagebreak -->', $strers);
  $tarycount = count($tary) - 1;
  $tcp_page -= 1;
  if ($tcp_page < 0) $tcp_page = 0;
  if ($tcp_page > $tarycount) $tcp_page = $tarycount;
  return $tary[$tcp_page];
}

function mm_cutepage_content_page($strers)
{
  $tary = explode('<!-- pagebreak -->', $strers);
  return count($tary);
}

function mm_cutepage_content_page_sel($strers, $id)
{
  global $nurltype;
  $tpagenum = mm_cutepage_content_page($strers);
  if ($tpagenum > 1)
  {
    $tpagelng = ii_itake('global.lng_cutepage.npage', 'lng');
    $ttpl_a_href_self = ii_itake('global.tpl_config.a_href_self', 'tpl');
    if ($tpagenum < 1) $tpagenum = 1;
    $tmpstr = '';
    for ($i = 1; $i <= $tpagenum; $i ++)
    {
      $tmpstr .= $ttpl_a_href_self;
      $tmpstr = str_replace('{$explain}', str_replace('[]', $i, $tpagelng), $tmpstr);
      $tmpstr = str_replace('{$value}', ii_iurl('cutepage', $id, $nurltype, 'cutekey='.$i), $tmpstr);
      if ($i != $tpagenum) $tmpstr .= ' ';
    }
    return $tmpstr;
  }
}

function mm_dbase_delete($table, $id, $idary, $osql = '')
{
  if (!(ii_isnull($table) || ii_isnull($id) || ii_isnull($idary)))
  {
    if (ii_cidary($idary))
    {
      global $conn;
      $tsqlstr = "delete from $table where $id in ($idary)";
      $tsqlstr .= $osql;
      return ii_conn_query($tsqlstr, $conn);
    }
  }
}

function mm_dbase_switch($table, $field, $id, $idary, $osql = '')
{
  if (!(ii_isnull($table) || ii_isnull($field) || ii_isnull($id) || ii_isnull($idary)))
  {
    if (ii_cidary($idary))
    {
      global $conn;
      $tsqlstr = "update $table set $field=abs($field-1) where $id in ($idary)";
      $tsqlstr .= $osql;
      return ii_conn_query($tsqlstr, $conn);
    }
  }
}

function mm_dbase_update($table, $field, $fieldValue, $id, $idary, $type = 0, $osql = '')
{
  if (!(ii_isnull($table) || ii_isnull($field) || ii_isnull($id) || ii_isnull($idary)))
  {
    if (ii_cidary($idary))
    {
      global $conn;
      if ($type == 0) $tsqlstr = "update $table set $field=$fieldValue where $id in ($idary)";
      else $tsqlstr = "update $table set $field='$fieldValue' where $id in ($idary)";
      $tsqlstr .= $osql;
      return ii_conn_query($tsqlstr, $conn);
    }
  }
}

function mm_echo_error()
{
  global $Err;
  $terrstr = '';
  if (is_array($Err) && count($Err) > 0)
  {
    foreach ($Err as $key => $val)
    {
      $terrstr .= $val . '\n';
    }
    $tmpstr = ii_itake('global.tpl_common.echo_error', 'tpl');
    $tmpstr = str_replace('{$message}', $terrstr, $tmpstr);
    return $tmpstr;
  }
}

function mm_exec_delete($table, $query)
{
  if (!ii_isnull($table))
  {
    global $conn;
    $tsqlstr = "delete from $table";
    if (!ii_isnull($query)) $tsqlstr .= $query;
    return ii_conn_query($tsqlstr, $conn);
  }
}

function mm_encode_content($content)
{
  return ii_encode_text($content);
}

function mm_get_postarystr($value)
{
  $tvalue = $value;
  if (is_array($tvalue)) $tvalue = implode(',', $tvalue);
  else $tvalue = ii_cstr($tvalue);
  if (ii_isnull($tvalue)) $tvalue = '';
  return $tvalue;
}

function mm_get_mysortary($genre, $lng, $fsid)
{
  global $conn;
  global $sort_database, $sort_idfield, $sort_fpre;
  $tarys = Array();
  $tgenre = ii_get_safecode($genre);
  $tlng = ii_get_safecode($lng);
  $tfsid = ii_get_num($fsid);
  $tsqlstr = "select * from $sort_database where " . ii_cfnames($sort_fpre, 'fsid') . "=$tfsid and " . ii_cfnames($sort_fpre, 'genre') . "='$tgenre' and " . ii_cfnames($sort_fpre, 'lng') . "='$tlng' and " . ii_cfnames($sort_fpre, 'hidden') . "=0 order by " . ii_cfnames($sort_fpre, 'order') . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tary[$trow[$sort_idfield]]['id'] = $trow[$sort_idfield];
    $tary[$trow[$sort_idfield]]['sort'] = $trow[ii_cfnames($sort_fpre, 'sort')];
    $tary[$trow[$sort_idfield]]['titles'] = $trow[ii_cfnames($sort_fpre, 'titles')];
    $tary[$trow[$sort_idfield]]['keywords'] = $trow[ii_cfnames($sort_fpre, 'keywords')];
    $tary[$trow[$sort_idfield]]['description'] = $trow[ii_cfnames($sort_fpre, 'description')];
    $tary[$trow[$sort_idfield]]['fid'] = $trow[ii_cfnames($sort_fpre, 'fid')];
    $tary[$trow[$sort_idfield]]['fsid'] = $trow[ii_cfnames($sort_fpre, 'fsid')];
    $tary[$trow[$sort_idfield]]['image'] = $trow[ii_cfnames($sort_fpre, 'image')];
    $tary[$trow[$sort_idfield]]['gourl'] = $trow[ii_cfnames($sort_fpre, 'gourl')];
    $tary[$trow[$sort_idfield]]['order'] = $trow[ii_cfnames($sort_fpre, 'order')];
    $tarys += $tary;
    $tarys += mm_get_mysortary($tgenre, $tlng, $trow[$sort_idfield]);
  }
  return $tarys;
}

function mm_get_sortary($genre, $lng)
{
  $tappstr = 'sys_sort_' . $genre . '_' . $lng;
  $tappstr = str_replace('/', '_', $tappstr);
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
  }
  else
  {
    $tary = mm_get_mysortary($genre, $lng, 0);
    if(count($tary) > 0) ii_cache_put($tappstr, 1, $tary);//无分类则不生成缓存
    $GLOBALS[$tappstr] = $tary;
  }
  return $GLOBALS[$tappstr];
}

function mm_get_sortids($genre, $lng)
{
  $tary = mm_get_sortary($genre, $lng);
  $tmpstr = '';
  foreach ($tary as $key => $val)
  {
    $tmpstr .= $key . ',';
  }
  if (ii_right($tmpstr, 1) == ',') $tmpstr = ii_left($tmpstr, strlen($tmpstr) - 1);
  return $tmpstr;
}

function mm_get_sortfid($fid, $id)
{
  if (ii_isnull($fid) || $fid == '0')
  {
    return $id;
  }
  else
  {
    return $fid . ',' . $id;
  }
}

function mm_get_sortfid_count($fid, $genre, $lng)
{
  global $conn;
  global $sort_database, $sort_idfield, $sort_fpre;
  $tsqlstr = "select count($sort_idfield) from $sort_database where " . ii_cfnames($sort_fpre, 'fid') . "='$fid' and " . ii_cfnames($sort_fpre, 'genre') . "='$genre' and " . ii_cfnames($sort_fpre, 'lng') . "='$lng'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  return $trs[0];
}

function mm_get_genreid_count($cid, $genre, $lng)
{
  global $conn,$variable;
  $ndatabase = $variable[ii_cvgenre($genre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($genre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($genre) . '.nfpre'];
  $tsqlstr = "select count($nidfield) from $ndatabase where " . ii_cfnames($nfpre, 'class') . "='$cid' and " . ii_cfnames($nfpre, 'lng') . "='$lng'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  return $trs[0];
}

function mm_get_sortfid_incount($fid)
{
  if ($fid == '0')
  {
    return -1;
  }
  else
  {
    return substr_count($fid, ',');
  }
}

function mm_get_sort_cls($id)
{
  global $conn;
  global $sort_database, $sort_idfield, $sort_fpre;
  $tid = ii_get_num($id);
  if (!($tid == 0))
  {
    $tsqlstr = "select * from $sort_database where $sort_idfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = '';
      $tfid = $trs[ii_cfnames($sort_fpre, 'fid')];
      $tfidary = explode(',', $tfid);
      foreach($tfidary as $key => $val)
      {
        $tmpstr .= '|' . $val . '|,';
      }
      $tmpstr .= '|' . $trs[$sort_idfield] . '|';
      return $tmpstr;
    }
  }
}

function mm_get_sorttext($genre, $lng, $id)
{
  $tary = mm_get_sortary($genre, $lng);
  if (is_array($tary))
  {
    foreach ($tary as $key => $val)
    {
      if ($key == $id) $res = $val['sort'];
    }
  }
  $res = ii_isnull($res)?ii_itake('global.'.$genre.':module.channel_title','lng'):$res;
  return $res;
}

function mm_get_sortkeywords($genre, $lng, $id)
{
  $tary = mm_get_sortary($genre, $lng);
  if (is_array($tary))
  {
    foreach ($tary as $key => $val)
    {
      if ($key == $id) return $val['keywords'];
    }
  }
}

function mm_get_sortdescription($genre, $lng, $id)
{
  $tary = mm_get_sortary($genre, $lng);
  if (is_array($tary))
  {
    foreach ($tary as $key => $val)
    {
      if ($key == $id) return $val['description'];
    }
  }
}

//富图片集专用函数,编辑输出时使用
function mm_get_img_list($strers)
{
  if (!(ii_isnull($strers)))
  {
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $tary = explode('|', $strers);
    $tmpstr = '';
    foreach ($tary as $key => $val)
    {
      $sary = explode('#:#', $val);
      $tstr = $option_unselected;
      $tstr = str_replace('{$explain}', $sary[0], $tstr);
      $tstr = str_replace('{$value}', $sary[0].'#:#'.$sary[1].'#:#'.$sary[2], $tstr);
      $tmpstr .= $tstr;
    }
    return $tmpstr;
  }
}

function mm_get_images_list($strers)
{
  if (!(ii_isnull($strers)))
  {
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $tary = explode('|', $strers);
    $tmpstr = '';
    foreach ($tary as $key => $val)
    {
      $tstr = $option_unselected;
      $tstr = str_replace('{$explain}', $val, $tstr);
      $tstr = str_replace('{$value}', $val, $tstr);
      $tmpstr .= $tstr;
    }
    return $tmpstr;
  }
}

function mm_imessage($message, $backurl = '0')
{
  global $default_head, $default_foot;
  $tmyhead = mm_web_head($default_head);
  $tmyfoot = mm_web_foot($default_foot);
  if ($backurl == '0') $tmybody = ii_ireplace('global.tpl_common.web_messages', 'tpl');
  else $tmybody = ii_ireplace('global.tpl_common.web_message', 'tpl');
  $tmybody = str_replace('{$message}', $message, $tmybody);
  $tmybody = str_replace('{$backurl}', $backurl, $tmybody);
  $tmyhtml = $tmyhead . $tmybody . $tmyfoot;
  echo $tmyhtml;
  exit();
}

function mm_sendemail($address, $subject, $message)
{
  global $variable;
  $ttype = SMTPTYPE;
  $tsmtpcharset = SMTPCHARSET;
  $tsmtpserver = SMTPSERVER;
  $tsmtpport = SMTPPORT;
  $tsmtpusername = SMTPUSERNAME;
  $tsmtppassword = SMTPPASSWORD;
  $tsmtpfromname = SMTPFROMNAME;
  $taddress = iconv(CHARSET, $tsmtpcharset, $address);
  $tsubject = iconv(CHARSET, $tsmtpcharset, $subject);
  $tmessage = iconv(CHARSET, $tsmtpcharset, $message);
  if ($ttype == -1) return;
  if ($ttype == 0)
  {
    return mail($taddress, $tsubject, $tmessage, "From: $tsmtpfromname");
  }
  elseif ($ttype == 1)
  {
    $tmail = new cc_socketmail;
    $tmail -> server = $tsmtpserver;
    $tmail -> port = $tsmtpport;
    $tmail -> charset = $tsmtpcharset;
    $tmail -> username = $tsmtpusername;
    $tmail -> password = $tsmtppassword;
    $tmail -> from = $tsmtpfromname;
    $tmail -> to = $taddress;
    $tmail -> subject = $tsubject;
    $tmail -> message = $tmessage;
    return $tmail -> send_mail();
  }
  else
  {
    ini_set('SMTP', $tsmtpserver);
    ini_set('smtp_port', $tsmtpport);
    ini_set('sendmail_from', $tsmtpfromname);
    return mail($taddress, $tsubject, $tmessage, "From: $tsmtpfromname");
  }
}

function mm_sel_sort($genre, $lng, $sid)
{
  $tary = mm_get_sortary($genre, $lng);
  if (is_array($tary))
  {
    $tsid = ii_get_num($sid);
    $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
    $option_pre = '<option value="0" selected>'.ii_itake('global.lng_config.unselect', 'lng').'</option>';
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $tmpstr = '';
    $treturnstr = '';
    foreach ($tary as $key => $val)
    {
      if ($key == $tsid) $tmpstr = $option_selected;
      else $tmpstr = $option_unselected;
      $tmpstr = str_replace('{$explain}', str_repeat($trestr, mm_get_sortfid_incount($val['fid'], ',') + 1) . $val['sort'], $tmpstr);
      $tmpstr = str_replace('{$value}', $val['id'], $tmpstr);
      $treturnstr .= $tmpstr;
    }
    return $option_pre.$treturnstr;
  }else{
    return $option_pre;
  }
}

function mm_sel_yesno($name, $value)
{
  $option_radio = ii_itake('global.tpl_config.option_radio', 'tpl');
  $option_unradio = ii_itake('global.tpl_config.option_unradio', 'tpl');
  $tlngyes = ii_itake('global.lng_config.yes', 'lng');
  $tlngno = ii_itake('global.lng_config.no', 'lng');
  $tmpstr = '';
  $treturnstr = '';
  if ($value == 1) $tmpstr = $option_radio;
  else $tmpstr = $option_unradio;
  $tmpstr = str_replace('{$explain}', $name, $tmpstr);
  $tmpstr = str_replace('{$value}', 1, $tmpstr);
  $tmpstr = $tmpstr . $tlngyes . ' ';
  $treturnstr .= $tmpstr;
  if ($value == 0) $tmpstr = $option_radio;
  else $tmpstr = $option_unradio;
  $tmpstr = str_replace('{$explain}', $name, $tmpstr);
  $tmpstr = str_replace('{$value}', 0, $tmpstr);
  $tmpstr = $tmpstr . $tlngno . ' ';
  $treturnstr .= $tmpstr;
  return $treturnstr;
}

function mm_sel_control()
{
  global $ncontrol;
  if (!ii_isnull($ncontrol))
  {
    return  ii_show_xmlinfo_select('global.sel_control.all|' . $ncontrol, '', 'select');
  }
}

function mm_html_content2($name, $value)
{
  $tmpstr = ii_itake('global.tpl_common.content2_htmledit', 'tpl');
  $tmpstr = str_replace('{$name}', $name, $tmpstr);
  $tmpstr = str_replace('{$value}', $value, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function mm_valcode()
{
  $valcode = ii_ireplace('global.tpl_common.valcode', 'tpl');
  return $valcode;
}

function mm_get_sorttitles($genre, $lng, $id)
{
  $tary = mm_get_sortary($genre, $lng);
  if (is_array($tary))
  {
    foreach ($tary as $key => $val)
    {
      if ($key == $id){
        if(!ii_isnull($val['titles'])) return $val['titles'];
        else return $val['sort'];
      } 
    }
  }
}

function mm_web_title($title)
{
  global $ngenre,$ntitle;
  if(!ii_isnull($ntitle)) $ttitle = $ntitle;
  else $ttitle = $title;
  $tweb_topic = ii_itake('global.support/global:seo.web_topic', 'lng');//首页标题
  $tweb_title = ii_itake('global.support/global:basic.web_name', 'lng');//网站名称
  if (ii_isnull($tweb_title)) $tweb_title = ii_itake('global.module.web_title', 'lng');
  if (ii_isnull($tweb_topic)) $tweb_topic = $tweb_title;
  if (ii_isnull($ttitle)) $tweb_title = $tweb_topic;
  if (!(ii_isnull($ttitle))) {
    if (ii_isnull($_GET['id'])) $tweb_title = $ttitle . SP_STR . $tweb_title;
    else $tweb_title = $ttitle . SP_STR . ii_itake('global.'.$ngenre.':module.channel_title', 'lng'). SP_STR . $tweb_title;
  }
  return $tweb_title;
}

function mm_web_keywords($keywords)
{
  $tkeywords = $keywords;
  $tweb_keywords = ii_itake('global.support/global:seo.web_keywords', 'lng');
  if (ii_isnull($tweb_keywords)) $tweb_keywords = ii_itake('global.module.web_keywords', 'lng');
  if (!(ii_isnull($tkeywords))) $tweb_keywords = $tkeywords;
  return $tweb_keywords;
}

function mm_web_description($description)
{
  $tdescription = $description;
  $tweb_description = ii_itake('global.support/global:seo.web_description', 'lng');
  if (ii_isnull($tweb_description)) $tweb_description = ii_itake('global.module.web_description', 'lng');
  if (!(ii_isnull($tdescription))) $tweb_description = $tdescription;
  return $tweb_description;
}

function mm_web_base()
{
  global $nbasehref, $variable;
  if (ii_isnull($nbasehref)) $nbasehref = $variable['common.nbasehref'];
  if ($nbasehref == 1) return ii_ireplace('global.tpl_public.web_base', 'tpl');
}

function mm_web_head($key)
{
  global $ngenre;
  $tmpstr = ii_itake('global.tpl_public.' . $key, 'tpl');
  $tmpstr = str_replace('{$ngenre}', $ngenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function mm_web_foot($key)
{
  global $starttime;
  $mirror_switch = ii_itake('global.support/global:basic.mirror_switch','lng');
  $runtime_switch = ii_itake('global.support/global:basic.runtime_switch','lng');
  $tfoot = ii_ireplace('global.tpl_public.' . $key, 'tpl');
  $tfoot = ii_creplace($tfoot);
  $endtime = microtime(1);
  $protime = number_format((($endtime - $starttime) * 1000), 3, '.', '');
  if($mirror_switch == 1 && !ii_isAdmin()) $tfoot = mm_deny_mirrored_websites() . $tfoot;
  if($runtime_switch == 1) $tfoot .= CRLF . '<!--WDJA, Processed in ' . $protime . ' ms-->';;
  return $tfoot;
}

function mm_deny_mirrored_websites() {
  global $nurls;
  $mirror_url = ii_itake('global.support/global:basic.mirror_url','lng');
  if(!ii_isnull($mirror_url)) $nurls = $mirror_url;
  $currentDomain = ii_get_lrstr($nurls, '.', 'leftr').'." + "'.ii_get_lrstr($nurls, '.', 'right');
  $res = '<img style="display:none" src=" " onerror=\'this.onerror=null;var str1="'.$currentDomain.'";str2="docu"+"ment.loca"+"tion.host";str3=eval(str2);if( str1!=str3 ){ do_action = "loca" + "tion." + "href = loca" + "tion.href" + ".rep" + "lace(docu" +"ment"+".loca"+"tion.ho"+"st," + "\"' . $currentDomain .'\"" + ")";eval(do_action) }\' />';
  return $res;
}

function vv_itransfer($type, $tpl, $vars)
{
  global $conn, $variable, $nurltype, $ngenre, $nlng;
  $tgenre = ii_get_strvalue($vars, 'genre');
  $ttopx = ii_get_strvalue($vars, 'topx');
  if (strpos($ttopx,',') === false) $ttopx = ii_get_num($ttopx);
  $tcls = ii_get_num(ii_get_strvalue($vars, 'cls'));
  $tclass = ii_get_num(ii_get_strvalue($vars, 'class'));
  $thtml = ii_get_num(ii_get_strvalue($vars, 'html'));
  $tbid = ii_get_num(ii_get_strvalue($vars, 'bid'));
  $tkeywords = ii_get_strvalue($vars, 'keywords');
  $tosql = ii_get_strvalue($vars, 'osql');
  $ttransVars = ii_get_strvalue($vars, 'transVars');
  //*****************************************************
  $tbsql = ii_get_strvalue($vars, 'bsql');
  $tdatabase = ii_get_strvalue($vars, 'database');
  $tidfield = ii_get_strvalue($vars, 'idfield');
  $tfpre = ii_get_strvalue($vars, 'fpre');
  //*****************************************************
  $tbaseurl = ii_get_strvalue($vars, 'baseurl');
  if ($ttopx >= 0 || strpos($ttopx,',') !== false)
  {
    if (ii_isnull($tbaseurl))
    {
      if (!ii_isnull($tgenre) && !($tgenre == $ngenre)) $tbaseurl = ii_get_actual_route($tgenre);
    }
    if (ii_isnull($tgenre)) $tgenre = $ngenre;
    if (!ii_isnull($tgenre))
    {
      if (ii_isnull($tdatabase)) $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
      if (ii_isnull($tidfield)) $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
      if (ii_isnull($tfpre)) $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
      if (ii_isnull($tbsql))
      {
        switch($type)
        {
          case 'rand':
            $rsqlstr = "select count(*) as recount from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $rrs = ii_conn_query($rsqlstr, $conn);
            $stat = ii_conn_fetch_array($rrs);
            $total = $stat['recount'];
            $totals = $total-$ttopx >= 0 ? $total-$ttopx : 0;
            $offset = mt_rand(0, $totles);
            $ttopx = $offset.','.$ttopx;
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by rand() desc";
            break;
          case 'all':
            $tsqlstr = "select * from $tdatabase where 1=1";
            $tsqlorder = " order by $tidfield desc";
            break;
          case 'id':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by $tidfield desc";
            break;
          case '@id':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by $tidfield asc";
            break;
          case 'order':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by " . ii_cfnames($tfpre, 'order') . " asc";
            break;
          case 'search':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and (" . ii_cfnames($tfpre, 'topic') . " like '%" .$tkeywords. "%' or ". ii_cfnames($tfpre, 'content') . " like '%" .$tkeywords."%')";
            $tsqlorder = " order by $tidfield desc";
            break;
          case 'top':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by $tidfield desc";
            break;
          case 'hot':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by " . ii_cfnames($tfpre, 'count') . " desc";
            break;
          case 'new':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by " . ii_cfnames($tfpre, 'time') . " desc";
            break;
          case 'old':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by " . ii_cfnames($tfpre, 'time') . " asc";
            break;
          case 'good':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and " . ii_cfnames($tfpre, 'good') . "=1";
            $tsqlorder = " order by $tidfield desc";
            break;
          case 'up':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and $tidfield>$tbid";
            $tsqlorder = " order by $tidfield asc";
            $tips = ii_itake('global.lng_config.updata','lng') . '：';
            break;
          case 'down':
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and $tidfield<$tbid";
            $tsqlorder = " order by $tidfield desc";
            $tips = ii_itake('global.lng_config.downdata','lng') . '：';
            break;
          default:
            $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0";
            $tsqlorder = " order by $tidfield desc";
            break;
        }
        $tsqlstr .= " and " . ii_cfnames($tfpre, 'lng') . " = '$nlng'";
        if ($tcls != 0) $tsqlstr .= " and " . ii_cfnames($tfpre, 'cls') . " like '%|" . $tcls . "|%'";
        if ($tclass != 0) $tsqlstr .= " and (" . ii_cfnames($tfpre,'class') . " = $tclass or find_in_set($tclass," . ii_cfnames($tfpre,'class_list') . "))";
        if (!ii_isnull($tosql)) {
          $tosql = str_replace('#_', $tfpre, $tosql);
          $tosql = str_replace('#id', $tidfield, $tosql);
          $tsqlstr .= $tosql;
        }
        if (strpos($ttopx,',') !== false) $tsqlstr .= $tsqlorder . " limit $ttopx";
        else $tsqlstr .= $tsqlorder . " limit 0,$ttopx";
      }
      else $tsqlstr = $tbsql;
      if(!ii_isnull($tdatabase)){
        $trs = ii_conn_query($tsqlstr, $conn);
        if (substr($tpl, 0, 7) == 'global.') $tmpstr = ii_itake($tpl, 'tpl');
        else $tmpstr = ii_itake('global.tpl_transfer.' . $tpl, 'tpl');
        if (!ii_isnull($tmpstr))
        {
          if (!ii_isnull($ttransVars))
          {
            $ttransVarsAry = explode('&', $ttransVars);
            foreach ($ttransVarsAry as $key => $val)
            {
              $ttransVarsArys = explode('=', $val);
              if (count($ttransVarsArys) == 2) $tmpstr = str_replace('{$' . $ttransVarsArys[0] . '}', $ttransVarsArys[1], $tmpstr);
            }
          }
          $i = 0;
          $tactive = '';
          $tmpastr = ii_ctemplate($tmpstr, '{@}');
          while ($trow = ii_conn_fetch_array($trs))
          {
            $tmptstr = $tmpastr;
            if ($i == 0) $tactive = 'active';
            else $tactive = '';
            foreach ($trow as $key => $val)
            {
              $tkey = ii_get_lrstr($key, '_', 'rightr');
              $tval = $val;
              $GLOBALS['RST_' . $tkey] = $tval;
              if ($thtml != 1) $tval = ii_htmlencode($tval);
              $tmptstr = str_replace('{$' . $tkey . '}', $tval, $tmptstr);
            }
            $tmptstr = api_replace_fields($tmptstr,$trow[$tidfield],$tgenre);
            $i++;
            $tmptstr = str_replace('{$id}', $trow[$tidfield], $tmptstr);
            $tmptstr = str_replace('{$i}', $i, $tmptstr);
            $tmptstr = str_replace('{$genre}', $tgenre, $tmptstr);
            $tmptstr = str_replace('{$nlng}', $nlng, $tmptstr);
            $tmptstr = str_replace('{$baseurl}', $tbaseurl, $tmptstr);
            $tmptstr = str_replace('{$urltype}', $nurltype, $tmptstr);
            $tmptstr = str_replace('{$active}', $tactive, $tmptstr);
            $tmptstr = ii_creplace($tmptstr);
            $tmprstr .= $tmptstr;
          }
          $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
          if ((empty($tmprstr) && $type == 'up')||(empty($tmprstr) && $type == 'down')) {
            $tipsnull = ii_itake('global.lng_config.nomore','lng');
          }elseif (empty($tmprstr)) {
            //$tipsnull = ii_itake('global.lng_config.nodata','lng');
          }
          else $tipsnull ='';
          $tmpstr = $tips . ii_creplace($tmpstr).$tipsnull;
          return $tmpstr;
        }
        else return 'tpl.error';
      }
    }
    else return 'genre.error';
  }
  else return 'topx.error';
}

function vv_inavigation($genre, $vars, $type='0')
{
  global $nurltype,$nlng,$ntitle,$nurlpre;
  $tclassid = ii_get_num(ii_get_strvalue($vars, 'classid'));
  $tstrers = ii_get_strvalue($vars, 'strers');
  $tstrurl = ii_get_strvalue($vars, 'strurl');
  if ($type==0) {
    $tpl_href = ii_itake('global.tpl_config.a_href_self', 'tpl');
  }else{
    $tpl_href = ii_itake('global.tpl_config.span_href_self', 'tpl');
  }
  $tmpstr = ii_itake('global.module.channel_title', 'lng');
  $toutstr = $tpl_href;
  $toutstr = str_replace('{$explain}', $tmpstr, $toutstr);
  $toutstr = str_replace('{$value}', ii_get_actual_route('./'), $toutstr);
  $tmpstr = ii_itake('global.' . $genre . ':'. $tstrers .'.channel_title', 'lng');
  if (!ii_isnull($tstrers))
  {
    if (!ii_isnull($tstrurl))
    {
      if(strpos($tstrurl,'/') !==false){
        $tarr = explode('/',$tstrurl);
        $i = 0;
        foreach($tarr as $arr){
          if($i==1) $arr = $tstrurl;
          $tmpstr = ii_itake('global.' . $arr . ':'. $tstrers .'.channel_title', 'lng');
          if(!ii_isnull($tmpstr)){
            $toutstr .= NAV_SP_STR . $tpl_href;
            $toutstr = str_replace('{$explain}', $tmpstr, $toutstr);
            $toutstr = str_replace('{$value}', ii_get_actual_route($arr), $toutstr);
          }
          $i++;
        }
      }else{
        $toutstr .= NAV_SP_STR . $tpl_href;
        $toutstr = str_replace('{$explain}', $tmpstr, $toutstr);
        $toutstr = str_replace('{$value}', ii_get_actual_route($tstrurl), $toutstr);
      }
    }
    else $toutstr .= NAV_SP_STR . $tmpstr;
  }else{
    $tmpstr = ii_itake('global.' . $genre . ':module.channel_title', 'lng');
    if(strpos($genre,'/') !==false){
      $tarr = explode('/',$genre);
      $i = 0;
      foreach($tarr as $arr){
        if($i==1) $arr = $genre;
        $tmpstr = ii_itake('global.' . $arr . ':module.channel_title', 'lng');
        if(ii_isnull($tmpstr)) $tmpstr = ii_itake('global.' . $arr . ':manage.channel_title', 'lng');
        if(!ii_isnull($tmpstr)){
          $toutstr .= NAV_SP_STR . $tpl_href;
          $toutstr = str_replace('{$explain}', $tmpstr, $toutstr);
          $toutstr = str_replace('{$value}', ii_get_actual_route($arr), $toutstr);
        }
        $i++;
      }
    }else{
      if(ii_isnull($tmpstr)) $tmpstr = ii_itake('global.' . $genre . ':manage.channel_title', 'lng');
      $toutstr .= NAV_SP_STR . $tpl_href;
      $toutstr = str_replace('{$explain}', $tmpstr, $toutstr);
      $toutstr = str_replace('{$value}', ii_get_actual_route($genre), $toutstr);
    }
  }
  $tbaseurl = ii_get_actual_route($genre);
  if (ii_right($tbaseurl, 1) != '/') $tbaseurl .= '/';
  if ($tclassid != -1)
  {
    $tary = mm_get_sortary($genre, $nlng);
    if (is_array($tary))
    {
      foreach ($tary as $key => $val)
      {
        if ($key == $tclassid) $tfid = mm_get_sortfid($val['fid'], $val['id']);
      }
      if (isset($tfid))
      {
        foreach ($tary as $key => $val)
        {
          if (ii_cinstr($tfid, $key, ','))
          {
            $toutstr .= NAV_SP_STR . $tpl_href;
            $toutstr = str_replace('{$explain}', $val['sort'], $toutstr);
            $toutstr = str_replace('{$value}', ii_curl($tbaseurl, ii_iurl('list', $val['id'], $nurltype)), $toutstr);
          }
        }
      }
    }
  }
  if($_GET['type'] == 'detail' && !ii_isnull($ntitle)){
    $tid = ii_get_num($_GET['id']);
    $tpage = ii_get_num($_GET['page']);
    $tnurl = $nurlpre . '/'.$genre.'/'.ii_iurl('detail', $tid, $nurltype);
    $tnurl = str_replace('index.php', '', $tnurl);
    $toutstr .= NAV_SP_STR . $tpl_href;
    $toutstr = str_replace('{$explain}', $ntitle, $toutstr);
    $toutstr = str_replace('{$value}', $tnurl, $toutstr);
  }
  return $toutstr;
}

function vv_isort($genre, $vars, $sortAry = '')
{
  global $nurltype, $nlng;
  $tclassid = ii_get_num(ii_get_strvalue($vars, 'classid'));
  $ttpl = ii_get_strvalue($vars, 'tpl');
  $tgenre = ii_get_strvalue($vars, 'genre');
  $ttopx = ii_get_num(ii_get_strvalue($vars, 'topx'),0);
  if (!ii_isnull($tgenre) && $tgenre != $genre)
  {
    $tbaseurl = ii_get_actual_route($tgenre);
    if (ii_right($tbaseurl, 1) != '/') $tbaseurl .= '/';
  }
  if (ii_isnull($tgenre)) $tgenre = $genre;
  if (is_array($sortAry)) $tary = $sortAry;
  else $tary = mm_get_sortary($tgenre, $nlng);
  if (is_array($tary))
  {
    if (substr($ttpl, 0, 7) == 'global.') $tmpstr = ii_itake($ttpl, 'tpl');
    else $tmpstr = ii_itake('global.tpl_transfer.' . $ttpl, 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $i = 0;
    if (!ii_isnull($tmpstr))
    {
      foreach ($tary as $key => $val)
      {
        if ($val['fsid'] == $tclassid)
        {
          if ($ttopx != 0 && $i > ($ttopx - 1)) break;
          $tgourl = $val['gourl'];
          $tmptstr = str_replace('{$id}', $key, $tmpastr);
          $tmptstr = str_replace('{$genre}', $genre, $tmptstr);
          $tmptstr = str_replace('{$sort}', $val['sort'], $tmptstr);
          $tmptstr = str_replace('{$image}', $val['image'], $tmptstr);
          $tmptstr = str_replace('{$desc}', $val['description'], $tmptstr);
          if (!ii_isnull($tgourl)) $tmptstr = str_replace('{$gourl}', $tgourl, $tmptstr);
          else $tmptstr = str_replace('{$gourl}', $tsorturl, $tmptstr);
          $tmptstr = str_replace('{$baseurl}', $tbaseurl, $tmptstr);
          $tmptstr = str_replace('{$urltype}', $nurltype, $tmptstr);
          $tmprstr .= $tmptstr;
        }
        $i++;
      }
      $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
      $tmpstr = ii_creplace($tmpstr);
      return $tmpstr;
    }
    else return 'tpl.error';
  }
}

function wdja_cms_setting()
{
  $tsite_skin = $_GET['site_skin'];
  $tsite_language = $_GET['site_language'];
  $tsite_template = $_GET['site_template'];
  if (!(ii_isnull($tsite_skin)))
  {
    header("Set-Cookie:".APP_NAME."config[skin]=".$tsite_skin.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
    $_COOKIE[APP_NAME . 'config']['skin'] = $tsite_skin;
  }
  if (!(ii_isnull($tsite_language)))
  {
    header("Set-Cookie:".APP_NAME."config[language]=".$tsite_language.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
    $_COOKIE[APP_NAME . 'config']['language'] = $tsite_language;
  }
  if (!(ii_isnull($tsite_template)))
  {
    header("Set-Cookie:".APP_NAME."config[template]=".$tsite_template.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
    $_COOKIE[APP_NAME . 'config']['template'] = $tsite_template;
  }
}

function wdja_cms_switch()
{
  $switch = ii_itake('global.support/global:basic.web_state','lng');
  if ($switch == 1 && !ii_isAdmin()) {
    $tips = ii_itake('global.support/global:basic.web_state_tips','lng');
    $tbody = ii_ireplace('global.tpl_common.web_switch', 'tpl');
    $tbody = str_replace('{$message}', $tips, $tbody);
    $thtml = ii_creplace($tbody);
    echo $thtml;
    exit;
  }
}

function key_in_str($str,$arr) {
  $bool = false;
  if (is_array($arr)) {
    foreach($arr as $key) {
      if (strstr($str,$key)) {
        $bool = true;
        return $bool;
      }
    }
  }
  return $bool;
}

function check_referer() {
  if (isset($_SERVER ['HTTP_REFERER'])) $referer_host = parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST); 
  else $referer_host = ''; 
  if ($referer_host != $_SERVER['HTTP_HOST'])
  {
    header('Location:http://www.wdja.net/');
    exit; 
  }
}

function check_https(){
  $scheme = 'http';
  if(isset($_SERVER['HTTP_X_CLIENT_SCHEME'])){
    $scheme = $_SERVER['HTTP_X_CLIENT_SCHEME'];
  }elseif(isset($_SERVER['REQUEST_SCHEME'])){
    $scheme = $_SERVER['REQUEST_SCHEME'];
  }
  return $scheme;
}

function wdja_cms_init($route)
{
  //check_referer();
  wdja_cms_switch();
  wdja_cms_setting();
  global $images_route, $global_images_route;
  global $nroute, $nlng, $nskin, $nuri, $nurs, $nurl, $nurlpre;
  $nroute = $route;
  $nlng = ii_get_active_things('lng');
  $nskin = ii_get_active_things('skin');
  $nuri = $_SERVER['SCRIPT_NAME'];
  $nurs = $_SERVER['QUERY_STRING'];
  $nport = $_SERVER['SERVER_PORT'];
  $nurl = $nuri;
  $burl = parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST);
  $bport = parse_url($_SERVER['HTTP_HOST'],PHP_URL_PORT);
  $http = check_https().'://';
  if (ii_isnull($burl)) $burl = $_SERVER['SERVER_NAME'];//$_SERVER['HTTP_HOST'];
  if ($nport != $bport && $nport != '443' && $nport != '80') $nport = $bport;
  if (!(ii_isnull($nurs))) $nurl = $nuri . '?' . $nurs;
  if (!(ii_isnull($nport)) && $nport != '443' && $nport != '80') $nurlpre = $http . $burl.':'.$nport;
  else  $nurlpre = $http . $burl;
  global $web_baseurl;
  $web_baseurl =  $nurlpre . ii_get_lrstr($GLOBALS['nuri'], '/', 'leftr'). '/';
  $subweb_switch = ii_itake('global.support/global:basic.subweb_switch','lng');
  $subweb_folder = ii_itake('global.support/global:basic.subweb_folder','lng');
  if($subweb_switch == 1) $nurlpre .= '/' . $subweb_folder;
  $images_route = ii_itake('global.tpl_config.images_route', 'tpl');
  $global_images_route = $nurlpre.'/'.$images_route;
  ii_conn_init();
  ii_get_variable_init();
  global $variable;
  global $sort_database, $sort_idfield, $sort_fpre;
  $sort_database = $variable['common.sort.ndatabase'];
  $sort_idfield = $variable['common.sort.nidfield'];
  $sort_fpre = $variable['common.sort.nfpre'];
  global $related_database, $related_idfield, $related_fpre;
  $related_database = $variable['common.related.ndatabase'];
  $related_idfield = $variable['common.related.nidfield'];
  $related_fpre = $variable['common.related.nfpre'];
  global $address_database, $address_idfield, $address_fpre;
  $address_database = $variable[USER_FOLDER.'.address.ndatabase'];
  $address_idfield = $variable[USER_FOLDER.'.address.nidfield'];
  $address_fpre = $variable[USER_FOLDER.'.address.nfpre'];
  global $nvalidate, $nurltype, $ncreatefiletype;
  $nvalidate = $variable['common.nvalidate'];
  $nurltype = $variable['common.nurltype'];
  $ncreatefiletype = $variable['common.ncreatefiletype'];
  if(!ii_isAdmin()) mm_disable_ip();
}

function wdja_cms_web_head($key)
{
  return mm_web_head($key);
}

function wdja_cms_web_foot($key)
{
  return mm_web_foot($key);
}

function wdja_cms_web_noout()
{
  $tserver_v1 = $_SERVER['SERVER_NAME'];
  $tserver_v2 = $_SERVER["HTTP_REFERER"];
  $tlen = strlen($tserver_v1);
  $tckfrom = substr($tserver_v2, 7, $tlen);
  if ($tckfrom != $tserver_v1) mm_imessage(ii_itake('global.lng_common.noout', 'lng'), -1);
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>