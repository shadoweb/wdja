<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: shadoweb@qq.com
// Web: http://www.wdja.net/
//****************************************************

function ii_isAdmin()
{
  $bool = false;
  $pstrurl = str_replace(dirname($_SERVER['PHP_SELF']),'',$_SERVER['PHP_SELF']);
  $strurl = str_replace('/', '', dirname($_SERVER['PHP_SELF']));
  $strlen = strlen(ADMIN_FOLDER);
  if(ADMIN_FOLDER == substr($strurl, 0, $strlen)) return true;
  if($pstrurl == '/manage.php' || $pstrurl == '/manage-topic.php' || $pstrurl == '/manage-dispose.php') return true;
  if(ADMIN_FOLDER == $strurl) return false;
  return $bool;
}

function ii_isMobileAgent()
{
  $bool = false;
  $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
  if (strpos($userAgent, 'android') && strpos($userAgent, 'mobile')) $bool = true;
  else if (strpos($userAgent, 'iphone')) $bool = true;
  else if (strpos($userAgent, 'ipod')) $bool = true;
  else if (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false) $bool = true;
  return $bool;
}

function ii_conn_init()
{
  global $conn, $db_host, $db_username, $db_password, $db_database;
  $conn = new mysqli($db_host, $db_username, $db_password, $db_database);
  if(mysqli_connect_errno()) die('MYSQL.Connect.Error!');
  mysqli_query($conn,'set names utf8'); 
  mysqli_select_db($db_database, $conn);
}

function ii_conn_query($sqlstr, $conn)
{
  return mysqli_query($conn,$sqlstr); 
}

function ii_conn_fetch_array($result)
{
  return mysqli_fetch_array($result);//只取关联数组(MYSQL_ASSOC - 关联数组,MYSQL_NUM - 数字数组,MYSQL_BOTH - 默认。同时产生关联和数字数组)
}

function ii_conn_fetch_all($result)
{
  return mysqli_fetch_all($result,MYSQL_ASSOC);//只取关联数组(MYSQL_ASSOC - 关联数组,MYSQL_NUM - 数字数组,MYSQL_BOTH - 默认。同时产生关联和数字数组)
}

function ii_conn_insert_id($conn)
{
  return mysqli_insert_id($conn);
}

function ii_cache_is($name)
{
  $cache_dir = ii_get_actual_route('./') . CACHE_DIR;
  $cache_filename = $cache_dir . '/' . $name . '.inc.php';
  if (file_exists($cache_filename))
  {
    return true;
  }
  else
  {
    return false;
  }
}

function ii_cache_get($name, $type)
{
  $cache = new cc_cache;
  $cache -> cachename = $name;
  $cache -> filename = ii_get_actual_route('./') . CACHE_DIR . '/' . $name . '.inc.php';
  switch ($type)
  {
    case -1:
      return $cache -> get_file_text();
      break;
    case 1:
      return $cache -> get_file_array();
      break;
    default:
      return $cache -> get_file_text();
      break;
  }
}

function ii_cache_put($name, $type, $data)
{
  $cache_dir = ii_get_actual_route('./') . CACHE_DIR;
  if (!(is_dir($cache_dir))) @mkdir($cache_dir, 0700);
  $cache = new cc_cache;
  $cache -> cachename = $name;
  $cache -> filename = $cache_dir . '/' . $name . '.inc.php';
  switch ($type)
  {
    case -1:
      return $cache -> put_file_text($data);
      break;
    case 1:
      return $cache -> put_file_array($data);
      break;
    default:
      return $cache -> put_file_text($data);
      break;
  }
}

function ii_cache_remove($name = '')
{
  $cache_dir = ii_get_actual_route('./') . CACHE_DIR;
  if (!ii_isnull($name))
  {
    $cache_filename = $cache_dir . '/' . $name . '.inc.php';
    return unlink($cache_filename);
  }
  else
  {
    $tbool = true;
    $tcdirs = dir($cache_dir);
    while($tentry = $tcdirs -> read())
    {
      if (is_numeric(strpos($tentry, '.')))
      {
        if (!(ii_isnull(ii_get_lrstr($tentry, '.', 'left'))))
        {
          if (!unlink($cache_dir . '/' . $tentry)) $tbool = false;
        }
      }
    }
    $tcdirs -> close();
    return $tbool;
  }
}

function ii_cstr($strers)
{
  if (get_magic_quotes_gpc()) return $strers;
  else return addslashes($strers);
}

function ii_creplace($strers)
{
  if (!(ii_isnull($strers)))
  {
    $tstrers = $strers;
    $tregm = preg_match_all('({\$=(.[^\}]*)})', $tstrers, $tregarys);
    if ($tregm)
    {
      for ($i = 0; $i <= count($tregarys[0]) - 1; $i++)
      {
        $tstrers = str_replace($tregarys[0][$i], ii_eval($tregarys[1][$i]), $tstrers);
      }
    }
    return $tstrers;
  }
}

