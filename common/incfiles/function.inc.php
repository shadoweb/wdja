<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function ii_get_Infos($infos,$tpl='')
{
  $arys = json_decode($infos,true);
  if (is_array($arys)&& count($arys) > 0)
  {
    if(!ii_isnull($tpl)) $tmpstr = ii_itake('global.tpl_transfer.'.$tpl, 'tpl');
    else $tmpstr = ii_itake('global.tpl_transfer.infos', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    foreach ($arys as $info)
    {
      $tmptstr = $tmpastr;
      $tmptstr = str_replace('{$id}', $info[0], $tmptstr);
      $tmptstr = str_replace('{$title}', $info[1], $tmptstr);
      $tmptstr = str_replace('{$content}', $info[2], $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  }
  return $tmpstr;
}

function ii_get_array2json($array) {
  ii_get_newarray($array, 'urlencode', true);
  $json = json_encode($array,true);
  return urldecode($json);
}

function ii_get_newarray(&$array, $function, $apply_to_keys_also = false)
{
  static $recursive_counter = 0;
  if (++$recursive_counter > 1000) {
    die('possible deep recursion attack');
  }
  foreach ($array as $key => $value) {
    if (is_array($value)) {
      ii_get_newarray($array[$key], $function, $apply_to_keys_also);
    } else {
        if(is_numeric($value)) $array[$key] = $value;
        else $array[$key] = $function($value);
    }

    if ($apply_to_keys_also && is_string($key)) {
      $new_key = $function($key);
      if ($new_key != $key) {
        $array[$new_key] = $array[$key];
        unset($array[$key]);
      }
    }
  }
  $recursive_counter--;
}

function ii_get_string($argString)
{
  $string = $argString;
  if (is_numeric($string)) $string = strval($string);
  if ($string == null) $string = '';
  return $string;
}

function ii_get_request($argName, $argType = 'auto')
{
  $result = '';
  $name = $argName;
  $type = $argType;
  if ($type == 'auto')
  {
    $result = ii_get_request($name, 'post');
    if (ii_isnull($result)) $result = ii_get_request($name, 'get');
  }
  elseif ($type == 'post')
  {
    $post = $_POST;
    if (array_key_exists($name, $post))
    {
      $value = $post[$name];
      if (is_array($value)) $result = json_encode($value);
      else $result = ii_get_string($value);
    }
  }
  elseif ($type == 'get')
  {
    $get = $_GET;
    if (array_key_exists($name, $get))
    {
      $value = $get[$name];
      if (is_array($value)) $result = json_encode($value);
      else $result = ii_get_string($value);
    }
  }
  return $result;
}

function ii_require_ApiFile($path){
 $arys = ii_get_api_file_ary($path);
 foreach($arys as $ary){
  if(file_exists($ary)) require_once($ary);
 }
}

function ii_get_api_file_ary($path)
{
  $tappstr = 'sys_apifile_array';
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
  }else{
    $tpath = ii_get_api_file_path($path);
    $tpath = ii_get_lrstr($tpath, '|', 'leftr');
    $tary = explode('|', $tpath);
    ii_cache_put($tappstr, 1, $tary);
    $GLOBALS[$tappstr] = $tary;
  }
  return $GLOBALS[$tappstr];
}

function ii_get_api_file_path($path)
{
  if (!is_dir($path)) return;
  $path = rtrim(str_replace('//','/',str_replace('\\','/',$path )),'/').'/';
  $trs = '';
  $twebdir = dir($path);
  while($tentry = $twebdir -> read())
  {
    if (!(is_numeric(strpos($tentry, '.'))))
    {
      $tfilename = $path . $tentry . '/incfiles/api.inc.php';
      if (file_exists($tfilename)){
          $trs .= $tfilename.'|';//把API文件存数组里
      }
      $trs .= ii_get_api_file_path($path . $tentry);
    }
  }
  $twebdir -> close();
  return $trs;
}

/*检查是否到期
*可以只传一个日期参数(YYYY-MM-DD H:i:s),和当前时间比对:主要用于定期更新状态等场景
*或者添加一个日期和相差时长,支持年,月,周,日,时,分,秒:主要用于会员时长,订单有效期等固定期限的场景
*ii_check_expireDate('2020-12-01 10:10:10')
*ii_check_expireDate('2020-11-30 13:50:10','5','5')
*/
function ii_check_expireDate($date,$add='0',$type='0') {
  $bool = false;
  $tnow = strtotime(ii_now());
  $tdate = strtotime($date);
  switch($type) {
      case 0:
      $edate = strtotime("+".$add." years",$tdate);
      break;
      case 1:
      $edate = strtotime("+".$add." months",$tdate);
      break;
      case 2:
      $edate = strtotime("+".$add." week",$tdate);
      break;
      case 3:
      $edate = strtotime("+".$add." days",$tdate);
      break;
      case 4:
      $edate = strtotime("+".$add." hours",$tdate);
      break;
      case 5:
      $edate = strtotime("+".$add." minutes",$tdate);
      break;
      case 6:
      $edate = strtotime("+".$add." seconds",$tdate);
      break;
      default:
      $edate = $tdate;
      break;
  }
  if (date('Y-m-d H:i:s',$tnow) >= date('Y-m-d H:i:s',$edate)) $bool = true;
  return $bool;
}

function ii_format_checkData($data,$str,$dstr) {
  $tarys = '';
  $tary = $data;
  if (is_array($tary) && count($tary) > 0) {
      $plus = $str;
      for($i=0;$i<count($tary);$i++) {
          if ($i < count($tary) - 1) $tarys .= $tary[$i].$plus;
          else $tarys .= $tary[$i];
      }
  }
  else $tarys = $dstr;
  return $tarys;
}

//判断是否同一天
function ii_isSameDay($day1,$day2) {
  $bool = false;
  if (date('Y-m-d',strtotime($day1)) == date('Y-m-d',strtotime($day2))) $bool = true;
  return $bool;
}

//判断两天是否相连
function ii_isStreakDays($last_date,$this_date) {
  //格式'2020-02-23''
  $last_date = getdate(strtotime(date('Y-m-d',strtotime($last_date))));
  $this_date = getdate(strtotime(date('Y-m-d',strtotime($this_date))));
  if (($last_date['year']===$this_date['year'])&&($this_date['yday']-$last_date['yday']===1)) {
    return true;
  }elseif (($this_date['year']-$last_date['year']===1)&&($last_date['mon']-$this_date['mon']=11)&&($last_date['mday']-$this_date['mday']===30)) {
    return true;
  }else{
    return false;
  }
}

function ii_isWeixin() {
    $bool = true;
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_agent, 'MicroMessenger') === false) {
        $bool = false;
    } else {
        $bool = true;
     }
    return $bool;
}

