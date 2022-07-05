<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
$smodule = ii_htmlencode($_GET['module']);
if (ii_isnull($smodule)) $smodule = pp_get_configure_select_default();

function pp_get_configure_select($module='')
{
  global $variable;
  $tary = ii_get_valid_module();
  if (is_array($tary))
  {
    $tmpstr = '';
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    foreach ($tary as $key => $val)
    {
      if (!ii_isnull($module) && $val == $module) $tmprstr = $option_selected;
      else $tmprstr = $option_unselected;
      $tmprstr = str_replace('{$explain}', '(' . mm_get_genre_description($val) . ')' , $tmprstr);
      $tmprstr = str_replace('{$value}', $val, $tmprstr);
      $tmpstr .= $tmprstr;
    }
    return $tmpstr;
  }
}

function pp_get_configure_select_default()
{
  global $variable;
  $tary = ii_get_valid_module();
  if (is_array($tary))
  {
    foreach ($tary as $key => $val)
    {
      return $val;
    }
  }
}

function pp_change_configure_explain($strers, $module)
{
  if (!ii_isnull($strers))
  {
    $tary = explode('.', $strers);
    if (count($tary) == 2)
    {
      $tstr1 = ii_itake('global.lng_mdl.' . $tary[0], 'lng');
      if (ii_isnull($tstr1)) $tstr1 = $tary[0];
      $tstr2 = ii_itake('global.lng_cfg.' . $tary[1], 'lng');
      if (ii_isnull($tstr2)) $tstr2 = $tary[1];
      $tmpstr = $tstr1 . '.' . $tstr2;
    }
    else
    {
      $tmpstr = ii_itake('global.lng_cfg.' . $strers, 'lng');
      if (ii_isnull($tmpstr)) $tmpstr = @ii_itake('global.' . $module . ':cfg.' . $strers, 'lng');
      if (ii_isnull($tmpstr)) $tmpstr = $strers;
    }
    return $tmpstr;
  }
}

function wdja_cms_admin_manage_editdisp()
{
  $tbackurl = $_GET['backurl'];
  $tmodule = ii_get_safecode($_GET['module']);
  $trootstr = ii_get_actual_route($tmodule) . '/common/config' . XML_SFX;
  if (file_exists($trootstr))
  {
    $tmode = ii_get_xrootatt($trootstr, 'mode');
    $torder = $_POST['xmlconfig_order'];
    if (ii_right($torder, 1) == ',') $torder = ii_left($torder, strlen($torder) - 1);
    $tary = explode(',', $torder);
    $tmpstr = '';
    $tmpstr .= '<?xml version="1.0" encoding="' . CHARSET . '"?>' . CRLF;
    $tmpstr .= '<xml mode="' . $tmode . '" author="wdja">' . CRLF;
    $tmpstr .= '  <configure>' . CRLF;
    foreach ($tary as $key => $val)
    {
      $tmpstr .= '    <item varstr="' . ii_htmlencode($val) . '" strvalue="' . ii_htmlencode(stripslashes(ii_cstr($_POST[str_replace('.', '_dot_', $val)]))) . '" />' . CRLF;
    }
    $tmpstr .= '  </configure>' . CRLF;
    $tmpstr .= '</xml>' . CRLF;
    if (file_put_contents($trootstr, $tmpstr)) wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('manage.notexists', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_action()
{
  switch($_GET['action'])
  {
    case 'edit':
      wdja_cms_admin_manage_editdisp();
      break;
  }
}

function wdja_cms_admin_manage_edit()
{
  global $smodule;
  global $nuri;
  $tmodule = $smodule;
  $trootstr = ii_get_actual_route($tmodule) . '/common/config' . XML_SFX;
  if (file_exists($trootstr))
  {
    $torder = '';
    $tmpstr = ii_itake('manage.edit', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
    $tdoc = new DOMDocument();
    $tdoc -> load($trootstr);
    $txpath = new DOMXPath($tdoc);
    $tquery = '//xml/configure/item';
    $trests = $txpath -> query($tquery);
    foreach ($trests as $trest)
    {
      $tstr1 = $trest -> getAttribute('varstr');
      $tstr2 = $trest -> getAttribute('strvalue');
      $torder .= $tstr1 . ',';
      $tmptstr = $tmpastr;
      $tmptstr = str_replace('{$explain}', pp_change_configure_explain($tstr1, $tmodule), $tmptstr);
      $tmptstr = str_replace('{$varstr}', str_replace('.', '_dot_', $tstr1), $tmptstr);
      $tmptstr = str_replace('{$strvalue}', $tstr2, $tmptstr);
      $tmprstr = $tmprstr . $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = str_replace('{$module}', ii_htmlencode($tmodule), $tmpstr);
    $tmpstr = str_replace('{$order}', $torder, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else wdja_cms_admin_msg(ii_itake('manage.notexists', 'lng'), $nuri, 1);
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'edit':
      return wdja_cms_admin_manage_edit();
      break;
    default:
      return wdja_cms_admin_manage_edit();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
