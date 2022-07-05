<?php
function api_collect_array()
{
  global $conn, $ngenre, $nlng, $variable, $nurltype, $ncreatefiletype;
  $tgenre = 'support/collect';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
  $tappstr = $tgenre.'_' . $nlng;
  $tappstr = str_replace('/', '_', $tappstr);
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
  }
  else
  {
    $tsqlstr = 'select '.ii_cfnames($tfpre,'url').','.ii_cfnames($tfpre,'title').','.ii_cfnames($tfpre,'image').','.ii_cfnames($tfpre,'author').','.ii_cfnames($tfpre,'content').','.ii_cfnames($tfpre,'replace').' from '. $tdatabase.' where '.ii_cfnames($tfpre,'lng').' = "'. $nlng.'" ';
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_all($trs);
    ii_cache_put($tappstr, 1, $trs);//缓存生成的热词数组
    $GLOBALS[$tappstr] = &$trs;
    unset($trs);
  }
  return $GLOBALS[$tappstr];
}