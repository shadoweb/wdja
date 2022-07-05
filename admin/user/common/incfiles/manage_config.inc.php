<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
$ncontrol = 'select,lock,delete';
$nsearch = 'username,id';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function pp_get_manage_module()
{
  $tappstr = 'sys_manage_module';
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
  }
  else
  {
    $tpath = ii_get_actual_route('./');
    $tmanage_module = pp_get_mymanage_module($tpath);
    if (ii_right($tmanage_module, 1) == '|') $tmanage_module = ii_left($tmanage_module, strlen($tmanage_module) - 1);
    $tary = explode('|', $tmanage_module);
    ii_cache_put($tappstr, 1, $tary);
    $GLOBALS[$tappstr] = $tary;
  }
  return $GLOBALS[$tappstr];
}

function pp_get_mymanage_module($strers)
{
  $tpath = $strers;
  $twebdir = dir($tpath);
  while($tentry = $twebdir -> read())
  {
    if (!(is_numeric(strpos($tentry, '.'))))
    {
      $tfilename = $tpath . $tentry . '/common/config' . XML_SFX;
      $tcfilename = $tpath . $tentry . '/common/language/manage' . XML_SFX;
      $tfoldersnames = $tpath . $tentry;
      $tfoldersnames = str_replace('../', '', $tfoldersnames);
      $tfoldersnames = str_replace('./', '', $tfoldersnames);
      if (file_exists($tcfilename)) $tmpstr .= $tfoldersnames . '|';
      if (file_exists($tfilename))
      {
        if (ii_get_xrootatt($tfilename, 'mode') == 'wdjafgf') $tmpstr .= pp_get_mymanage_module($tpath . $tentry . '/');
      }
    }
  }
  $twebdir -> close();
  return $tmpstr;
}

function pp_manage_popedom($strers)
{
  $option_uncheckbox = ii_itake('global.tpl_config.option_uncheckbox', 'tpl');
  $option_checkbox = ii_itake('global.tpl_config.option_checkbox', 'tpl');
  $html_kong = ii_itake('global.tpl_config.html_kong', 'tpl');
  $html_br = ii_itake('global.tpl_config.html_br', 'tpl');
  $html_p = ii_itake('global.tpl_config.html_p', 'tpl');
  $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
  $tmpstr = '';
  $tarys = pp_get_manage_module();
  if (is_array($tarys))
  {
    foreach($tarys as $key => $val)
    {
      $tmodule = $val;
      if (!ii_isnull($tmodule))
      {
        $tmodulestr = ii_itake('global.' . $tmodule . ':manage.mgtitle', 'lng');
        if (ii_isnull($tmodulestr)) $tmodulestr = '?';
        if (!is_numeric(strpos($val, '/')))
        {
          $tcount = 0;
          if (strlen($tmpstr) > strlen($html_p))
          {
            if (ii_right($tmpstr, strlen($html_p)) != $html_p) $tmpstr .= $html_p;
          }
        }
        else
        {
          $tcount += 1;
          $tmodulestr = str_replace('{$explain}', $tmodulestr, $font_disabled);
        }
        if (ii_cinstr($strers, $tmodule, ',')) $tstrs = $option_checkbox;
        else $tstrs = $option_uncheckbox;
        $tstrs = str_replace('{$explain}', 'popedom[]', $tstrs);
        $tstrs = str_replace('{$value}', $val, $tstrs);
        $tmpstr .= str_replace('{$modulestr}', $tmodulestr, $tstrs);
        if ($tcount == 0) $tmpstr .= $html_p;
      }
    }
    return $tmpstr;
  }
  else return 'error!';
}

