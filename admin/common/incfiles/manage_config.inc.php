<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
$admc_pstate = 'public';

function wdja_cms_top_lng()
{
  global $slng;
  $outputstr = '';
  $txinfostr = $strers;
  $trxinfoary = ii_replace_xinfo_ary('global.sel_lng.all', 'sel');
  $troute = $trxinfoary[0];
  $tselectary = ii_get_xinfo($troute, $slng);
  if (is_array($tselectary))
  {
    $option_unselected = ii_itake('global.tpl_config.admin_un_lng', 'tpl');
    $i = 0;
    foreach ($tselectary as $key => $val)
    {
      if (ii_isnull($tselstr) || ii_cinstr($tselstr, $key, ','))
      {
        $outputstr = $outputstr . $option_unselected;
        $outputstr = str_replace('{$explain}', $val, $outputstr);
        $outputstr = str_replace('{$value}', $key, $outputstr);
        $outputstr = str_replace('{$i}', $i, $outputstr);
      }
      $i++;
    }
    $outputstr = ii_creplace($outputstr);
  }
  return $outputstr;
}

function wdja_cms_top_lng_view()
{
  global $slng;
  $outputstr = '';
  $txinfostr = $strers;
  $trxinfoary = ii_replace_xinfo_ary('global.sel_lng.all', 'sel');
  $troute = $trxinfoary[0];
  $tselectary = ii_get_xinfo($troute, $slng);
  if (is_array($tselectary))
  {
    $option_selected = ii_itake('global.tpl_config.admin_lng', 'tpl');
    $i = 0;
    foreach ($tselectary as $key => $val)
    {
        if (ii_cinstr($slng, $key, ','))
        {
          if (ii_isnull($tselstr) || ii_cinstr($tselstr, $key, ','))
          {
            $outputstr = $outputstr . $option_selected;
            $outputstr = str_replace('{$explain}', $val, $outputstr);
            $outputstr = str_replace('{$value}', $key, $outputstr);
            $outputstr = str_replace('{$i}', $i, $outputstr);
          }
        break;
        }
      else
      {
        $i++;
        continue;
      }
    }
    $outputstr = ii_creplace($outputstr);
  }
  return $outputstr;
}

function pp_checkit()
{
  $tckname = $_POST['ckname'];
  if (!(ii_isnull($tckname)))
  {
    return 'Getenv: ' . getenv($tckname) . ', Get_cfg_var: ' . get_cfg_var($tckname) . ', Get_magic_quotes_gpc: ' . get_magic_quotes_gpc($tckname) . ', Get_magic_quotes_runtime: ' . get_magic_quotes_runtime($tckname);
  }
}

function pp_replace_pfs($array, $path, $folder)
{
  $tarray = $array;
  $tarray2 = Array();
  $tfolder = ii_get_lrstr($path, '/./', 'rightr');
  if (is_array($tarray))
  {
    foreach ($tarray as $key => $val)
    {
      $tkey = ii_creplace($key);
      $tkey = str_replace('{$path}', $path, $tkey);
      $tkey = str_replace('{$folder}', $tfolder, $tkey);
      $tarray2[$tkey] = $val;
    }
    return $tarray2;
  }
}

function pp_get_leftmenu_order($path)
{
  global $variable;
  $tstrers = ii_get_lrstr($path, '/./', 'rightr');
  $tstrers = ii_get_lrstr($tstrers, '/', 'leftr');
  switch($tstrers)
  {
    case ADMIN_FOLDER:
      return $variable[ADMIN_FOLDER . '.norder'];
      break;
    case USER_FOLDER:
      return $variable[USER_FOLDER . '.norder'];
      break;
    case 'support':
      return $variable['support.norder'];
      break;
    case 'expansion':
      return $variable['expansion.norder'];
      break;
    default:
      return ADMIN_FOLDER . ',' . $variable[ADMIN_FOLDER . '.morder'];
      break;
  }
}

function pp_get_leftmenu_array_config($path)
{
  global $nlng;
  $tarys = Array();
  $twebdir = dir($path);
  $torder = pp_get_leftmenu_order($path);
  while($tentry = $twebdir -> read())
  {
    if (!(is_numeric(strpos($tentry, '.'))))
    {
      if (!(ii_cinstr($torder, $tentry, ',')))
      {
        $torder .= ',' . $tentry;
      }
    }
  }
  $twebdir -> close();
  $torderary = explode(',', $torder);
  if (is_array($torderary))
  {
    foreach($torderary as $key => $val)
    {
      if (!(ii_isnull($val)))
      {
        $tfilename = $path . $val . '/common/guide' . XML_SFX;
        if (file_exists($tfilename))
        {
          $tary = pp_replace_pfs(ii_get_xinfo($tfilename, $nlng), $path . $val, $val);
          $tarys += $tary;
          if (ii_get_xrootatt($tfilename, 'mode') == 'wdjaf'){
              //添加模板配置菜单显示
              global $theme_guide;
              if($val == 'support') $tarys += $theme_guide;
              //添加模板配置菜单显示
              $tarys += pp_get_leftmenu_array_config($path . $val . '/');
          }
        }
      }
    }
  }
  return $tarys;
}

