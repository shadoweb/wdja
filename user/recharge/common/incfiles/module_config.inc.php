<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_module_orderdisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $nusername;
  $tpayment = ii_get_safecode($_POST['payment']);
  $tprice = ii_get_safecode($_POST['money']);
  $torderid = ii_format_date(ii_now(), 0) . ($tutype % 10);
  if (!ii_isnull($tprice))
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
    '" . $nusername . "',
    '" . $tpayment . "',
    '" . $tprice . "',
    '0',
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
      return wdja_cms_module_orderdisp();
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
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$pay_type}', $pay_type, $tmpstr);
    $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function wdja_cms_module_buy()
{
  global $nvalidate;
  global $nusername,$nuserid;
  global $pay_type;
  $payment = ii_get_lrstr($pay_type, ',', 'left');
  $tmpstr = ii_itake('module.buy', 'tpl');
  $tmpstr = str_replace('{$uid}', $nuserid, $tmpstr);
  $tmpstr = str_replace('{$username}', $nusername, $tmpstr);
  $tmpstr = str_replace('{$payment}', $payment, $tmpstr);
  $tmpstr = str_replace('{$pay_type}', $pay_type, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module()
{
  switch(mm_ctype($_GET['type']))
  {
    case 'upbuy':
      return wdja_cms_module_upbuy();
      break;
    case 'buy':
      return wdja_cms_module_buy();
      break;
    case 'order':
      return wdja_cms_module_order();
      break;
    default:
      return wdja_cms_module_buy();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>