function ii_cinstr($strers, $str, $spstr)
{
  $tstrers = strval($strers);
  $tstr = strval($str);
  if ($tstrers == $tstr)
  {
    return true;
  }
  elseif (is_numeric(strpos($tstrers, $spstr . $tstr . $spstr)))
  {
    return true;
  }
  elseif (ii_get_lrstr($tstrers, $spstr, 'left') == $tstr)
  {
    return true;
  }
  elseif (ii_get_lrstr($tstrers, $spstr, 'right') == $tstr)
  {
    return true;
  }
  else
  {
    return false;
  }
}

function ii_cfname($field)
{
  global $nfpre;
  return $nfpre . $field;
}

function ii_cfnames($fpre, $field)
{
  return $fpre . $field;
}

function ii_ctemplate(&$templatestr, $distinstr)
{
  if (is_numeric(strpos($templatestr, $distinstr)))
  {
    $tarys = explode($distinstr, $templatestr);
    if (count($tarys) == 3)
    {
      $templatestr = $tarys[0] . WDJA_CINFO . $tarys[2];
      return $tarys[1];
    }
  }
}

function ii_ctemplate_infos(&$templatestr, $distinstr)
{
  if (is_numeric(strpos($templatestr, $distinstr)))
  {
    $tarys = explode($distinstr, $templatestr);
    if (count($tarys) == 3)
    {
      $templatestr = $tarys[0] . WDJA_CINFO_INFOS . $tarys[2];
      return $tarys[1];
    }
  }
}

function ii_cidary($strers)
{
  if (!ii_isnull($strers))
  {
    $treturn = true;
    $tarys = explode(',', $strers);
    foreach($tarys as $key => $val)
    {
      if (!(is_numeric($val))) $treturn = false;
    }
    return $treturn;
  }
}

function ii_cper($num, $mum)
{
  if ($num == 0 || $mum ==0) return 0;
  else return number_format($num / $mum, 2) * 100;
}

function ii_curl($baseurl, $url)
{
  if (!ii_isnull($url))
  {
    if (ii_left($url, 1) == '/') return $url;
    else
    {
      if (ii_isnull($baseurl) || (ii_right($baseurl, 1) == '/')) return $baseurl . $url;
      else return $baseurl . '/' . $url;
    }
  }
}

function ii_cvgenre($strers)
{
  if (!ii_isnull($strers))
  {
    return str_replace('/', '.', $strers);
  }
}

function ii_csize($size)
{
  if ($size >= 1073741824) return (intval(($size / 1073741824) * 1000) / 1000) . 'GB';
  elseif ($size >= 1048576) return (intval(($size / 1048576) * 1000) / 1000) . 'MB';
  elseif ($size >= 1024) return (intval(($size / 1024) * 1000) / 1000) . 'KB';
  else return $size . 'B';
}

function ii_deldir($dir)
{
  $tdirs = opendir($dir);
  while ($tfile = readdir($tdirs))
  {
    if($tfile != '.' && $tfile!='..')
    {
      $tpath = $dir . '/' . $tfile;
      if(!is_dir($tpath)) unlink($tpath);
      else ii_deldir($tpath);
    }
  }
  closedir($tdirs);
  if(rmdir($dir)) return true;
  else return false;
}

function ii_dateadd($interval, $num, $date)
{
  $tdate = ii_mktime($date);
  if (!ii_isnull($tdate))
  {
    switch ($interval)
    {
      case 'w':
        $tretval = $tdate + $num * 604800;
        break;
      case 'd':
        $tretval = $tdate + $num * 86400;
        break;
      case 'h':
        $tretval = $tdate + $num * 3600;
        break;
      case 'n':
        $tretval = $tdate + $num * 60;
        break;
      case 's':
        $tretval = $tdate + $num;
        break;
    }
    $tretval = date('Y-m-d G:i:s', $tretval);
    return $tretval;
  }
}


function ii_datediff($interval, $date1, $date2)
{
  $tdate1 = ii_mktime($date1);
  $tdate2 = ii_mktime($date2);
  if (!ii_isnull($tdate1) && !ii_isnull($tdate2))
  {
    $tdifference = $tdate2 - $tdate1;
    switch ($interval)
    {
      case 'w':
        $tretval = bcdiv($tdifference, 604800);
        break;
      case 'd':
        $tretval = bcdiv($tdifference, 86400);
        break;
      case 'h':
        $tretval = bcdiv($tdifference, 3600);
        break;
      case 'n':
        $tretval = bcdiv($tdifference, 60);
        break;
      case 's':
        $tretval = $tdifference;
        break;
    }
    return $tretval;
  }
}