function ii_isAdmin()
{
  $bool = false;
  $pstrurl = str_replace(dirname($_SERVER['PHP_SELF']),'',$_SERVER['PHP_SELF']);
  $strurl = str_replace('/', '', dirname($_SERVER['PHP_SELF']));
  $strlen = strlen(ADMIN_FOLDER);
  if (ADMIN_FOLDER == substr($strurl, 0, $strlen)) return true;
  if ((strpos($_SERVER['PHP_SELF'],ADMIN_FOLDER.'/index.php') !== false) || substr($pstrurl, 0, 7) == '/admin_' || substr($pstrurl, 0, 7) == '/manage' || $pstrurl == '/interface.php') return true;
  if (ADMIN_FOLDER == $strurl) return false;
  return $bool;
}

function ii_isMobileAgent()
{
  $bool = false;
  $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
  $userUrl = parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST);
  if (ii_isnull($userUrl)) $userUrl = $_SERVER['HTTP_HOST'];
  if ($userUrl == MOBILE_URL && MOBILE_URL != DEFAULT_URL) $bool = true;//用手机网址访问时,设定作为手机访问
  elseif (strpos($userAgent, 'android') && strpos($userAgent, 'mobile')) $bool = true;
  elseif (strpos($userAgent, 'iphone')) $bool = true;
  elseif (strpos($userAgent, 'ipod')) $bool = true;
  elseif (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false) $bool = true;
  return $bool;
}

function ii_isRobotsAgent()
{
  $bool = false;
  $ua = ii_getAllHeaders();
  $ua = $ua['User-Agent'];
  $spider = 'baidu,google,360,sogou,soso,bing,msn,yahoo,yandex,360Spider,Sosospider,MSNBot,YoudaoBot,YodaoBot,bingbot,ia_archiver,Baiduspider,BaiduSpider,baiduspider,Baiduspider,Baiduspider-image,Baiduspider-mobile,Baiduspider-image,Baiduspider-video,Baiduspider-news,Baiduspider,Baiduspider-image,Googlebot,GoogleBot,Googlebot-Mobile,Yahoo! Slurp China,Yahoo,Sogou News Spider,Sogou web spider,Sogou inst spider,Sogou spider2,Sogou blog,Sogou Orion spider,msnbot，msnbot-media,bingbot,YisouSpider,ia_archiver,EasouSpider,JikeSpider,EtaoSpider,YandexBot,BingPreview';
  if (ii_search_str($ua,$spider)) $bool = true;
  return $bool;
}

