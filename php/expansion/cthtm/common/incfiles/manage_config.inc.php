<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
header("cache-control: no-cache, must-revalidate");
header("pragma: no-cache");
wdja_cms_admin_init();

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function pp_get_cthtm_select()
{
  global $variable;
  $tary = ii_get_valid_module();
  if (is_array($tary))
  {
    $tmpstr = '';
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    foreach ($tary as $key => $val)
    {
      $tmprstr = $option_unselected;
      if (!ii_isnull($variable[ii_cvgenre($val) . '.nindex'])) {
        $tmprstr = str_replace('{$explain}', '(' . mm_get_genre_description($val) . ')' , $tmprstr);
        $tmprstr = str_replace('{$value}', $val, $tmprstr);
      }
      else continue;
      $tmpstr .= $tmprstr;
    }
    return $tmpstr;
  }
}

function wdja_cms_admin_manage_index()
{
  $tmpstr = ii_ireplace('manage.index', 'tpl');
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  return wdja_cms_admin_manage_index();
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
