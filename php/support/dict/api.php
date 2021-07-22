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
  global $conn, $slng;
  global $ndatabase, $nidfield, $nfpre;
  $mgroup = ii_get_num($group);
  $tsqlstr = "select $nidfield,".ii_cfname('topic').",".ii_cfname('fsid')." from $ndatabase where " . ii_cfname('lng') . "='$slng' and " . ii_cfname('hidden') . "='0'";
  if(!ii_isnull($group)) $tsqlstr .= " and " . ii_cfname('group') . "='$mgroup'";
  $tsqlstr .= " order by " . ii_cfname('order') . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_all($trs);
  $nary = array();
  foreach($trs as $ary){
      $nary[] = array('id'=>$ary[$nidfield],'pid'=>$ary[ii_cfnames($nfpre,'fsid')],'txt'=>$ary[ii_cfnames($nfpre,'topic')]);
  }
  return $nary;
}
?>