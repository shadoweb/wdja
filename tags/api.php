<?php
require('../common/incfiles/autoload.php');
//wdja_cms_init('');
$admc_name = ii_get_safecode($_SESSION[APP_NAME . 'admin_username']);
$admc_pword = ii_get_safecode($_SESSION[APP_NAME . 'admin_password']);
if(!(wdja_cms_cklogin($admc_name, $admc_pword))){
  header('location: ' . ii_get_actual_route(ADMIN_FOLDER));
}else{
  $q = ii_cstr($_GET['q']);
  if(strlen($q) > 0){
    global $conn,$ngenre,$ndatabase,$nidfield,$nfpre, $nlng, $variable;
    global $nurltype,$ncreatefiletype;
    $tsqlstr = 'select '.ii_cfnames($nfpre,'topic').' as topic from '. $ndatabase.' where '.ii_cfnames($nfpre,'topic').' like "%'. $q . '%" and '.ii_cfnames($nfpre,'lng').' = "'. $nlng.'" ';
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_all($trs);
    if(count($trs)>0){
      uasort($trs, function ($a, $b) {
         return strLen($a['topic']) > strLen($b['topic']);
      });
      $trs = array_slice($trs,0,10);
      echo json_encode($trs);
    }
  }
}
?>