<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_admin_manage_delete()
{
  global $nuri;
  $tfile = $_GET['file'];
  $badstr = array("/","..","../","<", ">", "--", ":/", "\0", "\'", "\/\*","\.\.\/", "\.\/", "%00", "\r",' ', '"', "'", "   ", "%3C", "%3E", "'",'#','=','`','$','&',';','(',')', '<?', '?>');
  foreach($badstr as $key) {
    if (strstr($tfile,$key))
    {
        wdja_cms_admin_msg(ii_itake('manage.delete_error', 'lng'), $nuri, 1);
    }
  }
  $tcache_dir = ii_get_actual_route('./') . CACHE_DIR;
  $tfilename = $tcache_dir . '/' . $tfile;
  $tfilename = iconv (CHARSET, 'cp936', $tfilename);
  if (unlink($tfilename))
  {
    wdja_cms_admin_msg(ii_itake('manage.delete_success', 'lng'), $nuri, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('manage.delete_error', 'lng'), $nuri, 1);
  }
}

function wdja_cms_admin_manage_removeall()
{
  global $nuri;
  @ii_cache_remove();
  wdja_cms_admin_msg(ii_itake('manage.delete_success', 'lng'), $nuri, 1);
}

function wdja_cms_admin_manage_action()
{
  switch($_GET['action'])
  {
    case 'delete':
      wdja_cms_admin_manage_delete();
      break;
    case 'removeall':
      wdja_cms_admin_manage_removeall();
      break;
  }
}

function wdja_cms_admin_manage_list()
{
  $tmpstr = ii_ireplace('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{#recurrence_ida}');
  $tcache_dir = ii_get_actual_route('./') . CACHE_DIR;
  $tcdirs = dir($tcache_dir);
  while($tentry = $tcdirs -> read())
  {
    if (is_numeric(strpos($tentry, '.')))
    {
      $ttentry = iconv('cp936', CHARSET, $tentry);
      $tcachename = ii_get_lrstr($ttentry, '.', 'left');
      if (!(ii_isnull($tcachename)))
      {
        $tmptstr = str_replace('{$cache_name}', $tcachename, $tmpastr);
        $tmptstr = str_replace('{$file_name}', urlencode($ttentry), $tmptstr);
        $tmprstr = $tmprstr . $tmptstr;
      }
    }
  }
  $tcdirs -> close();
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
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