function wdja_cms_admin_manage_adddisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tusername = ii_get_safecode($_POST['username']);
  $tadmin_password = ii_md5($_POST['admin_password']);
  if (!wdja_cms_ckpassword($tadmin_password)) wdja_cms_admin_msg(ii_itake('manage.admin_password_err', 'lng'), $tbackurl, 1);
  if (ii_isnull($tusername)) mm_client_alert(str_replace('[]', '[' . ii_itake('global.lng_config.username', 'lng') . ']', ii_itake('global.lng_public.insert_empty', 'lng')), -1);
  $tsuper = ii_get_num($_POST['super']);
  if ($tsuper == 1)
  {
    $tpopedom = '-1';
  }
  else
  {
    $tpopedom = $_POST['popedom'];
    if (is_array($tpopedom)) $tpopedom = implode(',', $tpopedom);
  }
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('name') . "='" . $tusername . "'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
  }
  else
  {
    $tsqlstr = "insert into $ndatabase (" . ii_cfname('name') . "," . ii_cfname('pword') . "," . ii_cfname('popedom') . "," . ii_cfname('lock') . "," . ii_cfname('lasttime') . "," . ii_cfname('lastip') . ") values ('$tusername','" . ii_md5($_POST['password']) . "','$tpopedom','" . ii_get_num($_POST['lock']) . "','" . ii_now() . "','" . ii_get_client_ip() . "')";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
    }
    else
    {
      wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
    }
  }
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn, $nurlpre;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $tsuper = ii_get_num($_POST['super']);
  $tadmin_password = ii_md5($_POST['admin_password']);
  if (!wdja_cms_ckpassword($tadmin_password)) wdja_cms_admin_msg(ii_itake('manage.admin_password_err', 'lng'), $tbackurl, 1);
  if ($tsuper == 1)
  {
    $tpopedom = '-1';
  }
  else
  {
    $tpopedom = $_POST['popedom'];
    if (is_array($tpopedom)) $tpopedom = implode(',', $tpopedom);
  }
  $tsqlstr = "update $ndatabase set ";
  if (!ii_isnull($_POST['password'])) {
      $tsqlstr .= ii_cfname('pword') . "='" . ii_md5($_POST['password']) . "',";
      $tbackurl = $nurlpre.'/'.ADMIN_FOLDER.'/?action=logout';
  }
  $tsqlstr .= ii_cfname('popedom') . "='" . $tpopedom . "'," . ii_cfname('lock') . "=" . ii_get_num($_POST['lock']);
  $tsqlstr .= " where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs)
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
    case 'add':
      wdja_cms_admin_manage_adddisp();
      break;
    case 'edit':
      wdja_cms_admin_manage_editdisp();
      break;
    case 'delete':
      wdja_cms_admin_deletedisp($ndatabase, $nidfield);
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
  }
}

function wdja_cms_admin_manage_add()
{
  $tmpstr = ii_ireplace('manage.add', 'tpl');
  return $tmpstr;
}

function wdja_cms_admin_manage_edit()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    if ($trs[ii_cfname('popedom')] == '-1')
    {
      $tsuper = 1;
      $tpopedom = '';
    }
    else
    {
      $tsuper = 0;
      $tpopedom = ii_htmlencode($trs[ii_cfname('popedom')]);
    }
    $tmpstr = ii_itake('manage.edit', 'tpl');
    $tmpstr = str_replace('{$id}', ii_htmlencode($trs[$nidfield]), $tmpstr);
    $tmpstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfname('name')]), $tmpstr);
    $tmpstr = str_replace('{$super}', $tsuper, $tmpstr);
    $tmpstr = str_replace('{$popedom}', $tpopedom, $tmpstr);
    $tmpstr = str_replace('{$lock}', ii_htmlencode($trs[ii_cfname('lock')]), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.not_exist', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_list()
{
  global $ndatabase, $nidfield, $nfpre, $npagesize;
  $toffset = ii_get_num($_GET['offset']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where $nidfield>0";
  if ($search_field == 'username') $tsqlstr .= " and " . ii_cfname('name') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'id') $tsqlstr .= " and $nidfield=" . ii_get_num($search_keyword);
  $tsqlstr .= " order by $nidfield desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tusername = ii_htmlencode($trs[ii_cfname('name')]);
      if ($trs[ii_cfname('lock')] == 1) $tusername = str_replace('{$explain}', $tusername, $font_disabled);
      $tmptstr = str_replace('{$username}', $tusername, $tmpastr);
      $tmptstr = str_replace('{$usernamestr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('name')])), $tmptstr);
      $tmptstr = str_replace('{$lasttime}', ii_get_date($trs[ii_cfname('lasttime')]), $tmptstr);
      $tmptstr = str_replace('{$lastip}', ii_htmlencode($trs[ii_cfname('lastip')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'add':
      return wdja_cms_admin_manage_add();
      break;
    case 'edit':
      return wdja_cms_admin_manage_edit();
      break;
    case 'list':
      return wdja_cms_admin_manage_list();
      break;
    default:
      return wdja_cms_admin_manage_list();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