function ii_search_str($str,$val) {
  //查询是否包含
  $res = false;
  $tval = explode(",", $val);
  foreach($tval as $k=>$v) {
    if (strpos($str,$v) !==false) {
      $res = true;
      return $res;
    }
  }
  return $res;
}

function ii_getAllHeaders()
{
    $headers = array();
    $copy_server = array(
      'CONTENT_TYPE'   => 'Content-Type',
      'CONTENT_LENGTH' => 'Content-Length',
      'CONTENT_MD5'    => 'Content-Md5',
    );
    foreach ($_SERVER as $key => $value) {
      if (substr($key, 0, 5) === 'HTTP_') {
        $key = substr($key, 5);
        if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
          $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
          $headers[$key] = $value;
        }
      } elseif (isset($copy_server[$key])) {
        $headers[$copy_server[$key]] = $value;
      }
    }
    if (!isset($headers['Authorization'])) {
      if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
      } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
        $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
        $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
      } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
        $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
      }
    }
    return $headers;
}

function ii_conn_init()
{
  global $conn, $db_host, $db_username, $db_password, $db_database;
  try {
      $conn = new mysqli($db_host, $db_username, $db_password, $db_database);
      if(mysqli_connect_errno()) die("数据库连接失败，错误代码:".mysqli_connect_errno()."</br>");
  } catch (Exception $e) {
      die('MYSQL.Connect.Error!');
  }
  mysqli_query($conn,'set names utf8');
  mysqli_select_db($conn, $db_database);
}

function ii_conn_version()
{
  global $conn;
  return mysqli_get_server_info($conn);
}

function ii_conn_query($sqlstr, $conn)
{
  try {
    return mysqli_query($conn,$sqlstr);
  } catch (Exception $e) {
    echo $e->getMessage();
  }
}

function ii_conn_close($conn)
{
  return mysqli_close($conn);
}

function ii_conn_fetch_array($result)
{
  if($result -> num_rows > 0) return mysqli_fetch_array($result,MYSQLI_BOTH);//只取关联数组(MYSQL_ASSOC - 关联数组,MYSQL_NUM - 数字数组,MYSQL_BOTH - 默认。同时产生关联和数字数组)
  else return array();
}

function ii_conn_fetch_all($result)
{
  if($result -> num_rows > 0) return mysqli_fetch_all($result,MYSQLI_ASSOC);//只取关联数组(MYSQL_ASSOC - 关联数组,MYSQL_NUM - 数字数组,MYSQL_BOTH - 默认。同时产生关联和数字数组)
  else return array();
}

function ii_conn_fetch_assoc($conn)
{
  return mysqli_fetch_assoc($conn);
}

function ii_conn_insert_id($conn)
{
  return mysqli_insert_id($conn);
}

function ii_conn_affected_rows($conn)
{
  return mysqli_affected_rows($conn);//上次执行所影响的行数,用于判断执行是否成功
}

