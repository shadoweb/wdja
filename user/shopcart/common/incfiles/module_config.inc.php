<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_module_adddisp()
{
  global $ngenre;
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id'], 0);
  $tbuynum = ii_get_num($_GET['buynum'], 1);
  if ($tbuynum < 1) $tbuynum = 1;
  if ($tid != 0) {
      $tnum = ii_get_num($_COOKIE[APP_NAME . $ngenre][$tid], 0);
      if ($tnum != 0) $tbuynum = $tbuynum + $tnum;
      header("Set-Cookie:".APP_NAME.$ngenre."[".$tid."]=".$tbuynum.";path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
  }
  mm_client_redirect('./?type=list&backurl' . urlencode($tbackurl));
}

function wdja_cms_module_editdisp()
{
  global $ngenre;
  $tsid = $_POST['sel_ids'];
  $tcookiesAry = $_COOKIE[APP_NAME . $ngenre];
  if (is_array($tcookiesAry))
  {
    foreach ($tcookiesAry as $key => $val)
    {
      header("Set-Cookie:".APP_NAME.$ngenre."[".$key."]=0;path=".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
    }
  }
  if (ii_cidary($tsid))
  {
    $tary = explode(',', $tsid);
    foreach ($tary as $key => $val)
    {
      $tnum = ii_get_num($_POST['num_' . $val], 0);
      if ($tnum != 0) header("Set-Cookie:".APP_NAME.$ngenre."[".$val."]=".$tnum.";path=".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
    }
  }
  mm_client_redirect('./?type=list');
}

function wdja_cms_module_deletedisp()
{
  global $ngenre;
  $tcookiesAry = $_COOKIE[APP_NAME . $ngenre];
  if (is_array($tcookiesAry))
  {
    foreach ($tcookiesAry as $key => $val)
    {
      header("Set-Cookie:".APP_NAME.$ngenre."[".$key."]=0;path =".COOKIES_PATH.";expires=".gmdate('D, d M Y H:i:s \G\M\T', time()-1).";",false);
    }
  }
  mm_client_redirect('./?type=list');
}

function wdja_cms_module_buydisp()
{
  global $ctype, $Err;
  $ctype = 'list';
  global $nusername;
  global $conn, $nshop, $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  $tugid = ap_get_userinfo('utype', $nusername);
  $tdatabase = mm_cndatabase($nshop);
  $tidfield = mm_cnidfield($nshop);
  $tfpre = mm_cnfpre($nshop);
  $titems = '';
  $tids = $_POST['cart_ids'];
  if(ii_isnull($tids)) mm_client_redirect('./?type=list');
  $tidsAry = explode(',',$tids);
  $tprices = 0;
    foreach ($tidsAry as $val)
    {
      $tid = ii_get_lrstr($val, ':', 'left');
      $tnum = ii_get_num(ii_get_lrstr(ii_get_lrstr($val, ':', 'rightr'), ':', 'left'));
      $tprice = ii_get_num(api_get_userGroupPrice($tid,$tugid));
      $tprices += $tnum*$tprice;
      if ($tid != 0 && $tnum != 0)
      {
        $titems .= $tid . ':' . $tnum . ':' . $tprice . ',';
        $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and $tidfield=$tid";
        $trs = ii_conn_query($tsqlstr, $conn);
        $trs = ii_conn_fetch_array($trs);
        if ($trs)
        {
          if (ii_get_num($trs[ii_cfnames($tfpre, 'limitnum')], 0) < $tnum) $Err[count($Err)] = str_replace('[]', '[' . ii_htmlencode(ii_cfnames($tfpre, 'topic')) . ']', ii_itake('module.addbuyerror1', 'lng'));
          $tsqlary[$tid] = "update $tdatabase set " . ii_cfnames($tfpre, 'limitnum') . "=" . ii_cfnames($tfpre, 'limitnum') . "-$tnum where $tidfield=$tid";
        }
        else $Err[count($Err)] = ii_itake('module.addbuyerror2', 'lng');
      }
    }
  if (ii_isnull($titems)) $Err[count($Err)] = ii_itake('module.payerror', 'lng');
  if (!is_array($Err) || count($Err) == 0)
  {
    $titems = ii_get_lrstr($titems, ',', 'leftr');
    if (is_array($tsqlary))
    {
      foreach ($tsqlary as $key => $val)
      {
        if (!ii_isnull($val)) @ii_conn_query($val, $conn);
      }
    }
    $ttraffic = ii_get_num($_POST['traffic'], 0);
    //$tmerchandiseprice = ii_get_num($_POST['merchandiseprice'], 0);
    $tmerchandiseprice = $tprices;//重新计算总价
    $ttrafficprice = ii_itake('sel_traffic_fare.' . $ttraffic, 'sel');
    $tallprice = $tmerchandiseprice + $ttrafficprice;
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('fid') . ",
    " . ii_cfname('merchandiseprice') . ",
    " . ii_cfname('trafficprice') . ",
    " . ii_cfname('allprice') . ",
    " . ii_cfname('name') . ",
    " . ii_cfname('address') . ",
    " . ii_cfname('phone') . ",
    " . ii_cfname('code') . ",
    " . ii_cfname('email') . ",
    " . ii_cfname('remark') . ",
    " . ii_cfname('payment') . ",
    " . ii_cfname('traffic') . ",
    " . ii_cfname('username') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . ",
    " . ii_cfname('dtime') . "
    ) values (
    '$titems',
    $tmerchandiseprice,
    $ttrafficprice,
    $tallprice,
    '" . ii_left(ii_cstr($_POST['name']), 50) . "',
    '" . ii_left(ii_cstr($_POST['address']), 255) . "',
    '" . ii_left(ii_cstr($_POST['phone']), 50) . "',
    '" . ii_left(ii_cstr($_POST['code']), 50) . "',
    '" . ii_left(ii_cstr($_POST['email']), 50) . "',
    '" . ii_left(ii_cstr($_POST['remark']), 1000) . "',
    " . ii_get_num($_POST['payment']) . ",
    " . ii_get_num($_POST['traffic']) . ",
    '$nusername',
    '" . ii_now() . "',
    '" . ii_now() . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $tupid = ii_conn_insert_id($conn);
      $torderid = ii_format_date(ii_now(), 0) . ($tupid % 10);
      $torderuser = $nusername;
      $tsqlostr = "update $ndatabase set " . ii_cfname('orderid') . "=$torderid where $nidfield=$tupid";
      ii_conn_query($tsqlostr, $conn);
        $omail = ii_itake('global.support/global:other.order_mail','lng');
        $otitle = ii_itake('global.support/global:other.order_title','lng');
        $obody = ii_itake('global.support/global:other.order_body','lng');
        $obody = str_replace('{$time}', ii_now(), $obody);
        $obody = str_replace('{$orderid}', $torderid, $obody);
        $obody = str_replace('{$price}', $tallprice, $obody);
        $obody = str_replace('{$uname}', $nusername, $obody);
      	mm_sendemail($omail, $otitle, $obody);
        $tcookiesAry = $_COOKIE[APP_NAME . $ngenre];
        if (is_array($tcookiesAry))
        {
          foreach ($tcookiesAry as $key => $val)
          {
            header("Set-Cookie:".APP_NAME.$ngenre."[".$key."]=0;path =".COOKIES_PATH.";expires=".gmdate('D, d M Y H:i:s \G\M\T', time()-1).";",false);
          }
        }
        mm_client_redirect('./?type=succeed&orderid=' . $torderid.'&orderuser=' . $torderuser);
    }
    else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), -1);
  }
}

