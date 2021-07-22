<?php
function search_data_insert($tshkeyword) {
  global $conn,$nlng,$variable;
  $ngenre = 'search';
  $ndatabase = mm_cndatabase(ii_cvgenre($ngenre));
  $nidfield = mm_cnidfield(ii_cvgenre($ngenre));
  $nfpre = mm_cnfpre(ii_cvgenre($ngenre));
  if (mm_search_field($ngenre,$tshkeyword,'topic')) $count = search_data_get_field($tshkeyword) + 1;
  else $count = 1;
    $ip = ii_get_client_ip();
    $time = ii_now();
    $topic = ii_get_safecode($tshkeyword);
    $content = ii_encode_article($tshkeyword);
    $infos = json_encode($_SERVER);
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfnames($nfpre,'topic') . ",
    " . ii_cfnames($nfpre,'ip') . ",
    " . ii_cfnames($nfpre,'content') . ",
    " . ii_cfnames($nfpre,'infos') . ",
    " . ii_cfnames($nfpre,'count') . ",
    " . ii_cfnames($nfpre,'time') . ",
    " . ii_cfnames($nfpre,'update') . ",
    " . ii_cfnames($nfpre,'lng') . "
    ) values (
    '" . $topic . "',
    '" . $ip . "',
    '" . $content . "',
    '" . $infos . "',
    '" . $count . "',
    '" . ii_now() . "',
    '" . ii_now() . "',
    '$nlng'
    )";
    ii_conn_query($tsqlstr, $conn);
}

function search_data_get_field($tshkeyword) {
  global $conn,$nlng,$variable;
  $count = 0;
  $ngenre = 'search';
  $ndatabase = mm_cndatabase(ii_cvgenre($ngenre));
  $nidfield = mm_cnidfield(ii_cvgenre($ngenre));
  $nfpre = mm_cnfpre(ii_cvgenre($ngenre));
  $tsqlstr = 'select '.ii_cfnames($nfpre,"count").' from '. $ndatabase.' where '.ii_cfnames($nfpre,"topic").' = "' .$tshkeyword.'" order by '.ii_cfnames($nfpre,'time').' desc';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $count = $trs[ii_cfnames($nfpre,'count')];
  return $count;
}

?>