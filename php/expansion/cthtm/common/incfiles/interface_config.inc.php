<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
header("cache-control: no-cache, must-revalidate");
header("pragma: no-cache");

wdja_cms_admin_init();

$n_module = ii_get_safecode(stripslashes(ii_cstr($_GET['module'])));
$n_index = $variable[ii_cvgenre($n_module) . '.nindex'];
if (ii_isnull($n_index)) die('$invalid$');
else
{
  $n_database = $variable[ii_cvgenre($n_module) . '.ndatabase'];
  $n_idfield = $variable[ii_cvgenre($n_module) . '.nidfield'];
  $n_fpre = $variable[ii_cvgenre($n_module) . '.nfpre'];
  $n_pagesize = ii_get_num($variable[ii_cvgenre($n_module) . '.npagesize']);
  $n_listtopx = ii_get_num($variable[ii_cvgenre($n_module) . '.nlisttopx']);
  $n_urltype = $variable[ii_cvgenre($n_module) . '.nurltype'];
  $n_clstype = $variable[ii_cvgenre($n_module) . '.nclstype'];
  $n_contentcutepage = $variable[ii_cvgenre($n_module) . '.ncontentcutepage'];
  $n_createfolder = $variable[ii_cvgenre($n_module) . '.ncreatefolder'];
  $n_createfiletype = $variable[ii_cvgenre($n_module) . '.ncreatefiletype'];
}

function pp_get_myurl($strers)
{
  global $ngenre;
  global $nuri, $nurlpre;
  $turl = $nurlpre . $nuri;
  $turl = ii_get_lrstr($turl, $ngenre, 'leftr') . $strers;
  return $turl;
}

function wdja_cms_interface_get_index()
{
  $tmpstr = ii_ireplace('interface.index', 'tpl');
  echo $tmpstr;
}

function wdja_cms_interface_get_list()
{
  $tmpstr = ii_ireplace('interface.list', 'tpl');
  echo $tmpstr;
}

function wdja_cms_interface_get_detail()
{
  global $conn;
  global $n_database, $n_idfield, $n_fpre;
  $tmpstr = ii_ireplace('interface.detail', 'tpl');
  $tsqlstr = "select min($n_idfield) from $n_database where " . ii_cfnames($n_fpre, 'hidden') . "=0 and " . ii_cfnames($n_fpre, 'update') . "=0";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tid_min = $trs[0];
  $tsqlstr = "select max($n_idfield) from $n_database where " . ii_cfnames($n_fpre, 'hidden') . "=0 and " . ii_cfnames($n_fpre, 'update') . "=0";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tid_max = $trs[0];
  $tmpstr = str_replace('{$id_min}', ii_get_num($tid_min), $tmpstr);
  $tmpstr = str_replace('{$id_max}', ii_get_num($tid_max), $tmpstr);
  echo $tmpstr;
}

function wdja_cms_interface_get()
{
  switch($_GET['mtype'])
  {
    case 'index':
      wdja_cms_interface_get_index();
      break;
    case 'list':
      wdja_cms_interface_get_list();
      break;
    case 'detail':
      wdja_cms_interface_get_detail();
      break;
  }
}

function wdja_cms_interface_create_index()
{
  $tindex_filename = ii_get_safecode(stripslashes(ii_cstr($_GET['index_filename'])));
  if (!ii_isnull($tindex_filename))
  {
    global $n_module, $n_index, $n_createfiletype;
    $tfileURL = pp_get_myurl($n_module . '/' . $n_index);
    $tfileDATA = @file_get_contents($tfileURL);
    $tfileHTMLURL = ii_get_actual_route($n_module) . '/' . $tindex_filename . $n_createfiletype;
    if (file_put_contents($tfileHTMLURL, $tfileDATA))
    {
      $tinfo = ii_itake('info.create_succeed', 'lng');
      $ta_href_blank = ii_itake('global.tpl_config.a_href_blank', 'tpl');
      $tinfo_tfileHTMLURL = str_replace('{$explain}', $tfileHTMLURL, $ta_href_blank);
      $tinfo_tfileHTMLURL = str_replace('{$value}', $tfileHTMLURL, $tinfo_tfileHTMLURL);
      $tinfo .= $tinfo_tfileHTMLURL;
      echo $tinfo;
    }
  }
}

