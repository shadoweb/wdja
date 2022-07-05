<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'username,id';
$ncontrol = 'select,delete';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function wdja_cms_admin_manage_adddisp()
{
  global $ngenre, $slng;
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nsaveimages;
  $tbackurl = $_GET['backurl'];
  $tckstr = 'username:' . ii_itake('manage.username', 'lng') . ',price:' . ii_itake('manage.price', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  $tusername = ii_get_safecode($_POST['username']);
  $tprice = ii_get_safecode($_POST['price']);
  $tpayment = ii_get_safecode($_POST['payment']);
  $torderid = ii_format_date(ii_now(), 0) . ($tutype % 10);
  
  if (!ii_isnull($torderid))
  {
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('orderid') . ",
    " . ii_cfname('username') . ",
    " . ii_cfname('payment') . ",
    " . ii_cfname('price') . ",
    " . ii_cfname('state') . ",
    " . ii_cfname('lock') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . "
    ) values (
    '" . $torderid . "',
    '" . $tusername . "',
    '0',
    '" . $tprice . "',
    '1',
    '1',
    '" . ii_now() . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) {
        $tmoney = ap_get_userinfo('money', $tusername);//当前会员的余额
        $tuserid = ap_get_userid($tusername);//当前订单会员ID
        mm_update_field('user',$tuserid,'money',$tmoney + $tprice);//充值
        $nmoney = ap_get_userinfo('money', $tusername);//当前会员的最新余额
        if ($tprice == ($nmoney - $tmoney)) wdja_cms_admin_msg(ii_itake('manage.add_succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn;
  global $ngenre,$ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id']);
  $tstate = ii_get_num($_POST['state']);
  $tprice = mm_get_field($ngenre,$tid,'price');
  $tlock = mm_get_field($ngenre,$tid,'lock');
  if ($tid != 0 && $tlock != 1)
  {
    $tusername = mm_get_field($ngenre,$tid,'username');
    $tmoney = ap_get_userinfo('money', $tusername);//当前会员的余额
    $tuserid = ap_get_userid($tusername);//当前订单会员ID
    $tsqlstr = "update $ndatabase set " . ii_cfname('state') . "= $tstate where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) {
        if ($tstate == 1 && $tlock == 0) {
            //编辑订单状态为已充值时,同步增加会员余额,再进行订单锁定lock.
            mm_update_field('user',$tuserid,'money',$tmoney + $tprice);//充值
            $nmoney = ap_get_userinfo('money', $tusername);//当前会员的最新余额
            if ($tprice == ($nmoney - $tmoney)) mm_update_field($ngenre,$tid,'lock',1);//锁定
        }
        wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
    case 'add':
      wdja_cms_admin_manage_adddisp();
      break;
    case 'edit':
      wdja_cms_admin_manage_editdisp();
      break;
    case 'delete':
      wdja_cms_admin_deletedisp($ndatabase, $nidfield);
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
  }
}

function wdja_cms_admin_manage_add()
{
  $tmpstr = ii_ireplace('manage.add', 'tpl');
  return $tmpstr;
}

function wdja_cms_admin_manage_edit()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage.edit', 'tpl');
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

function wdja_cms_admin_manage_list()
{
  global $conn;
  global $ngenre, $npagesize, $nlisttopx;
  global $ndatabase, $nidfield, $nfpre;
  $toffset = ii_get_num($_GET['offset']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where $nidfield>0";
  if ($search_field == 'username') $tsqlstr .= " and " . ii_cfname('username') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'id') $tsqlstr .= " and $nidfield=" . ii_get_num($search_keyword);
  if ($search_field == 'state') $tsqlstr .= " and " . ii_cfname('state') . "=" . $search_keyword;
  $tsqlstr .= " order by $ndatabase." . ii_cfname('time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (!(ii_isnull($search_keyword)) && $search_field == 'username') $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tusername = ii_htmlencode($trs[ii_cfname('username')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $search_keyword, $font_red);
        $tusername = str_replace($search_keyword, $font_red, $tusername);
      }
      $tpayment = ii_get_num($trs[ii_cfname('payment')]);
      if ($tpayment == 0)$tpayment = ii_itake('manage.add', 'lng');
      else $tpayment = ii_itake('global.user/shopcart:sel_payment.'.$tpayment, 'sel');
      $tmptstr = str_replace('{$username}', $tusername, $tmpastr);
      $tmptstr = str_replace('{$orderid}', ii_get_num($trs[ii_cfname('orderid')]), $tmptstr);
      $tmptstr = str_replace('{$price}', ii_get_num($trs[ii_cfname('price')]), $tmptstr);
      $tmptstr = str_replace('{$state}', ii_itake('sel_state.'.ii_get_num($trs[ii_cfname('state')]), 'sel'), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$payment}', $tpayment, $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'add':
      return wdja_cms_admin_manage_add();
      break;
    case 'edit':
      return wdja_cms_admin_manage_edit();
      break;
    case 'list':
      return wdja_cms_admin_manage_list();
      break;
    default:
      return wdja_cms_admin_manage_list();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>