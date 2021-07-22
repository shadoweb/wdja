<?php
require('../../common/incfiles/autoload.php');

echo wdja_cms_module_api();

function wdja_cms_module_api()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $address = array();
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $address[$tkey]=$val;
    }
    $tmpstr = json_encode($address,true);
    return $tmpstr;
  }
}
?>