function wdja_cms_interface_create_list()
{
  global $n_module, $n_index, $n_pagesize;
  global $n_urltype, $n_createfolder, $n_createfiletype;
  $tclassid = ii_get_num($_GET['classid']);
  $tpage = ii_get_num($_GET['page'], 1);
  $toffset = ($tpage - 1) * $n_pagesize;
  $tfileURL = pp_get_myurl($n_module . '/' . $n_index . '?type=list&classid=' . $tclassid . '&offset=' . $toffset);
  $tfileDATA = ii_encode_newline(file_get_contents($tfileURL));
  $tfileHTMLURL = ii_curl(ii_get_actual_route($n_module), ii_iurl('listpage', $toffset, $n_urltype, 'folder=' . $n_createfolder . ';filetype=' . $n_createfiletype . ';listkey=' . $tclassid));
  ii_mkdir(ii_get_lrstr($tfileHTMLURL, '/', 'leftr'));
  if (file_put_contents($tfileHTMLURL, $tfileDATA))
  {
    $tinfo = ii_itake('info.create_succeed', 'lng');
    $ta_href_blank = ii_itake('global.tpl_config.a_href_blank', 'tpl');
    $tinfo_tfileHTMLURL = str_replace('{$explain}', $tfileHTMLURL, $ta_href_blank);
    $tinfo_tfileHTMLURL = str_replace('{$value}', $tfileHTMLURL, $tinfo_tfileHTMLURL);
    $tinfo .= $tinfo_tfileHTMLURL;
    echo $tinfo;
  }
}

function wdja_cms_interface_create_detail()
{
  global $conn;
  global $n_database, $n_idfield, $n_fpre;
  global $n_module, $n_index, $n_urltype, $n_contentcutepage, $n_createfolder, $n_createfiletype;
  $tid = ii_get_num($_GET['id']);
  $tsort = ii_get_num($_GET['sort']);
  $tsort_child = ii_get_num($_GET['sort_child']);
  $tisupdate = ii_get_num($_GET['isupdate']);
  $tpage = ii_get_num($_GET['page']);
  $tsqlwhere = " where " . ii_cfnames($n_fpre, 'hidden') . "=0";
  if (!($tsort == -1 || $tsort == 0))
  {
    if ($tsort_child == 1) $tsqlwhere .= " and " . ii_cfnames($n_fpre, 'cls') . " like '%|" . $tsort . "|%'";
    else $tsqlwhere .= " and " . ii_cfnames($n_fpre, 'class') . "=" . $tsort;
  }
  if ($tisupdate == 1) $tsqlwhere .= " and " . ii_cfnames($n_fpre, 'update') . "=0";
  $tcn_create = 1;
  $tsqlstr = "select * from " . $n_database . $tsqlwhere . " and $n_idfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    if ($n_contentcutepage == 1)
    {
      $tcn_crpagenum = mm_cutepage_content_page($trs[ii_cfnames($n_fpre, 'content')]);
      if ($tpage == 0) $tcn_crpage = 2;
      else $tcn_crpage = $tpage + 1;
      if ($tcn_crpage > $tcn_crpagenum) $twlupdate = 1;
    }
    else
    {
      $tcn_crpage = 0;
      $twlupdate = 1;
    }
    if ($twlupdate)
    {
      $tsqlstr = "update $n_database set " . ii_cfnames($n_fpre, 'update') . "=1 where $n_idfield=$tid";
      @ii_conn_query($tsqlstr, $conn);
    }
    $tcn_crtime = ii_get_date($trs[ii_cfnames($n_fpre, 'time')]);
    if ($tcn_create == 1)
    {
      $tfileURL = pp_get_myurl($n_module . '/' . $n_index . '?type=detail&id=' . $tid . '&page=' . $tpage);
      $tfileDATA = ii_encode_newline(file_get_contents($tfileURL));
      $tfileHTMLURL = ii_curl(ii_get_actual_route($n_module), ii_iurl('cutepage', $tpage, $n_urltype, 'folder=' . $n_createfolder . ';filetype=' . $n_createfiletype . ';cutekey=' . $tid . ';time=' . $tcn_crtime));
      ii_mkdir(ii_get_lrstr($tfileHTMLURL, '/', 'leftr'));
      if (file_put_contents($tfileHTMLURL, $tfileDATA))
      {
        $tinfo = ii_itake('info.create_succeed', 'lng');
        $ta_href_blank = ii_itake('global.tpl_config.a_href_blank', 'tpl');
        $tinfo_tfileHTMLURL = str_replace('{$explain}', $tfileHTMLURL, $ta_href_blank);
        $tinfo_tfileHTMLURL = str_replace('{$value}', $tfileHTMLURL, $tinfo_tfileHTMLURL);
        $tinfo .= $tinfo_tfileHTMLURL;
        if ($tcn_crpage == 0 || $tcn_crpage > $tcn_crpagenum)
        {
          $tsqlstr = "select * from " . $n_database . $tsqlwhere . " and $n_idfield>$tid order by $n_idfield asc limit 0,1";
          $trs = ii_conn_query($tsqlstr, $conn);
          $trs = ii_conn_fetch_array($trs);
          if ($trs) $tnextnum = $trs[$n_idfield];
          else $tnextnum = 0;
          $tinfo .= '|' . $tnextnum . '|0';
        }
        else $tinfo .= '|' . $tid . '|' . $tcn_crpage;
        echo $tinfo;
      }
    }
  }
}