function wdja_cms_module_action()
{
  switch($_GET['action'])
  {
    case 'add':
      return wdja_cms_module_adddisp();
      break;
    case 'edit':
      return wdja_cms_module_editdisp();
      break;
    case 'delete':
      return wdja_cms_module_deletedisp();
      break;
    case 'buy':
      return wdja_cms_module_buydisp();
      break;
  }
}

function wdja_cms_module_order_detail()
{
  global $conn, $variable;
  global $pay_type;
  $tusername = ii_get_safecode($_COOKIE[APP_NAME . 'user']['username']);
  $tugid = ap_get_userinfo('utype', $tusername);
  $ndatabase = $variable['user.shopcart.ndatabase'];
  $nidfield = $variable['user.shopcart.nidfield'];
  $nfpre = $variable['user.shopcart.nfpre'];
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    if ($trs[ii_cfnames($nfpre, 'prepaid')] == 1) $tmpstr = ii_itake('module.order_detail_paid', 'tpl');
    else $tmpstr = ii_itake('module.order_detail', 'tpl');
    $tprolist = $trs[ii_cfnames($nfpre, 'fid')];
    $nshop = 'shop';
    $tdatabase = mm_cndatabase($nshop);
    $tidfield = mm_cnidfield($nshop);
    $tfpre = mm_cnfpre($nshop);
    $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
    $tmprstr = '';
    $tmerchandiseprice = 0;
    if (!ii_isnull($tprolist))
    {
      $tary = explode(',', $tprolist);
      if (is_array($tary))
      {
        foreach ($tary as $key => $val)
        {
          $tid = ii_get_num(ii_get_lrstr($val, ':', 'left'), 0);
          if ($tid != 0)
          {
            $tsqlstr2 = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and $tidfield=$tid";
            $trs2 = ii_conn_query($tsqlstr2, $conn);
            $trs2 = ii_conn_fetch_array($trs2);
            if ($trs2)
            {
              $tnum = ii_get_lrstr(ii_get_lrstr($val, ':', 'rightr'), ':', 'left');
              $tprice = ii_get_num($trs2[ii_cfnames($tfpre, 'price')], 0);
              $twprice = ii_get_lrstr($val, ':', 'right');
              $tmerchandiseprice = $tmerchandiseprice + ($twprice * $tnum);
              $tmptstr = $tmpastr;
              global $nurltype;
              $turl = '/'.$nshop.'/'.ii_iurl('detail',$trs2[$tidfield], $nurltype);
              $tmptstr = str_replace('{$id}', $trs2[$tidfield], $tmptstr);
              $tmptstr = str_replace('{$ugid}', $tugid, $tmptstr);
              $tmptstr = str_replace('{$url}', $turl, $tmptstr);
              $tmptstr = str_replace('{$num}', $tnum, $tmptstr);
              $tmptstr = str_replace('{$price}', $tprice, $tmptstr);
              $tmptstr = str_replace('{$wprice}', $twprice, $tmptstr);
              $tmptstr = str_replace('{$topic}', ii_htmlencode($trs2[ii_cfnames($tfpre, 'topic')]), $tmptstr);
              $tmptstr = str_replace('{$limitnum}', ii_get_num($trs2[ii_cfnames($tfpre, 'limitnum')], 0), $tmptstr);
              $tmprstr .= $tmptstr;
            }
          }
        }
      }
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = str_replace('{$tallprice}', $tmerchandiseprice, $tmpstr);
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      if ($tkey=='expressid' && ii_htmlencode($val) == 0 ) $tmpstr = str_replace('{$' . $tkey . '}', '', $tmpstr);
      else $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$pay_type}', $pay_type, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else
  {
    mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
  }
}