function ii_conn_free_result($result)
{
  if($result -> num_rows > 0) return mysqli_free_result($result);
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
  try
  {
    if (version_compare(PHP_VERSION, '8.0.0') >= 0) return addslashes($strers);
    if (get_magic_quotes_gpc() && function_exists('get_magic_quotes_gpc')) return $strers;
    else return addslashes($strers);
  }
  catch (Exception $e)
  {
    echo $e->getMessage();
  }
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
  global $nurlpre;
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
    if ($tfile != '.' && $tfile!='..')
    {
      $tpath = $dir . '/' . $tfile;
      if (!is_dir($tpath)) unlink($tpath);
      else ii_deldir($tpath);
    }
  }
  closedir($tdirs);
  if (rmdir($dir)) return true;
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
      eval('$tstr = $GLOBALS[\'' . $tstrers . '\'];');
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
  $date = ii_get_lrstr($date, '+', 'left');
  $date = str_replace('T',' ',$date);
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
      case 4:
        return date('Y-m-d', $tdate).'T'.date('H:i', $tdate);
        break;
      case 5:
        return date('YmdG', $tdate);
        break;
      case 6:
        return date('m-d', $tdate);
        break;
      case 7:
        return date('Y', $tdate);
        break;
      case 8:
        return date('m', $tdate);
        break;
      case 9:
        return date('d', $tdate);
        break;
      case 10:
        return date('mdis', $tdate);
        break;
      case 11:
        return date('m.d G:i', $tdate);
        break;
      case 12:
        return date('n', $tdate);
        break;
      case 20:
        return date('Gis', $tdate);
        break;
      case 21:
        return date('G:i:s', $tdate);
        break;
      case 22:
        return date('Ymd', $tdate);
        break;
      case 23:
        return date('H:i', $tdate);
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
  $ttypelist = '.7z.aac.apk.app.asp.aspx.avi.bak.bat.bin.bmp.cfm.cgi.css.csv.dat.db.dll.doc.docx.exe.flv.folder.gif.gz.html.ico.iso.jar.jpg.js.jsp.jtbc.log.lua.m4a.m4v.mdb.mid.mov.mp3.mp4.msi.ogg.otf.others.pdf.php.png.ppt.psd.rar.rss.sql.srt.svg.swf.sys.tar.tmp.ttf.txt.wav.wdja.wma.wmv.xls.xlsx.xml.zip';
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
      if (ii_isMobileAgent()) $tthings = $tthings . '/' . $GLOBALS['mobile_skin'];
      elseif (ii_isRobotsAgent()) $tthings = $tthings . '/' . $GLOBALS['robots_skin'];
      else $tthings = $tthings . '/' . $GLOBALS['default_skin'];
      if (ii_isAdmin()) $tthings = 'template/admin';
      break;
    case 'skin':
      $tthings = 'skin';
      break;
  }
  if (!(ii_isnull($tthings)))
  {
    $trthings = ii_get_safecode($_COOKIE[APP_NAME . 'config'][$tthings]);
    if (ii_isnull($trthings)) $trthings = $GLOBALS['default_' . $tthings];
    if (!ii_isnull($trthings) && ii_isMobileAgent() && $tthings == 'skin') $trthings = $GLOBALS['mobile_' . $tthings];
    if (ii_isAdmin() && $tthings == 'skin') $trthings = 'admin';
  }
  return $trthings;
}