function ii_eval($strers)
{
  if (!(ii_isnull($strers)))
  {
    if (substr($strers, 0 ,1) == '#')
    {
      $tstrers = substr($strers, 1, strlen($strers) - 1);
      eval('$tstr = $GLOBALS[' . $tstrers . '];');
    }
    else
    {
      eval('$tstr = ' . $strers . ';');
    }
    return $tstr;
  }
}

function ii_encode_newline($strers)
{
  if (!ii_isnull($strers))
  {
    $tstrers = $strers;
    $tstrers = str_replace(chr(13) . chr(10), chr(10), $tstrers);
    $tstrers = str_replace(chr(10), chr(13) . chr(10), $tstrers);
    return $tstrers;
  }
}

function ii_encode_text($strers)
{
  $tstrers = $strers;
  if (!ii_isnull($tstrers))
  {
    $tstrers = str_replace('$', '&#36;', $tstrers);
    $tstrers = str_replace('@', '&#64;', $tstrers);
    return $tstrers;
  }
}

function ii_encode_article($strers)
{
  $tstrers = ii_encode_newline($strers);
  if (!ii_isnull($tstrers))
  {
    $tstrers = str_replace(chr(39), '&#39;', $tstrers);
    $tstrers = str_replace(chr(32) . chr(32), '&nbsp;&nbsp;', $tstrers);
    $tstrers = str_replace(chr(13) . chr(10), '<br />', $tstrers);
    return $tstrers;
  }
}

function ii_encode_scripts($strers)
{
  if (!ii_isnull($strers))
  {
    $tstrers = $strers;
    $tstrers = str_replace('\\', '\\\\', $tstrers);
    $tstrers = str_replace('\'', '\\\'', $tstrers);
    $tstrers = str_replace('"', '\\"', $tstrers);
    return $tstrers;
  }
}

function ii_format_date($date, $type)
{
  $tdate = ii_mktime($date);
  if (!ii_isnull($tdate))
  {
    switch($type)
    {
      case 0:
        return date('YmdGis', $tdate);
        break;
      case 1:
        return date('Y-m-d', $tdate);
        break;
      case 2:
        return date('Y/m/d', $tdate);
        break;
      case 3:
        return date('Y.m.d', $tdate);
        break;
      case 10:
        return date('mdis', $tdate);
        break;
      case 11:
        return date('m.d G:i', $tdate);
        break;
      case 20:
        return date('Gis', $tdate);
        break;
      case 21:
        return date('G:i:s', $tdate);
        break;
      default:
        return date('Y-m-d', $tdate);
        break;
    }
  }
}

function ii_format_ip($ip, $type)
{
  if (!(ii_isnull($ip)))
  {
    $tarys = explode('.', $ip);
    if (count($tarys) == 4)
    {
      switch($type)
      {
        case 1:
          return $tarys[0] . '.' . $tarys[1] . '.' . $tarys[2] . '.*';
          break;
        case 2:
          return $tarys[0] . '.' . $tarys[1] . '.*.*';
          break;
        case 3:
          return $tarys[0] . '.*.*.*';
          break;
        default:
          return $tarys[0] . '.' . $tarys[1] . '.' . $tarys[2] . '.' . $tarys[3];
          break;
      }
    }
  }
}

function ii_fileico($strers)
{
  $ttypelist = '.asp.asa.aspx.bat.bmp.css.cfm.com.doc.db.dll.exe.fla.gif.htm.html.inc.ini.jpg.js.wdja.log.mdb.mid.mp3.png.php.rm.rar.swf.txt.wav.xls.xml.zip';
  $tfiletype = ii_get_lrstr($strers, '.', 'right');
  if (ii_cinstr($ttypelist, $tfiletype, '.')) return $tfiletype;
  else return 'others';
}

function ii_get_arymax($strary, $strmax = 1)
{
  $tary = $strary;
  $tmax = ii_get_num($strmax, 1);
  if (is_array($tary))
  {
    foreach ($tary as $key => $val)
    {
      if (ii_get_num($val, 0) > $tmax) $tmax = ii_get_num($val, 0);
    }
  }
  return $tmax;
}

function ii_get_actual_route($routestr)
{
  global $nroute;
  if (ii_isnull($routestr)) $routestr = './';
  switch ($nroute)
  {
    case 'grandchild':
      $troot = '../../../' . $routestr;
      break;
    case 'child':
      $troot = '../../' . $routestr;
      break;
    case 'node':
      $troot = '../' . $routestr;
      break;
    default:
      $troot = $routestr;
      break;
  }
  return $troot;
}