function wdja_cms_interface_create()
{
  switch($_GET['mtype'])
  {
    case 'index':
      wdja_cms_interface_create_index();
      break;
    case 'list':
      wdja_cms_interface_create_list();
      break;
    case 'detail':
      wdja_cms_interface_create_detail();
      break;
  }
}

function wdja_cms_interface_loadsort()
{
  global $n_module, $nlng;
  $tsort = ii_get_num($_GET['sort']);
  $tsort_child = ii_get_num($_GET['sort_child']);
  $tsortarys = mm_get_sortary($n_module, $nlng);
  if (is_array($tsortarys))
  {
    foreach ($tsortarys as $key => $val)
    {
      if ($tsort == -1 || ii_get_num($val['id']) == $tsort || ($tsort_child == 1 && ii_cinstr($val['fid'], $tsort, ','))) $tmpstr .= $val['id'] . ',';
    }
    if (strlen($tmpstr) > 0) $tmpstr = substr($tmpstr, 0, strlen($tmpstr) - 1);
    echo $tmpstr;
  }
}

function wdja_cms_interface_loadsortlists()
{
  global $conn;
  global $n_database, $n_idfield, $n_fpre;
  global $n_clstype, $n_listtopx, $n_pagesize;
  $tclassid = ii_get_num($_GET['classid']);
  if (ii_get_num($n_clstype, 0) == 0) $tsqlstr = "select count($n_idfield) from $n_database where " . ii_cfnames($n_fpre, 'class') . "=$tclassid";
  else $tsqlstr = "select count($n_idfield) from $n_database where " . ii_cfnames($n_fpre, 'cls') . " like '%|" . $tclassid . "|%'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tcount = $trs[0];
  if ($tcount > $n_listtopx) $tcount = $n_listtopx;
  if ($tcount != 0) $tcount = ceil($tcount / $n_pagesize);
  echo $tcount;
}

function wdja_cms_interface_loadidminmax()
{
  global $conn;
  global $n_database, $n_idfield, $n_fpre;
  $tsort = ii_get_num($_GET['sort']);
  $tsort_child = ii_get_num($_GET['sort_child']);
  $tisupdate = ii_get_num($_GET['isupdate']);
  $tsqlwhere = " where " . ii_cfnames($n_fpre, 'hidden') . "=0";
  if (!($tsort == -1 || $tsort == 0))
  {
    if ($tsort_child == 1) $tsqlwhere .= " and " . ii_cfnames($n_fpre, 'cls') . " like '%|" . $tsort . "|%'";
    else $tsqlwhere .= " and " . ii_cfnames($n_fpre, 'class') . "=" . $tsort;
  }
  if ($tisupdate == 1) $tsqlwhere .= " and " . ii_cfnames($n_fpre, 'update') . "=0";
  $tsqlstr = "select min($n_idfield) from " . $n_database . $tsqlwhere;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tid_min = ii_get_num($trs[0], 0);
  $tsqlstr = "select max($n_idfield) from " . $n_database . $tsqlwhere;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tid_max = ii_get_num($trs[0], 0);
  echo $tid_min . ',' . $tid_max;
}

function wdja_cms_interface()
{
  switch($_GET['type'])
  {
    case 'get':
      wdja_cms_interface_get();
      break;
    case 'create':
      wdja_cms_interface_create();
      break;
    case 'loadsort':
      wdja_cms_interface_loadsort();
      break;
    case 'loadsortlists':
      wdja_cms_interface_loadsortlists();
      break;
    case 'loadidminmax':
      wdja_cms_interface_loadidminmax();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
