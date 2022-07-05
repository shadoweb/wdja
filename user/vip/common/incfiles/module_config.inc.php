<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_module_order_search() {
  global $nusername,$nuserid;
  global $ngenre,$conn,$ndatabase;
  $tutype = ap_get_userinfo('utype', $nusername);//会员级别
  if (mm_search_field($ngenre,$tutype,'outype')) {
      $tsqlstr = "select " . ii_cfname('orderid') . " from $ndatabase where " . ii_cfname('outype') . "='$tutype' and " . ii_cfname('username') . "='$nusername'";
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs) {
          $torderid = $trs[ii_cfname('orderid')];
          mm_client_redirect('./?type=order&id=' . $torderid);
      }
  }
}

function wdja_cms_module_orderdisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $nusername,$nuserid;
  global $ngenre,$vip_user_price,$svip_user_price;
  $toutype = mm_get_field('user',$nuserid,'utype');
  $tnutype = ii_get_safecode($_POST['utype']);
  $tutype = ap_get_userinfo('utype', $nusername);//会员级别
  if ($tnutype == 1) $tprice = $vip_user_price;
  else $tprice = $svip_user_price;
  if ($tutype == 1 && $tnutype == 2) $tprice = $svip_user_price - $vip_user_price;//如果是升级会员,只需补差价即可.
  $tpayment = $_POST['payment'];
  $torderid = ii_format_date(ii_now(), 0) . ($tutype % 10);
  if (!ii_isnull($tnutype) && !ii_isnull($tprice))
  {
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('orderid') . ",
    " . ii_cfname('username') . ",
    " . ii_cfname('outype') . ",
    " . ii_cfname('nutype') . ",
    " . ii_cfname('price') . ",
    " . ii_cfname('payment') . ",
    " . ii_cfname('state') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . "
    ) values (
    '" . $torderid . "',
    '" . $nusername . "',
    '" . $toutype . "',
    '" . $tnutype . "',
    '" . $tprice . "',
    '" . $tpayment . "',
    '0',
    '" . ii_now() . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) mm_client_redirect('./?type=order&id=' . $torderid);
    else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');
  }
}

function wdja_cms_module_action()
{
  switch($_GET['action'])
  {
    case 'order':
      wdja_cms_module_orderdisp();
      break;
  }
}

function wdja_cms_module_order()
{
  global $conn;
  global $nusername, $nlisttopx, $npagesize;
  global $ndatabase, $nidfield, $nfpre;
  global $pay_type;
  $toffset = ii_get_num($_GET['offset']);
  $torderid = $_GET['id'];
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('orderid') . "='$torderid' order by " . ii_cfname('time') . " desc";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('module.order', 'tpl');
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      if ($tkey == 'utype') $tmpstr = str_replace('{$utype}', ii_itake('global.user:sel_group.'.$trs[ii_cfname('nutype')],'lng'), $tmpstr);
      else $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$pay_type}', $pay_type, $tmpstr);
    $tmpstr = str_replace('{$payment}', $trs[ii_cfname('payment')], $tmpstr);
    $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');
}

function wdja_cms_module_pay()
{
  global $nvalidate;
  global $nusername,$nuserid;
  global $ngenre;
  $tid = $_POST['id'];//订单ID
  $tprice = mm_get_field($ngenre,$tid,'price');//价格
  $torderid = mm_get_field($ngenre,$tid,'orderid');//订单号
  $tnutype = mm_get_field($ngenre,$tid,'nutype');
  $tstate = mm_get_field($ngenre,$tid,'state');
  $money = ap_get_userinfo('money', $nusername);//余额
  if ($tstate == 1) mm_imessage(ii_itake('module.ordered', 'lng'), '-1');//已支付订单退出支付
  if ($money >= $tprice) {
      $tmoney = ap_get_userinfo('money', $nusername);//重新获取余额
      $nmoney = $tmoney - $tprice;//余额结余
      if ($nmoney >= 0) {
        mm_update_field('user',$nuserid,'money',$nmoney);//更新余额
        mm_update_field($ngenre,$tid,'state',1);//更新订单状态
        mm_update_field('user',$nuserid,'utype',$tnutype);//更新会员级别
        $nstate = mm_get_field($ngenre,$tid,'state');
        if ($nstate == 1) mm_imessage(ii_itake('module.order_ok', 'lng'), '/user');
      }
      else mm_imessage(ii_itake('global.user:config.recharge_low', 'lng'), '-1');
  }
  else mm_imessage(ii_itake('global.user:config.recharge_low', 'lng'), '-1');
}

function wdja_cms_module_buyvip()
{
  global $nvalidate;
  global $nusername,$nuserid;
  global $pay_type;
  wdja_cms_module_order_search();//判断是否已有订单,有则跳转到订单页进行支付
  $payment = ii_get_lrstr($pay_type, ',', 'left');
  $tmpstr = ii_itake('module.buyvip', 'tpl');
  $tmpstr = str_replace('{$uid}', $nuserid, $tmpstr);
  $tmpstr = str_replace('{$username}', $nusername, $tmpstr);
  $tmpstr = str_replace('{$payment}', $payment, $tmpstr);
  $tmpstr = str_replace('{$pay_type}', $pay_type, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_upvip()
{
  global $nvalidate;
  global $nusername,$nuserid;
  global $pay_type,$vip_user_price,$svip_user_price;
  wdja_cms_module_order_search();//判断是否已有订单,有则跳转到订单页进行支付
  $payment = ii_get_lrstr($pay_type, ',', 'left');
  $umoney = $svip_user_price - $vip_user_price;
  $tmpstr = ii_itake('module.upvip', 'tpl');
  $tmpstr = str_replace('{$uid}', $nuserid, $tmpstr);
  $tmpstr = str_replace('{$username}', $nusername, $tmpstr);
  $tmpstr = str_replace('{$payment}', $payment, $tmpstr);
  $tmpstr = str_replace('{$pay_type}', $pay_type, $tmpstr);
  $tmpstr = str_replace('{$umoney}', $umoney, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_topvip()
{
  mm_imessage(ii_itake('module.topvip_tips', 'lng'), '-1');
}

function wdja_cms_module()
{
  switch(mm_ctype($_GET['type']))
  {
    case 'upbuy':
      return wdja_cms_module_upbuy();
      break;
    case 'buyvip':
      return wdja_cms_module_buyvip();
      break;
    case 'upvip':
      return wdja_cms_module_upvip();
      break;
    case 'topvip':
      return wdja_cms_module_topvip();
      break;
    case 'order':
      return wdja_cms_module_order();
      break;
    case 'pay':
      return wdja_cms_module_pay();
      break;
    default:
      return wdja_cms_module_buyvip();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>