function ii_get_actual_genre($routestr, $route)
{
  global $nroute;
  if (ii_isnull($route)) $tnroute = $nroute;
  else $tnroute = $route;
  $troutestr = dirname($routestr);
  $troutestr = str_replace('\\', '/', $troutestr);
  $troutestr = ii_get_lrstr($troutestr, '/common', 'left');
  $tary = explode('/', $troutestr);
  $tarycount = count($tary);
  switch ($tnroute)
  {
    case 'grandchild':
      if ($tarycount >= 3) $tgenre = $tary[$tarycount - 3] . '/' . $tary[$tarycount - 2] . '/' . $tary[$tarycount - 1];
      break;
    case 'child':
      if ($tarycount >= 2) $tgenre = $tary[$tarycount - 2] . '/' . $tary[$tarycount - 1];
      break;
    case 'node':
      if ($tarycount >= 1) $tgenre = $tary[$tarycount - 1];
      break;
    default:
      $tgenre = '';
      break;
  }
  return $tgenre;
}

function ii_get_active_things($type)
{
  switch($type)
  {
    case 'lng':
      $tthings = 'language';
      break;
    case 'sel':
      $tthings = 'language';
      break;
    case 'tpl':
      $tthings = 'template';
      if(ii_isMobileAgent()) $tthings = $tthings . '/' . $GLOBALS['m_skin' ];
      else $tthings = $tthings . '/' . $GLOBALS['default_skin' ];
      if(ii_isAdmin()) $tthings = 'template/default';
      break;
    case 'skin':
      $tthings = 'skin';
      break;
  }
  if (!(ii_isnull($tthings)))
  {
    $trthings = ii_get_safecode($_COOKIE[APP_NAME . 'config'][$tthings]);
    if (ii_isnull($trthings)) $trthings = $GLOBALS['default_' . $tthings];
    if (!ii_isnull($trthings) && ii_isMobileAgent() && $tthings == 'skin') $trthings = $GLOBALS['m_' . $tthings];
    if (ii_isAdmin() && $tthings == 'skin') $trthings = 'default';
  }
  return $trthings;
}

function ii_get_client_ip()
{
  $tclient_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  if(ii_isnull($tclient_ip))
  {
    $tclient_ip = $_SERVER['HTTP_CLIENT_IP'];
    if(ii_isnull($tclient_ip)) $tclient_ip = $_SERVER['REMOTE_ADDR'];
  }
  $tclient_ip = ii_get_safecode($tclient_ip);
  return $tclient_ip;
}

function ii_get_date($date)
{
  if (ii_isdate($date)) return $date;
  else return ii_now();
}

function ii_get_dirsize($dir)
{
  $tdirs = @opendir($dir);
  $tsize = 0;
  while ($tfile = @readdir($tdirs))
  {
    if ($tfile != '.' && $tfile != '..')
    {
      $tpath = $dir . '/' . $tfile;
      if (is_dir($tpath)) $tsize += ii_get_dirsize($tpath);
      elseif (is_file($tpath)) $tsize += filesize($tpath);
    }
  }
  @closedir($tdirs);
  return $tsize;
}

function ii_get_filetype($strers)
{
  if (!ii_isnull($strers))
  {
    return ii_get_lrstr($strers, '.', 'right');
  }
}

function ii_get_safecode($strers)
{
  if (!ii_isnull($strers))
  {
    $tstrers = $strers;
    $tstrers = str_replace('\'', '', $tstrers);
    $tstrers = str_replace(';', '', $tstrers);
    $tstrers = str_replace('--', '', $tstrers);
    return $tstrers;
  }
}

function ii_get_strvalue($strers, $str, $spstr = ';')
{
  $tregm = preg_match('((?:^|' . $spstr . ')' . $str . '=(.[^' . $spstr . ']*))', $strers, $tregarys);
  return $tregarys[1];
}

function ii_get_num($num, $default = 0)
{
  if (is_numeric($num))
  {
    if (is_numeric(strpos($num, '.')))
    {
      return doubleval($num);
    }
    else
    {
      return intval($num);
    }
  }
  else
  {
    return $default;
  }
}

function ii_get_hstr($str1, $str2)
{
  if (!ii_isnull($str1)) $tmpstr = $str1;
  else $tmpstr = $str2;
  return $tmpstr;
}

function ii_get_lrstr($strers, $spstr, $type)
{
  if (ii_isnull($spstr) || !(is_numeric(strpos($strers, $spstr))))
  {
    return $strers;
  }
  else
  {
    switch($type)
    {
      case 'left':
        return substr($strers, 0, strpos($strers, $spstr));
        break;
      case 'leftr':
        return substr($strers, 0, strrpos($strers, $spstr));
        break;
      case 'right':
        return substr($strers, -(strlen($strers) - strrpos($strers, $spstr) - strlen($spstr)));
        break;
      case 'rightr':
        return substr($strers, -(strlen($strers) - strpos($strers, $spstr) - strlen($spstr)));
        break;
      default:
        return $strers;
        break;
    }
  }
}