function pp_get_leftmenu_array()
{
  global $adms_appstr;
  if (ii_cache_is($adms_appstr))
  {
    ii_cache_get($adms_appstr, 1);
  }
  else
  {
    $tpath = ii_get_actual_route('./');
    $tary = pp_get_leftmenu_array_config($tpath);
    ii_cache_put($adms_appstr, 1, $tary);
    $GLOBALS[$adms_appstr] = $tary;
  }
  return $GLOBALS[$adms_appstr];
}

function wdja_cms_ckulogin()
{
    global $conn, $variable;
    $tislogin = 0;
    $tuname = ii_get_safecode($_POST['uname']);
    $tpassword = ii_md5($_POST['password']);
    $numMax = ii_get_num($variable['common.adminlog.maxerrornum'],0);
    $numMax_tips = str_replace('[]', '[' . $numMax . ']', ii_itake('config.ulogin_maxnum', 'lng'));
    if (wdja_cms_cklogin_erromax($tuname)) mm_client_alert($numMax_tips, -1);
    if (wdja_cms_cklogin($tuname, $tpassword)) $tislogin = 1;
    $tdatabase =  $variable['common.adminlog.ndatabase'];
    $tidfield =  $variable['common.adminlog.nidfield'];
    $tfpre =  $variable['common.adminlog.nfpre'];
    $tsqlstr = "insert into $tdatabase (" . ii_cfnames($tfpre, 'name') . "," . ii_cfnames($tfpre, 'time') . "," . ii_cfnames($tfpre, 'ip') . "," . ii_cfnames($tfpre, 'islogin') . ") values ('$tuname','" . ii_now() . "','" . ii_get_client_ip() . "','$tislogin')";
    if (ii_conn_query($tsqlstr, $conn))
    {
      if ($tislogin == 1)
      {
        header('location: admin_main.php');
      }
      else
      {
        mm_client_alert(ii_itake('config.ulogin_failed', 'lng'), -1);
      }
    }
    else
    {
      mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
    }
}

function wdja_cms_login()
{
  global $admc_name, $admc_pword;
  $taction = $_GET['action'];
  if (!(ii_isnull($taction)))
  {
    if ($taction == 'login') wdja_cms_ckulogin();
    else wdja_cms_ulogout();
  }
  else
  {
    if (wdja_cms_cklogin($admc_name, $admc_pword)) header('location: admin_main.php');
    else return ii_ireplace('manage.login', 'tpl');
  }
}

function wdja_cms_ulogout()
{
  unset($_SESSION[APP_NAME . 'admin_popedom']);
  unset($_SESSION[APP_NAME . 'admin_username']);
  header('location: ./');
}

function wdja_cms_frame()
{
  global $admc_name;
    $tmpstr = ii_itake('manage.frame', 'tpl');
    $tmpstr = str_replace('{$admin_user}', $admc_name, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
}

function wdja_cms_manage()
{
  return ii_ireplace('manage.manage', 'tpl');
}

function wdja_cms_left()
{
  global $admc_popedom;
  $tarray = pp_get_leftmenu_array();
  if (is_array($tarray))
  {
    $tplstr = ii_ireplace('manage.left', 'tpl');
    $tcrca = explode('{@recurrence_ida}', $tplstr);
    if (count($tcrca) == 3)
    {
      $tcrcastr = $tcrca[1];
      $tcrcb = explode('{@recurrence_idb}', $tcrcastr);
      if (count($tcrcb) == 3) $tcrcbstr = $tcrcb[1];
      $ttplstr = $tcrca[0];
      $tii = 0;
      foreach ($tarray as $key => $val)
      {
        if (is_numeric(strpos($key, 'description')))
        {
          if ($admc_popedom == -1 || $key == 'description'  || ii_cinstr($admc_popedom, ii_get_lrstr($key, ':', 'left'), ','))
          {
            $tstate = 1;
          }
          else
          {
            $tstate = 0;
          }
        }
        if ($tstate == 1)
        {
          if (ii_get_lrstr($key, ':', 'right') == 'description')
          {
            $ttplstr = str_replace(WDJA_CINFO, '', $ttplstr);
            $tstr = $tcrcb[0] . WDJA_CINFO . $tcrcb[2];
            $tstr = str_replace('{$description}', $val, $tstr);
            $tstr = str_replace('{$id}', $tii, $tstr);
            $ttplstr = $ttplstr . $tstr;
            $tii = $tii + 1;
          }
          elseif (ii_get_lrstr($key, ':', 'right') == 'icon')
          {
            $tstr = str_replace('{$icon}', $val, $ttplstr);
            $ttplstr = $tstr;
          }
          else
          {
            $tkey = $key;
            if (is_numeric(strpos($tkey, ':')))
            {
              if ($admc_popedom == '-1' || ii_cinstr($admc_popedom, ii_get_lrstr($tkey, ':', 'left'), ','))
              {
                $tkey = ii_get_lrstr($tkey, ':', 'right');
              }
            }
            if (!(is_numeric(strpos($tkey, ':'))))
            {
              $tstr = str_replace('{$topic}', $val, $tcrcbstr);
              $tstr = str_replace('{$ahref}', $tkey, $tstr);
              $ttplstr = str_replace(WDJA_CINFO, $tstr . WDJA_CINFO, $ttplstr);
              $tstrs = $tstrs . $tstr;
            }
          }
        }
      }
      $ttplstr = $ttplstr . $tcrca[2];
      $ttplstr = str_replace(WDJA_CINFO, '', $ttplstr);
      return $ttplstr;
    }
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>