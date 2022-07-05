<?php
function get_weburl(){
  $nuri = $_SERVER['SCRIPT_NAME'];
  $nurs = $_SERVER['QUERY_STRING'];
  $nport = $_SERVER['SERVER_PORT'];
  $nurl = $nuri;
  $burl = parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST);
  $bport = parse_url($_SERVER['HTTP_HOST'],PHP_URL_PORT);
  $sub = str_ireplace('/','',ii_get_lrstr($nuri,'install','left'));
  if (ii_isnull($burl)) $burl = $_SERVER['HTTP_HOST'];
  if ($nport != $bport) $nport = $bport;
  if (!(ii_isnull($nurs))) $nurl = $nuri . '?' . $nurs;
  if ($nport == '443') {
    $nurlpre = 'https://' . $burl;
  }elseif ($nport == '80') {
    $nurlpre = 'http://' . $burl;
  }else{
    if (!(ii_isnull($nport))) $nurlpre = 'http://' . $burl.':'.$nport;
    else  $nurlpre = 'http://' . $burl;
  }
  if(!ii_isnull($sub)) $nurlpre .= '/'.$sub. '/';
  return $nurlpre;
}

function install_success($url)
{
    $indexPath = '../index.php';
    $indexContent = file_get_contents($indexPath);
    $indexContent = str_replace('<?php @header("location:install/index.php");?>', '', $indexContent);
    $fileBool1 = file_put_contents($indexPath, $indexContent);
    if($fp = @fopen("../complete.php", 'w')) {
    @fclose($fp);
    $completeContent = '<?php' . chr(10);
    $completeContent .= 'require(\'common/incfiles/autoload.php\');' . chr(10);
    $completeContent .= 'ii_deldir(\'install\');' . chr(10);
    $completeContent .= 'unlink(\'complete.php\');' . chr(10);
    $completeContent .= 'header(\'location: ' . $url . '\');' . chr(10);
    $completeContent .= '?>' . chr(10);
    }
    $fileBool2 = file_put_contents("../complete.php", $completeContent);
    if ($fileBool1 && $fileBool2)
    {
        @sleep(3);
        @header("location:../complete.php");
    }
}

function install_database($dbconn)
    {
    ini_set('max_execution_time','300');//防止超时,如果数据太大,建议直接在数据库中还原.
    $filename = '../install/mysql.sql';
    //$sqllogs = '../sql_logs.txt';
    if (!file_exists($filename)) return;
    $sqls = file_get_contents($filename);
    $sqls = explode(";", $sqls);
    if (is_array($sqls))
    {
        //$sql_str = '';
        foreach($sqls as $sql)
        {
            //$sql_str .= $sql.';';
            if (!ii_isnull($sql) && strlen($sql)>3 && (substr(ltrim($sql),0,2)!='/*' || substr(ltrim($sql),0,3)!='-- ')) {
                $trs = ii_conn_query($sql.';', $dbconn);
                ii_conn_free_result($trs);
                unset($trs);
            }
        }
        //if($fp = @fopen($sqllogs, 'w')) file_put_contents($sqllogs, $sql_str);
        //fclose($fp);
        unset($sqls);
    }
}

function dir_writeable($dir) {
    $writeable = 0;
    if(!is_dir($dir)) {
        @mkdir($dir, 0755);
    }else {
        @chmod($dir,0755);
    }
    if(is_dir($dir)) {
        if($fp = @fopen("$dir/test.txt", 'w')) {
            @fclose($fp);
            @unlink("$dir/test.txt");
            $writeable = '可写';//1;
        } else {
            $writeable = '不可写';//0;
        }
    }
    return $writeable;
}

function update_config($config)
{
    global $installpath;
    $bool=false;
    $info=file_get_contents($installpath.'common/incfiles/const.inc.php');
    foreach($config as $key => $val) {
        $old_inf = "/define\('".$key."','(.*?)'\);/";
        $new_inf = "define('".$key."','".$val."');";
        if(defined($key)) $info = preg_replace($old_inf,$new_inf,$info);
    }
    $res = file_put_contents($installpath.'common/incfiles/const.inc.php',$info);
    if ($res) $bool=true;
    return $bool;
}

function edit_global_config($name,$value)
{
    global $installpath;
      $keyword = 'disinfo,chinese';
      $sourceFile = $installpath.'support/global/common/language/basic.wdja';
      if (is_file($sourceFile))
      {
        $doc = new DOMDocument();
        $doc -> load($sourceFile);
        $xpath = new DOMXPath($doc);
        $query = '//xml/configure/node';
        $node = $xpath -> query($query) -> item(0) -> nodeValue;
        $query = '//xml/configure/field';
        $field = $xpath -> query($query) -> item(0) -> nodeValue;
        $query = '//xml/configure/base';
        $base = $xpath -> query($query) -> item(0) -> nodeValue;
        $fieldArys = explode(',', $field);
        $fieldLength = count($fieldArys);
        if ($fieldLength >= 2)
        {
          if (!in_array($keyword, $fieldArys)) $keyword = $fieldArys[1];
          $query = '//xml/' . $base . '/' . $node;
          $rests = $xpath -> query($query);
          foreach ($rests as $rest)
          {
            $nodeDom = $rest -> getElementsByTagName($keyword);
            if ($nodeDom -> length == 0) $nodeDom = $rest -> getElementsByTagName($fieldArys[1]);
            if ($rest -> getElementsByTagName(current($fieldArys)) -> item(0) -> nodeValue == $name)
            {
              $nodeDom -> item(0) -> nodeValue = '';
              $nodeDom -> item(0) -> appendChild($doc -> createCDATASection($value));
            }
          }
        }
        $docSave = $doc -> save($sourceFile);
  }
}
?>