function ii_get_xinfo($sourcefile, $keyword)
{
  $tdoc = new DOMDocument();
  $tdoc -> load($sourcefile);
  $txpath = new DOMXPath($tdoc);
  $tquery = '//xml/configure/node';
  $tnode = $txpath -> query($tquery) -> item(0) -> nodeValue;
  $tquery = '//xml/configure/field';
  $tfield = $txpath -> query($tquery) -> item(0) -> nodeValue;
  $tquery = '//xml/configure/base';
  $tbase = $txpath -> query($tquery) -> item(0) -> nodeValue;
  $tfieldarys = explode(',', $tfield);
  for ($i = 0; $i <= (count($tfieldarys) - 1); $i++)
  {
    if ($tfieldarys[$i] == $keyword)
    {
      $tki = $i;
      continue;
    }
  }
  if (ii_get_num($tki, 0) == 0) $tki = 1;
  $tki = $tki * 2 + 1;
  $tquery = '//xml/' . $tbase . '/' . $tnode;
  $trests = $txpath -> query($tquery);
  foreach ($trests as $trest)
  {
    $tkarys[$trest -> childNodes -> item(1) -> nodeValue] = $trest -> childNodes -> item($tki) -> nodeValue;
  }
  return $tkarys;
}

function ii_get_xrootatt($sourcefile, $att)
{
  $tdoc = new DOMDocument();
  $tdoc -> load($sourcefile);
  $txpath = new DOMXPath($tdoc);
  $tquery = '//xml';
  $trests = $txpath -> query($tquery) -> item(0) -> getAttribute($att);
  return $trests;
}

function ii_get_variable($sourcefile)
{
  $tvfpre = ii_get_lrstr($sourcefile, '/common/config', 'left');
  $tvfpre = ii_get_lrstr($tvfpre, './', 'right');
  $tvfpre = str_replace('/', '.', $tvfpre);
  $tdoc = new DOMDocument();
  $tdoc -> load($sourcefile);
  $txpath = new DOMXPath($tdoc);
  $tquery = '//xml/configure/item';
  $trests = $txpath -> query($tquery);
  foreach ($trests as $trest)
  {
    $tarys[$tvfpre . '.' . $trest -> getAttribute('varstr')] = $trest -> getAttribute('strvalue');
  }
  return $tarys;
}

function ii_get_variable_config($path)
{
  $tarys = Array();
  $twebdir = dir($path);
  while($tentry = $twebdir -> read())
  {
    if (!(is_numeric(strpos($tentry, '.'))))
    {
      $tfilename = $path . $tentry . '/common/config' . XML_SFX;
      if (file_exists($tfilename))
      {
        $tary = ii_get_variable($tfilename);
        $tarys += $tary;
        if (ii_get_xrootatt($tfilename, 'mode') == 'wdjafgf') $tarys += ii_get_variable_config($path . $tentry . '/');
      }
    }
  }
  $twebdir -> close();
  return $tarys;
}

function ii_get_variable_init()
{
  $tappstr = 'variable';
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
  }
  else
  {
    $tpath = ii_get_actual_route('./');
    $tary = ii_get_variable_config($tpath);
    ii_cache_put($tappstr, 1, $tary);
    $GLOBALS[$tappstr] = $tary;
  }
}

function ii_get_valid_module($type = 'array')
{
  if ($type != 'string') $type = 'array';
  $tappstr = 'sys_valid_module_' . $type;
  if (ii_cache_is($tappstr))
  {
    if ($type == 'array') ii_cache_get($tappstr, 1);
    else $tmpstr = ii_cache_get($tappstr, -1);
  }
  else
  {
    $tpath = ii_get_actual_route('./');
    $tvalid_module = ii_get_myvalid_module($tpath);
    if (ii_right($tvalid_module, 1) == '|') $tvalid_module = ii_left($tvalid_module, strlen($tvalid_module) - 1);
    if ($type == 'array')
    {
      $tary = explode('|', $tvalid_module);
      ii_cache_put($tappstr, 1, $tary);
      $GLOBALS[$tappstr] = $tary;
    }
    else
    {
      $tmpstr = $tvalid_module;
      ii_cache_put($tappstr, -1, $tmpstr);
    }
  }
  if ($type == 'array') return $GLOBALS[$tappstr];
  else return $tmpstr;
}