function ii_get_client_ip()
{
  $tclient_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  if (ii_isnull($tclient_ip))
  {
    $tclient_ip = $_SERVER['HTTP_CLIENT_IP'];
    if (ii_isnull($tclient_ip)) $tclient_ip = $_SERVER['REMOTE_ADDR'];
  }
  $tclient_ip = ii_get_safecode($tclient_ip);
  if (strpos($tclient_ip, ',') !== FALSE) $tclient_ip = ii_get_lrstr($tclient_ip, ',', 'leftr');
  if(filter_var($tclient_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
      if (strpos($tclient_ip, ':') !== FALSE) $tclient_ip = '127.0.0.1';
  }
  else $tclient_ip = '127.0.0.1';
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
    if(preg_match('# AND | INSERT |SELECT |UPDATE |DELETE | and | sleep |insert |select |update |delete | SLEEP #', $tstrers)) $tstrers = '' ;
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

function ii_htmlclear($str,$type=0)
{
  switch($type) {
      case -1:
      $str = $str; 
      break;
      case 0:
      $str = strip_tags($str); 
      break;
      case 1:
      $str = strip_tags($str,'<p> <br> <img>'); 
      break;
      case 2:
      $str = strip_tags($str,'<section> <div> <span> <p> <br> <strong> <h1> <h2> <h3> <h4> <h5> <b> <img>'); 
      break;
      default:
      $str = strip_tags($str); 
      break;
  }
  return trim($str); 
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

function ii_iurl($type, $urlid, $set, $strers = '')
{
  global $ngenre, $nurlpre, $ncreatefiletype;
  if(is_numeric($urlid)) $ucode = mm_get_field($ngenre,$urlid,'ucode');
  else $ucode = '';
  $tset = ii_get_num($set);
  $tfiletype = $ncreatefiletype;
  $turlkey = ii_get_strvalue($strers, 'urlkey');
  $tcutekey = ii_get_strvalue($strers, 'cutekey');
  switch($tset)
  {
    case 0:
      switch($type)
      {
        case 'list':
          $lurl = '?type=list&classid=' . $urlid;
          if (!ii_isnull($turlkey)) $lurl .= '&offset=' . $turlkey;
          return $lurl;
          break;
        case 'detail':
          $durl = '?type=detail&id=' . $urlid;
          if (!ii_isnull($tcutekey)) $durl .= '&page=' . $tcutekey;
          if (!ii_isnull($ucode)) $durl = $ucode.'.html';
          return $durl;
          break;
        case 'tags':
          $durl = '?type=detail&id=' . $urlid;
          if (!ii_isnull($tcutekey)) $durl .= '&offset=' . $tcutekey;
          if (!ii_isnull($ucode)) $durl = $ucode.'.html';
          return $durl;
          break;
        case 'listpage':
          if (!ii_isnull($turlkey)) $lurl = '?' . ii_htmlencode(ii_replace_querystring('offset', $turlkey));
          elseif (!ii_isnull($urlid)) $lurl = '?' . ii_htmlencode(ii_replace_querystring('classid', $urlid));
          else $lurl = '?type=list&classid=' . $urlid;
          return $lurl;
          break;
        case 'searchpage':
          if (!ii_isnull($turlkey)) $surl = '?' . ii_htmlencode(ii_replace_querystring('offset', $turlkey));
          elseif (!ii_isnull($urlid)) $surl = '?' . ii_htmlencode(ii_replace_querystring('keyword', $urlid));
          else $surl = '?type=list&keyword=' . $urlid;
          return $surl;
          break;
        case 'cutepage':
          if (!ii_isnull($tcutekey)) $durl = '?' . ii_htmlencode(ii_replace_querystring('page', $tcutekey));
          elseif (!ii_isnull($urlid)) $durl = '?' . ii_htmlencode(ii_replace_querystring('id', $urlid));
          else $durl = '?type=detail&id=' . $urlid;
          if (!ii_isnull($ucode)) $durl = $ucode.'.html';
          return $durl;
          break;
        case 'tagpage':
          if (!ii_isnull($tcutekey)) $durl = '?' . ii_htmlencode(ii_replace_querystring('offset', $tcutekey));
          elseif (!ii_isnull($urlid)) $durl = '?' . ii_htmlencode(ii_replace_querystring('id', $urlid));
          else $durl = '?type=detail&id=' . $urlid;
          if (!ii_isnull($ucode)) $durl = $ucode.'.html';
          return $durl;
          break;
      }
      break;
    case 1:
      switch($type)
      {
        case 'list':
          if(ii_isnull($urlid)) $urlid = 0;
          $lurl = 'list-' . $urlid . '-0' . $tfiletype;
          if (!ii_isnull($turlkey)) $lurl = 'list-' . $urlid . '-' . $turlkey . $tfiletype;
          return $lurl;
          break;
        case 'detail':
        case 'tags':
        case 'cutepage':
        case 'tagpage':
          $durl = 'detail-' . $urlid. $tfiletype;
          if (!ii_isnull($tcutekey) && $tcutekey != 0) $durl = 'detail-' . $urlid . '-' . $tcutekey . $tfiletype;
          if (!ii_isnull($ucode)) $durl = $ucode.'.html';
          return $durl;
          break;
        case 'listpage':
          if (ii_isnull($turlkey)) return 'list-' . $urlid. '-0' . $tfiletype;
          else return 'list-' . $urlid . '-' . $turlkey . $tfiletype;
          break;
        case 'searchpage':
          if (ii_isnull($turlkey)) return 'list-' . $urlid. '-0' . $tfiletype;
          else return 'list-' . $urlid . '-' . $turlkey . $tfiletype;
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
      if (ii_isMobileAgent()) $troot = $troot . '/' . $GLOBALS['mobile_skin'];
      elseif (ii_isRobotsAgent()) $troot = $troot . '/' . $GLOBALS['robots_skin'];
      else $troot = $troot . '/' . $GLOBALS['default_skin'];
      if (!ii_isnull($nsort)) $trootdir = $nsort . '/' . $troot;
      if (!ii_isnull($ngenre)) $trootdir = $ngenre . '/' . $troot;
      if (!is_dir(ii_get_actual_route($trootdir))) $troot = 'common/template/default';//如果后台配置的模板不存在,则使用default模板.
      if (ii_isAdmin()) $troot = 'common/template/admin';
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
    $tselstr = ii_get_lrstr($strers, '|', 'right');//启用的节点值
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
    if (substr($val, 0, 2) == "%u")
    {
      $tary[$key] = iconv("UCS-2BE", CHARSET, pack("H4",substr($val, -4)));
    }
    elseif (substr($val, 0, 3) == "&#x")
    {
      $tary[$key] = iconv("UCS-2BE", CHARSET, pack("H4",substr($val, 3, -1)));
    }
    elseif (substr($val, 0, 2) == "&#")
    {
      $tary[$key] = iconv("UCS-2BE", CHARSET, pack("n",substr($val, 2, -1)));
    }
  }
  return join("", $tary);
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>