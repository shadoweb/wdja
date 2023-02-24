<?php

function mm_get_detail_check($genre,$gid,$url)
{
  $tmpstr = ii_itake('global.check:module.detail_check', 'tpl');
  $tmpstr = str_replace('{$genre}', $genre, $tmpstr);
  $tmpstr = str_replace('{$id}', $gid, $tmpstr);
  $tmpstr = str_replace('{$url}', $url, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function mm_get_detail_check_history($genre,$gid)
{
  global $conn, $nlng, $variable;
  $ngenre = 'check';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
  $nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
  $toffset = ii_get_num($_GET['offset']);
  $tmpstr = ii_itake('global.check:module.detail_check_history', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre,'genre') . "='$genre' and " . ii_cfnames($nfpre,'gid') . "='$gid' and " . ii_cfnames($nfpre,'hidden') . "=0 and " . ii_cfnames($nfpre,'lng') . "='$nlng' order by " . ii_cfnames($nfpre,'time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      if (ii_isnull($trs[ii_cfnames($nfpre,'reply')])) $treplyis = 0;
      else $treplyis = 1;
      $tmptstr = mm_cvalhtml($tmpastr, $treplyis, '{@admin_reply}');
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = str_replace('{$gid}', $gid, $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}