function ii_get_myvalid_module($strers)
{
  $tpath = $strers;
  $twebdir = dir($tpath);
  while($tentry = $twebdir -> read())
  {
    if (!(is_numeric(strpos($tentry, '.'))))
    {
      $tfilename = $tpath . $tentry . '/common/config' . XML_SFX;
      $tfoldersnames = $tpath . $tentry;
      $tfoldersnames = str_replace('../', '', $tfoldersnames);
      $tfoldersnames = str_replace('./', '', $tfoldersnames);
      if (file_exists($tfilename))
      {
        $tmpstr .= $tfoldersnames . '|';
        if (ii_get_xrootatt($tfilename, 'mode') == 'wdjafgf') $tmpstr .= ii_get_myvalid_module($tpath . $tentry . '/');
      }
    }
  }
  $twebdir -> close();
  return $tmpstr;
}

function ii_htmlencode($strers, $type = 0)
{
  $tstrers = $strers;
  $tstrers = str_replace('&', '&amp;', $tstrers);
  $tstrers = str_replace('>', '&gt;', $tstrers);
  $tstrers = str_replace('<', '&lt;', $tstrers);
  $tstrers = str_replace('"', '&quot;', $tstrers);
  $tstrers = ii_encode_text($tstrers);
  if ($type == 1) $tstrers = stripslashes(ii_cstr($tstrers));
  return $tstrers;
}

function ii_itake($strers, $type, $all = 0)
{
  $txinfoary = ii_replace_xinfo_ary($strers, $type);
  $trootstr = $txinfoary[0];
  $tkey = $txinfoary[1];
  $tathings = ii_get_active_things($type);
  $tglobalstr = $trootstr;
  $tglobalstr = str_replace('../', '', $tglobalstr);
  $tglobalstr = str_replace(XML_SFX, '', $tglobalstr);
  $tglobalstr = str_replace('/', '_', $tglobalstr);
  $tglobalstr = APP_NAME . $tglobalstr . '_' . $tathings;
  if (!(is_array($GLOBALS[$tglobalstr]))) $GLOBALS[$tglobalstr] = ii_get_xinfo($trootstr, $tathings);
  if ($all == 0) return $GLOBALS[$tglobalstr][$tkey];
  else return $GLOBALS[$tglobalstr];
}

function ii_ireplace($strers, $type)
{
  global $nvalidate;
  $tstrers = ii_itake($strers, $type);
  $tstrers = ii_creplace($tstrers);
  $tstrers = mm_cvalhtml($tstrers, $nvalidate, '{@recurrence_valcode}');
  return $tstrers;
}

function ii_isdate($date)
{
  $trst = false;
  $tarys = explode(' ', $date);
  if (count($tarys) == 2)
  {
    $tarys2 = explode('-', $tarys[0]);
    $tarys3 = explode(':', $tarys[1]);
    if (count($tarys2) == 3 && count($tarys3) == 3)
    {
      $trst = true;
    }
  }
  else
  {
    $tarys2 = explode('-', $tarys[0]);
    if (count($tarys2) == 3) $trst = true;
  }
  return $trst;
}

function ii_isnull($strers)
{
  if (trim($strers) == '') return true;
  else return false;
}