function wdja_cms_module_order_list()
{
  global $conn,$variable;
  $ndatabase = $variable['user.shopcart.ndatabase'];
  $nidfield = $variable['user.shopcart.nidfield'];
  $nfpre = $variable['user.shopcart.nfpre'];
  $npagesize = $variable['user.shopcart.npagesize'];
  $nlisttopx = $variable['user.shopcart.nlisttopx'];
  $toffset = ii_get_num($_GET['offset']);
  $tusername = ii_get_safecode($_COOKIE[APP_NAME . 'user']['username']);
  $tmpstr = ii_itake('module.order_list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idb}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where $nidfield>0";
  if (!ii_isnull($tusername)) $tsqlstr .= " and " . ii_cfnames($nfpre,'username') . "='" . $tusername."'";
  $tsqlstr .= " order by " . ii_cfnames($nfpre,'time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tname = ii_htmlencode($trs[ii_cfnames($nfpre,'name')]);
      $tmptstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfnames($nfpre,'username')]), $tmpastr);
      $tmptstr = str_replace('{$name}', $tname, $tmptstr);
      $tmptstr = str_replace('{$namestr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfnames($nfpre,'name')])), $tmptstr);
      $tmptstr = str_replace('{$orderid}', ii_htmlencode($trs[ii_cfnames($nfpre,'orderid')]), $tmptstr);
      $tmptstr = str_replace('{$allprice}', ii_get_num($trs[ii_cfnames($nfpre,'allprice')]), $tmptstr);
      $tmptstr = str_replace('{$paystate}', ii_itake('global.user/shopcart:sel_paystate.' . ii_get_num($trs[ii_cfnames($nfpre,'prepaid')]), 'lng'), $tmptstr);
      $tmptstr = str_replace('{$state}', ii_itake('global.user/shopcart:sel_state.' . ii_get_num($trs[ii_cfnames($nfpre,'state')]), 'lng'), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfnames($nfpre,'time')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_list()
{
  global $conn, $nshop, $ngenre;
  $tbackurl = $_GET['backurl'];
  $tcontinue = ii_get_actual_route($nshop);
  if (!ii_isnull($tbackurl)) $tcontinue = ii_htmlencode($tbackurl);
  $tdatabase = mm_cndatabase($nshop);
  $tidfield = mm_cnidfield($nshop);
  $tfpre = mm_cnfpre($nshop);
  $tmpstr = ii_itake('module.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tmerchandiseprice = 0;
  $tcookiesAry = $_COOKIE[APP_NAME . $ngenre];
  if (is_array($tcookiesAry))
  {
    foreach ($tcookiesAry as $key => $val)
    {
      $tid = ii_get_num($key, 0);
      if ($tid != 0)
      {
        $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and $tidfield=$tid";
        $trs = ii_conn_query($tsqlstr, $conn);
        $trs = ii_conn_fetch_array($trs);
        if ($trs)
        {
          $tnum = ii_get_num($val, 0);
          $tprice = ii_get_num($trs[ii_cfnames($tfpre, 'price')], 0);
          $twprice = ii_get_num($trs[ii_cfnames($tfpre, 'wprice')], 0);
          $tmerchandiseprice = $tmerchandiseprice + ($twprice * $tnum);
          $tmptstr = $tmpastr;
          global $variable,$nurltype;
          $turl = '/'.$nshop.'/'.ii_iurl('detail',$trs[$nidfield], $nurltype);
          $tmptstr = str_replace('{$id}', $trs[$tidfield], $tmptstr);
          $tmptstr = str_replace('{$url}', $turl, $tmptstr);
          $tmptstr = str_replace('{$num}', $tnum, $tmptstr);
          $tmptstr = str_replace('{$price}', $tprice, $tmptstr);
          $tmptstr = str_replace('{$wprice}', $twprice, $tmptstr);
          $tmptstr = str_replace('{$topic}', ii_htmlencode($trs[ii_cfnames($tfpre, 'topic')]), $tmptstr);
          $tmptstr = str_replace('{$limitnum}', ii_get_num($trs[ii_cfnames($tfpre, 'limitnum')], 0), $tmptstr);
          $tmprstr .= $tmptstr;
        }
      }
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$merchandiseprice}', $tmerchandiseprice, $tmpstr);
  $tmpstr = str_replace('{$continue}', $tcontinue, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_buy()
{
  global $conn, $nshop, $ngenre;
  global $pay_type;
  $tbackurl = $_GET['backurl'];
  $tcontinue = ii_get_actual_route($nshop);
  if (!ii_isnull($tbackurl)) $tcontinue = ii_htmlencode($tbackurl);
  $tdatabase = mm_cndatabase($nshop);
  $tidfield = mm_cnidfield($nshop);
  $tfpre = mm_cnfpre($nshop);
  $payid = ii_get_lrstr($pay_type, ',', 'left');
  $tmpstr = ii_itake('module.buy', 'tpl');
  $tmpstr = str_replace('{$payid}', $payid, $tmpstr);
  $tmpstr = str_replace('{$pay_type}', $pay_type, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_succeed()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $torderid = ii_cstr($_GET['orderid']);
  $torderuser = ii_cstr($_GET['orderuser']);
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('orderid') . "='$torderid'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tid = $trs[$nidfield];
    $tmpstr = ii_itake('module.succeed', 'tpl');
    $tmpstr = str_replace('{$id}', $tid, $tmpstr);
    $tmpstr = str_replace('{$orderid}', $torderid, $tmpstr);
    $tmpstr = str_replace('{$orderuser}', $torderuser, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), './?type=list');
}

function wdja_cms_module_pay()
{
  global $nvalidate;
  global $nusername,$nuserid;
  global $ngenre;
  $tbackurl = $_GET['backurl'];
  if (ii_isnull($tbackurl)) $tbackurl = ii_get_actual_route('./').'user/shopcart/?type=order_list';
  $tid = $_POST['id'];//订单ID
  $tprice = mm_get_field($ngenre,$tid,'allprice');//价格
  $torderid = mm_get_field($ngenre,$tid,'orderid');//订单号
  $tusername = mm_get_field($ngenre,$tid,'username');//下单会员名
  $tprepaid = mm_get_field($ngenre,$tid,'prepaid');//订单支付状态
  $money = ap_get_userinfo('money', $nusername);//会员余额
  if ($tprepaid == 1) mm_imessage(ii_itake('module.ordered', 'lng'), '-1');
  if ($nusername != $tusername) mm_imessage(ii_itake('module.unorder', 'lng'), '-1');
  if ($money >= $tprice) {
      $tmoney = ap_get_userinfo('money', $nusername);//重新获取余额
      $nmoney = $tmoney - $tprice;
      if ($nmoney >= 0) {
        mm_update_field('user',$nuserid,'money',$nmoney);//更新余额
        mm_update_field($ngenre,$tid,'prepaid',1);//更新订单支付状态
        $nprepaid = mm_get_field($ngenre,$tid,'prepaid');
        if ($nprepaid == 1) mm_imessage(ii_itake('module.order_ok', 'lng'), $tbackurl);
      }
      else mm_imessage(ii_itake('global.user:config.recharge_low', 'lng'), '-1');
  }
  else mm_imessage(ii_itake('global.user:config.recharge_low', 'lng'), '-1');
}

function wdja_cms_module_index()
{
  $tmpstr = ii_ireplace('module.index', 'tpl');
  if (!ii_isnull($tmpstr)) return $tmpstr;
  else return wdja_cms_module_list();
}

function wdja_cms_module()
{
  switch(mm_ctype($_GET['type']))
  {
    case 'order_list':
      return wdja_cms_module_order_list();
      break;
    case 'order_detail':
      return wdja_cms_module_order_detail();
      break;
    case 'list':
      return wdja_cms_module_list();
      break;
    case 'buy':
      return wdja_cms_module_buy();
      break;
    case 'pay':
      return wdja_cms_module_pay();
      break;
    case 'succeed':
      return wdja_cms_module_succeed();
      break;
    case 'index':
      return wdja_cms_module_index();
      break;
    default:
      return wdja_cms_module_index();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>