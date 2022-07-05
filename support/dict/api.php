<?php
require('../../common/incfiles/autoload.php');

echo api_getdatas();

function api_getdatas()
{
  global $slng;
  $narys = array();
  $tgroupsary = ii_replace_xinfo_ary('global.support/dict:sel_group.all', 'sel');
  $tgroups = $tgroupsary[0];
  $tgroupary = ii_get_xinfo($tgroups, $slng);
  if (is_array($tgroupary))
  {
    foreach ($tgroupary as $key => $val)
    {
        $narys[$key]['name'] = $val;
        $narys[$key]['infos'] = api_getdata($key);
    }
  }
  return json_encode($narys,true);
}

function api_getdata($group='')
{
  global $conn, $nlng, $slng, $variable;
  $tgenre = 'support/dict';
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
  $tsqlstr = "select $tidfield,".ii_cfnames($tfpre,'topic').",".ii_cfnames($tfpre,'fsid')." ,".ii_cfnames($tfpre,'group')." from $tdatabase where " . ii_cfnames($tfpre,'lng') . "='$slng' and " . ii_cfnames($tfpre,'hidden') . "='0'";
  $tsqlstr .= " order by " . ii_cfnames($tfpre,'order') . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_all($trs);
  $nary = array();
  foreach($trs as $ary){
      $nary[] = array('id'=>$ary[$tidfield],'pid'=>$ary[ii_cfnames($tfpre,'fsid')],'txt'=>$ary[ii_cfnames($tfpre,'topic')],'group'=>$ary[ii_cfnames($tfpre,'group')]);
  }
    ii_cache_put($tappstr, 1, $nary);
    $GLOBALS[$tappstr] = $nary;
  }
  $mgroup = ii_get_num($group,0);
  $rary = array();
  $cary = $GLOBALS[$tappstr];
  foreach($cary as $key => $val){
      if($mgroup == $val['group'] ) $rary[] = array('id'=>$val['id'],'pid'=>$val['pid'],'txt'=>$val['txt']);
  }
  return $rary;
}
?>