function ii_isvalidemail($email)
{
  if (preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/i', $email)) return true;
  else return false;
}

function ii_iurl($type, $key, $set, $strers)
{
  global $ngenre;
  $ucode = mm_get_field($ngenre,$key,'ucode');
  $tset = ii_get_num($set);
  switch($tset)
  {
    case 0:
      switch($type)
      {
        case 'list':
          return '?type=list&amp;classid=' . $key;
          break;
        case 'detail':
          if(!ii_isnull($ucode)) $durl = $ucode.'.html';
          else $durl = '?type=detail&amp;id=' . $key;
          return $durl;
          break;
        case 'listpage':
          return '?' . ii_htmlencode(ii_replace_querystring('offset', $key));
          break;
        case 'cutepage':
          return '?' . ii_htmlencode(ii_replace_querystring('page', $key));
          break;
      }
      break;
    case 1:
      $tfolder = ii_get_strvalue($strers, 'folder');
      $tfiletype = ii_get_strvalue($strers, 'filetype');
      $ttime = ii_get_strvalue($strers, 'time');
      switch($type)
      {
        case 'list':
          return $tfolder . '/list/' . $key . '/0' . $tfiletype;
          break;
        case 'detail':
          return $tfolder . '/detail/' . ii_format_date($ttime, 2) . '/' . $key . $tfiletype;
          break;
        case 'listpage':
          $tlistkey = ii_get_strvalue($strers, 'listkey');
          return $tfolder . '/list/' . $tlistkey . '/' . $key . $tfiletype;
          break;
        case 'cutepage':
          $tcutekey = ii_get_strvalue($strers, 'cutekey');
          if ($key <= 1) return $tfolder . '/detail/' . ii_format_date($ttime, 2) . '/' . $tcutekey . $tfiletype;
          else return $tfolder . '/detail/' . ii_format_date($ttime, 2) . '/' . $tcutekey . '_' . $key . $tfiletype;
          break;
      }
      break;
    case 2:
      $tfiletype = ii_get_strvalue($strers, 'filetype');
      switch($type)
      {
        case 'list':
          return 'list-' . $key . '-0' . $tfiletype;
          break;
        case 'detail':
          return 'detail-' . $key . $tfiletype;
          break;
        case 'listpage':
          $tlistkey = ii_get_strvalue($strers, 'listkey');
          return 'list-' . $tlistkey . '-' . $key . $tfiletype;
          break;
        case 'cutepage':
          $tcutekey = ii_get_strvalue($strers, 'cutekey');
          if ($key <= 1) return 'detail-' . $tcutekey . $tfiletype;
          else return 'detail-' . $tcutekey . '-' . $key . $tfiletype;
          break;
      }
      break;
  }
}

function ii_left($strers, $len, $type = 0)
{
  if (!(ii_isnull($strers)))
  {
    if ($type == 0) return mb_substr($strers, 0, $len, CHARSET);
    else return substr($strers, 0, $len);
  }
}

function ii_mktime($date)
{
  if (ii_isdate($date))
  {
    $tarys = explode(' ', $date);
    $tarys2 = explode('-', $tarys[0]);
    $tarys3 = explode(':', $tarys[1]);
    $thour = ii_get_num($tarys3[0]);
    $tminute = ii_get_num($tarys3[1]);
    $tsecond = ii_get_num($tarys3[2]);
    $tmonth = ii_get_num($tarys2[1]);
    $tday = ii_get_num($tarys2[2]);
    $tyear = ii_get_num($tarys2[0]);
    return mktime($thour, $tminute, $tsecond, $tmonth, $tday, $tyear);
  }
}

function ii_mkdir($strers)
{
  $tnpath = '';
  $tstrers = $strers;
  $tstrary = explode('/', $tstrers);
  foreach($tstrary as $key => $val)
  {
    $tnpath .= $val . '/';
    if (!($val == '.') || ($val == '..'))
    {
      if (!(is_dir($tnpath))) @mkdir($tnpath, 0777);
    }
  }
}

function ii_md5($strers)
{
  return md5($strers);
}

function ii_now()
{
  return date('Y-m-d G:i:s', time() + 3600 * GMT_PLUS);
}

function ii_right($strers, $len, $type = 0)
{
  if (!(ii_isnull($strers)))
  {
    if ($type == 0) return mb_substr($strers, (mb_strlen($strers, CHARSET) - $len), $len, CHARSET);
    else return substr($strers, (strlen($strers) - $len), $len);
  }
}

function ii_random($length)
{
  $thash = '';
  $tchars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz1234567890';
  $tmax = strlen($tchars) - 1;
  mt_srand((double)microtime() * 1000000);
  for($i = 0; $i < $length; $i++)
  {
    $thash .= $tchars[mt_rand(0, $tmax)];
  }
  return $thash;
}

function ii_replace_xinfo_ary($strers, $type)
{
  global $nsort, $ngenre;
  $trootstr = ii_get_lrstr($strers, '.', 'leftr');
  $tkey = ii_get_lrstr($strers, '.', 'right');
  switch($type)
  {
    case 'tpl':
      $troot = 'common/template';
      if(ii_isMobileAgent()) $troot = $troot . '/' . $GLOBALS['m_skin' ];
      else $troot = $troot . '/' . $GLOBALS['default_skin' ];
      if(ii_isAdmin()) $troot = 'common/template/default';
      break;
    case 'lng':
      $troot = 'common/language';
      break;
    case 'sel':
      $troot = 'common/language';
      break;
    default:
      $troot = 'common';
      break;
  }
  if (substr($trootstr, 0, 7) == 'global.')
  {
    $trootstr = substr($trootstr, 7, strlen($trootstr) - 7);
    if (is_numeric(strpos($trootstr, ':')))
    {
      $trootstr = ii_get_lrstr($trootstr, ':', 'left') . '/' . $troot . '/' . ii_get_lrstr($trootstr, ':', 'right') . XML_SFX;
    }
    else
    {
      $trootstr = $troot . '/' . $trootstr . XML_SFX;
    }
  }
  else
  {
    $trootstr = $troot . '/' . $trootstr . XML_SFX;
    if (!ii_isnull($nsort)) $trootstr = $nsort . '/' . $trootstr;
    if (!ii_isnull($ngenre)) $trootstr = $ngenre . '/' . $trootstr;
  }
  $trootstr = ii_get_actual_route($trootstr);
  $txinfoary[0] = $trootstr;
  $txinfoary[1] = $tkey;
  return $txinfoary;
}

function ii_replace_querystring($str, $value, $urs = '')
{
  $tmpstr = '';
  if (!ii_isnull($urs)) $turs = $urs;
  else $turs = $_SERVER['QUERY_STRING'];
  if (ii_isnull($turs)) $tmpstr = $str . '=' . $value;
  else
  {
    $tvalue = ii_get_strvalue($turs, $str, '&');
    if (ii_isnull($tvalue))
    {
      $turs = str_replace($str . '=', '', $turs);
      if (ii_isnull($turs)) $tmpstr = $str . '=' . $value;
      else $tmpstr = $turs . '&' . $str . '=' . $value;
    }
    else
    {
      $turs = str_replace($str . '=' . $tvalue, $str . '=' . $value, $turs);
      $tmpstr = $turs;
    }
  }
  $tmpstr = str_replace('&&', '&', $tmpstr);
  return $tmpstr;
}

function ii_strlen($strers)
{
  return mb_strlen($strers, CHARSET);
}

function ii_show_num_select($value1, $value2, $value)
{
  $outputstr = '';
  $tvalue = ii_get_num($value);
  $tvalue1 = ii_get_num($value1);
  $tvalue2 = ii_get_num($value2);
  $option_unselected = ii_itake('global.tpl_config.xmlselect_unselect', 'tpl');
  $option_selected = ii_itake('global.tpl_config.xmlselect_select', 'tpl');
  for ($i = $tvalue1; $i <= $tvalue2; $i ++)
  {
    if ($i == $tvalue) $outputstr = $outputstr . $option_selected;
    else $outputstr = $outputstr . $option_unselected;
    $outputstr = str_replace('{$explain}', $i, $outputstr);
    $outputstr = str_replace('{$value}', $i, $outputstr);
  }
  return $outputstr;
}

function ii_show_old_select($value)
{
  $tyear = date('Y', time() + 3600 * GMT_PLUS);
  $tyear1 = $tyear - 60;
  $tyear2 = $tyear - 0;
  $tvalue = ii_get_num($value, -1);
  if ($tvalue == -1) $tvalue = $tyear - 30;
  return ii_show_num_select($tyear1, $tyear2, $tvalue);
}

function ii_show_xmlinfo_select($strers, $value, $template)
{
  global $nlng;
  $outputstr = '';
  if (is_numeric(strpos($strers, '|')))
  {
    $txinfostr = ii_get_lrstr($strers, '|', 'left');
    $tselstr = ii_get_lrstr($strers, '|', 'right');
  }
  else
  {
    $txinfostr = $strers;
  }
  $trxinfoary = ii_replace_xinfo_ary($txinfostr, 'sel');
  $troute = $trxinfoary[0];
  $tselectary = ii_get_xinfo($troute, $nlng);
  if (is_array($tselectary))
  {
    if (is_numeric(strpos($template, ':')))
    {
      $tarys = explode(':', $template);
      $tname = $tarys[0];
      $ttemplate = $tarys[1];
    }
    else
    {
      $ttemplate = $template;
    }
    $option_unselected = ii_itake('global.tpl_config.xmlselect_un' . $ttemplate, 'tpl');
    $option_selected = ii_itake('global.tpl_config.xmlselect_' . $ttemplate, 'tpl');
    foreach ($tselectary as $key => $val)
    {
      if (ii_isnull($tselstr) || ii_cinstr($tselstr, $key, ','))
      {
        if (ii_cinstr($value, $key, ','))
        {
          $outputstr = $outputstr . $option_selected;
        }
        else
        {
          $outputstr = $outputstr . $option_unselected;
        }
        $outputstr = str_replace('{$explain}', $val, $outputstr);
        $outputstr = str_replace('{$value}', $key, $outputstr);
      }
    }
    $outputstr = str_replace('{$name}', $tname, $outputstr);
    $outputstr = ii_creplace($outputstr);
  }
  return $outputstr;
}

function ii_unescape($strers)
{    
  $tstrers = rawurldecode($strers);
  preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U", $tstrers, $tarys);
  $tary = $tarys[0];
  foreach($tary as $key => $val)
  {
    if(substr($val, 0, 2) == "%u")
    {
      $tary[$key] = iconv("UCS-2BE", CHARSET, pack("H4",substr($val, -4)));
    }
    elseif(substr($val, 0, 3) == "&#x")
    {
      $tary[$key] = iconv("UCS-2BE", CHARSET, pack("H4",substr($val, 3, -1)));
    }
    elseif(substr($val, 0, 2) == "&#")
    {
      $tary[$key] = iconv("UCS-2BE", CHARSET, pack("n",substr($val, 2, -1)));
    }
  }
  return join("", $tary);
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: shadoweb@qq.com
// Web: http://www.wdja.net/
//****************************************************
?>