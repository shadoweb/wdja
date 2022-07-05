<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
define('AP_SUPPORT_REVIEW_GENRE', 'ask/answer');

function ap_review_output_note($fid, $topx)
{
  global $conn;
  $tfid = ii_get_num($fid);
  $ttopx = ii_get_num($topx);
  if ($ttopx == 0) $ttopx = 5;
  $tdatabase = mm_cndatabase(ii_cvgenre(AP_SUPPORT_REVIEW_GENRE));
  $tidfield = mm_cnidfield(ii_cvgenre(AP_SUPPORT_REVIEW_GENRE));
  $tfpre = mm_cnfpre(ii_cvgenre(AP_SUPPORT_REVIEW_GENRE));
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'fid') . "=$tfid and " . ii_cfnames($tfpre, 'hidden') . "=0 order by " . ii_cfnames($tfpre, 'good') . " desc," . ii_cfnames($tfpre, 'time') . " desc limit 0,$ttopx";
  $trs = ii_conn_query($tsqlstr, $conn);
  $tmpstr = ii_itake('global.' . AP_SUPPORT_REVIEW_GENRE . ':api.output_note', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tmptstr = $tmpastr;
    foreach ($trow as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      if($tkey == 'good' && !is_numeric($tkey)){
          if($val == 1) $tmptstr = str_replace('{$best}', '<div class="lists-item-best">'.ii_itake('global.ask/answer:api.best', 'lng').'</div>', $tmptstr);
          else  $tmptstr = str_replace('{$best}', '', $tmptstr);
      } 
      else $tmptstr = str_replace('{$' . $tkey . '}', $val, $tmptstr);
    }
    $tmptstr = ii_creplace($tmptstr);
    $tmprstr .= $tmptstr;
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function ap_review_input_form($fid)
{
  $tmpstr = ii_itake('global.' . AP_SUPPORT_REVIEW_GENRE . ':api.input_form', 'tpl');
  $tmpstr = str_replace('{$fid}', urlencode($fid), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
