<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
class cc_cache
{
  public $filename;
  public $cachename;

  function get_file_text()
  {
    return file_get_contents($this -> filename);
  }

  function put_file_text($data)
  {
    return file_put_contents($this -> filename, $data);
  }

  function get_file_array()
  {
    return include_once($this -> filename);
  }

  function set_file_array($data)
  {
    if (!is_array($data))
    {
      return false;
    }
    else
    {
      $tarraytext = 'array(';
      foreach($data as $key => $val)
      {
        if (is_array($val))
        {
          $tarraytext = $tarraytext . '\'' . $key . '\' => ' . $this -> set_file_array($val) . ',';
        }
        else
        {
          $tarraytext = $tarraytext . '\'' . $key . '\' => \'' . $val . '\',';
        }
      }
      $tarraytext = $tarraytext . ')';
      return $tarraytext;
    }
  }

  function put_file_array($data)
  {
    $ttext = '<?php' . chr(13) . chr(10);
    $ttext = $ttext . '$GLOBALS[\'' . $this -> cachename . '\'] = ';
    $ttext = $ttext . $this -> set_file_array($data) . ';' . chr(13) . chr(10);
    $ttext = $ttext . '?>';
    return file_put_contents($this -> filename, $ttext);
  }
}

class cc_cutepage
{
  public $id;
  public $sqlstr;
  public $offset;
  public $pagesize;
  public $rslimit;
  public $listkey;

  function init()
  {
    $trscount = $this -> get_rs_count();
    if (!isset($this -> rslimit)) $this -> rslimit = $trscount;
    else
    {
      if ($trscount < ($this -> rslimit)) $this -> rslimit = $trscount;
    }
  }

  function get_rs_count()
  {
    global $conn;
    $tsqlstr = 'select count(' . $this -> id . ') from (' . $this -> sqlstr .') as sum';
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    return $trs[0];
  }

  function get_rs_array()
  {
    global $conn;
    $toffset = $this -> offset;
    $tpagesize = $this -> pagesize;
    $trslimit = $this -> rslimit;
    if (!($toffset > $trslimit))
    {
      if (($toffset + $tpagesize) > $trslimit) $tpagesize = $trslimit - $toffset;
      $tsqlstr = $this -> sqlstr . ' limit ' . $toffset . ',' . $tpagesize;
      $trs = ii_conn_query($tsqlstr, $conn);
      $ti = 0;
      while ($trow = ii_conn_fetch_array($trs))
      {
        $tarray[$ti] = $trow;
        $ti += 1;
      }
      return $tarray;
    }
  }

  function get_pagestr($type = 'list') 
  {
    global $nurltype, $ncreatefolder, $ncreatefiletype;
    $toffset = $this -> offset;
    $tpagesize = $this -> pagesize;
    $trslimit = $this -> rslimit;
    $tlistkey = $this -> listkey;
    $tpagenums = ceil($trslimit / $tpagesize);
    $tnpagenum = ceil($toffset / $tpagesize) + 1;
    if ($tnpagenum > $tpagenums) $tnpagenum = $tpagenums;
    $txpagenum = $tnpagenum + 1;
    if ($txpagenum > $tpagenums) $txpagenum = $tpagenums;
    $tstate1 = ($toffset > 0) ? 1 : 0;
    $tstate2 = (($toffset + $tpagesize) < $trslimit) ? 1 : 0;
    $tmpstr = ii_itake('global.tpl_common.cutepage', 'tpl');
    $tpl_firstpage = ii_ctemplate($tmpstr, '{@firstpage}');
    $tary = explode('{|}', $tpl_firstpage);
    if ($tstate1)
    {
      $tstr = $tary[1];
      if ($type == 'search') $tstr = str_replace('{$URLfirst}', ii_iurl('searchpage', 0, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tstr);
      else $tstr = str_replace('{$URLfirst}', ii_iurl('listpage', 0, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tstr);
    }
    else $tstr = $tary[0];
    $tmpstr = str_replace(WDJA_CINFO, $tstr, $tmpstr);
    $tpl_prepage = ii_ctemplate($tmpstr, '{@prepage}');
    $tary = explode('{|}', $tpl_prepage);
    if ($tstate1)
    {
      $tstr = $tary[1];
      if ($type == 'search') $tstr = str_replace('{$URLpre}', ii_iurl('searchpage', $toffset - $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tstr);
      else $tstr = str_replace('{$URLpre}', ii_iurl('listpage', $toffset - $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tstr);
    }
    else $tstr = $tary[0];
    $tmpstr = str_replace(WDJA_CINFO, $tstr, $tmpstr);
    $tpl_nextpage = ii_ctemplate($tmpstr, '{@nextpage}');
    $tary = explode('{|}', $tpl_nextpage);
    if ($tstate2)
    {
      $tstr = $tary[1];
      if ($type == 'search') $tstr = str_replace('{$URLnext}', ii_iurl('searchpage', $toffset + $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tstr);
      else $tstr = str_replace('{$URLnext}', ii_iurl('listpage', $toffset + $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tstr);
    }
    else $tstr = $tary[0];
    $tmpstr = str_replace(WDJA_CINFO, $tstr, $tmpstr);
    $tpl_lastpage = ii_ctemplate($tmpstr, '{@lastpage}');
    $tary = explode('{|}', $tpl_lastpage);
    if ($tstate2)
    {
      $tlastoffset = $trslimit - (($trslimit - $toffset) % $tpagesize);
      if ($tlastoffset == $trslimit) $tlastoffset = $trslimit - $tpagesize;
      $tstr = $tary[1];
      if ($type == 'search') $tstr = str_replace('{$URLlast}', ii_iurl('searchpage', $tlastoffset, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tstr);
      else $tstr = str_replace('{$URLlast}', ii_iurl('listpage', $tlastoffset, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tstr);
    }
    else $tstr = $tary[0];
    $tmpstr = str_replace(WDJA_CINFO, $tstr, $tmpstr);
    $tmpstr = str_replace('{$npagenum}', $tnpagenum, $tmpstr);
    $tmpstr = str_replace('{$pagenums}', $tpagenums, $tmpstr);
    $tmpstr = str_replace('{$xpagenum}', $txpagenum, $tmpstr);
    $tmpstr = str_replace('{$pagesize}', $tpagesize, $tmpstr);
    if ($type == 'search') $tmpstr = str_replace('{$goURL}', ii_iurl('searchpage', '\' + cc_cutepage(get_id(\'go-page-num\').value) + \'', $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tmpstr);
    else $tmpstr = str_replace('{$goURL}', ii_iurl('listpage', '\' + cc_cutepage(get_id(\'go-page-num\').value) + \'', $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  function get_pagenum($type = 'list') 
  {
    global $nurltype, $ncreatefolder, $ncreatefiletype;
    $maxlength = 10;
    $toffset = $this -> offset;
    $tpagesize = $this -> pagesize;
    $trslimit = $this -> rslimit;
    $tlistkey = $this -> listkey;
    $tpagenums = ceil($trslimit / $tpagesize);
    $tnpagenum = ceil($toffset / $tpagesize) + 1;
    if ($tnpagenum > $tpagenums) $tnpagenum = $tpagenums;
    $txpagenum = $tnpagenum + 1;
    if ($txpagenum > $tpagenums) $txpagenum = $tpagenums;
    $tstate1 = ($toffset > 0) ? 1 : 0;
    $tstate2 = (($toffset + $tpagesize) < $trslimit) ? 1 : 0;
    $tmpstr = '';
    if ($tpagenums >= 1)
    {
      $tmpstr = ii_itake('global.tpl_common.pagenum', 'tpl');
      $tmpastr = ii_ctemplate($tmpstr, '{@}');
      $tmprstr = '';
      $tstr = $tary[1];
      for($ti = 0;$ti < $tpagenums; $ti++)
      {
        $tmptstr = $tmpastr;
        if ($type == 'search') $tmptstr = str_replace('{$pageurl}', ii_iurl('searchpage', $ti*$tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tmptstr);
        else if ($type == 'detail') $tmptstr = str_replace('{$pageurl}', ii_iurl('detailpage', $ti*$tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tmptstr);
        else $tmptstr = str_replace('{$pageurl}', ii_iurl('listpage', $ti*$tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey), $tmptstr);
        $tmptstr = str_replace('{$pagenum}', $ti + 1, $tmptstr);
        $tmptstr = $ti + 1 == $tnpagenum ?  str_replace('{$current}', ' class="current"', $tmptstr) : str_replace('{$current}', '', $tmptstr);
        if (($ti > $tpagenums - $maxlength - 1 || $ti > $tnpagenum - 6) && ($ti < $tnpagenum + $maxlength - 5 || $ti < $maxlength)) $tmprstr .= $tmptstr;
      }
      if ($tstate1)
      {
        if ($type == 'search') $tmpstr = str_replace('{$pre}', '<a class="np-page" href="' . ii_iurl('searchpage', $toffset - $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey) . '">' . ii_itake('global.lng_cutepage.prepage', 'lng') . '</a>', $tmpstr);
        else if ($type == 'detail') $tmpstr = str_replace('{$pre}', '<a class="np-page" href="' . ii_iurl('detailpage', $toffset - $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey) . '">' . ii_itake('global.lng_cutepage.prepage', 'lng') . '</a>', $tmpstr);
        else $tmpstr = str_replace('{$pre}', '<a class="np-page" href="' . ii_iurl('listpage', $toffset - $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey) . '">' . ii_itake('global.lng_cutepage.prepage', 'lng') . '</a>', $tmpstr);
      }
      else $tmpstr = str_replace('{$pre}', '', $tmpstr);
      if ($tstate2)
      {
        if ($type == 'search') $tmpstr = str_replace('{$next}', '<a class="np-page" href="' . ii_iurl('searchpage', $toffset + $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey) . '">' . ii_itake('global.lng_cutepage.nextpage', 'lng') . '</a>', $tmpstr);
        else $tmpstr = str_replace('{$next}', '<a class="np-page" href="' . ii_iurl('listpage', $toffset + $tpagesize, $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype . ';listkey=' . $tlistkey) . '">' . ii_itake('global.lng_cutepage.nextpage', 'lng') . '</a>', $tmpstr);
      }
      else $tmpstr = str_replace('{$next}', '', $tmpstr);
      $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
      $tmpstr = str_replace('{$npagenum}', $tnpagenum, $tmpstr);
      $tmpstr = str_replace('{$pagenums}', $tpagenums, $tmpstr);
      $tmpstr = str_replace('{$xpagenum}', $txpagenum, $tmpstr);
      $tmpstr = str_replace('{$pagesize}', $tpagesize, $tmpstr);
      $tmpstr = ii_creplace($tmpstr);
    }
    return $tmpstr;
  }

}

require('PHPMailer/Exception.php');
require('PHPMailer/PHPMailer.php');
require('PHPMailer/SMTP.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class cc_socketmail
{
  public $server;
  public $port;
  public $username;
  public $password;
  public $from;
  public $to;
  public $subject;
  public $message;

  function send_mail()
  {
    $tserver = $this -> server;
    $tport = $this -> port;
    $tcharset = $this -> charset;
    $tusername = $this -> username;
    $tpassword = $this -> password;
    $tfrom = $this -> from;
    $tto = $this -> to;
    $tsubject = $this -> subject;
    $tmessage = $this -> message;

    if (empty($tsubject) || empty($tmessage)) {
      return ['result' => false, 'msg' => '????????????'];
    }
    $fromAddress = $tfrom;
    $pwd =  $tpassword;
    $toAddress = $tto;

    $mail = new PHPMailer();
    //??????PHPMailer??????SMTP
    $mail->isSMTP();
    //??????SMTP??????
    // 0 =???????????????????????????
    // 1 =???????????????
    // 2 =???????????????????????????
    $mail->SMTPDebug = 0 ;
    //?????????????????????????????????
    $mail->Host = $tserver;
    //??????
    // $ mail-> Host = gethostbyname???'smtp.gmail.com'???;
    //???????????????????????????SMTP over IPv6
    //??????SMTP????????? -  587???????????????????????????TLS??????RFC4409 SMTP??????
    $mail->Port = $tport;
    //???????????????????????? -  ssl????????????????????????tls
    $mail->SMTPSecure = 'ssl';
    //????????????SMTP????????????
    $mail->SMTPAuth = true ;
    //??????SMTP???????????????????????? - ??????gmail???????????????????????????
    $mail->Username = $fromAddress;
    //??????SMTP?????????????????????(?????????????????????????????????)
    $mail->Password = $pwd;
    //?????????????????????????????? ??????GB2312 ?????????utf-8 ??????utf8????????????????????????????????????
    $mail->CharSet = $tcharset;
    //????????????????????????????????????
    $mail->setFrom($fromAddress,$tusername);
    //????????????????????????
    //$mail->addReplyTo('***@qq.com','??????');
    //??????????????????????????????
    $mail->addAddress($toAddress,$toAddress);
    //???????????????
    $mail->Subject = $tsubject;
    //????????????????????????HTML?????????????????????????????????????????????????????????
    //???HTML???????????????????????????????????????
    //$mail->msgHTML(file_get_contents(' contents.html '),__DIR__);
    //???????????????????????????????????????
    $mail->AltBody  = 'This is the body in plain text for non-HTML mail clients';
    $mail->Body  = $tmessage;
    $result = $mail->send();
  }
}

ini_set('user_agent','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');

require('HtmlDom.php');

function collects($url) {
  $turl = parse_url($url,PHP_URL_HOST);
  $tip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  if (empty($tip))
  {
    $tip = $_SERVER['HTTP_CLIENT_IP'];
    if (empty($tip)) $tip = $_SERVER['REMOTE_ADDR'];
  }
  $collect = array();
  $collect = api_collect_array();
  $opts = array(
  'http' => array(
     'timeout' => 5,
     'method' => 'GET',
     'protocol_version'=>'1.1',
     'header' =>
            "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1\r\n" .
            "X-Forwarded-For:".$tip."\r\n" .
            "Client-IP: ".$tip."\r\n" .
            "Referer:http://".$turl."\r\n".
            "Host: ".$turl."\r\n" .
            "Accept-Language: zh-cn,zh;q=0.5\r\n" .
            "Accept-Encoding: gzip, deflate\r\n" .
            "Accept-Charset: GB2312,UTF-8;q=0.7,*;q=0.7\r\n" .
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" .
            "Connection: keep-alive\r\n\r\n"
    )
  );
  $context = stream_context_create($opts);
  $dom = file_get_html($url,false,$content);
  if (!$dom) {
    return false;
  }
  $dom = wdja_iconv($dom);
  if(!empty($collect)){
    $burl = parse_url($url,PHP_URL_HOST);
    foreach($collect as $rule){
      if($rule['c_url'] == $burl){
        foreach($rule as $k => $v){
          $key = ii_get_lrstr($k, '_', 'rightr');
          $$key = $rule[$k];
        }
      }
    }
    if(!ii_isnull($image)){
      $timage = $dom->find($image, 0)->content;
      $timage = wdja_iconv(trim(saveimage($timage)));
      $data['image'] = ii_htmlclear($timage);
    }
    else $data['image'] = '';
    if(!ii_isnull($title)){
      $ttitle = $dom->find($title, 0)->innertext;
      $ttitle = wdja_iconv(trim($ttitle));
      $data['title'] = ii_htmlclear($ttitle);
    }
    else $data['title'] = '';
    if(!ii_isnull($author)){
      $tauthor = $dom->find($author, 0)->innertext;
      $tauthor = wdja_iconv(trim($tauthor));
      $data['author'] = ii_htmlclear($tauthor);
    }
    else $data['author'] = '';
    if(!ii_isnull($content)){
      $tcontent = $dom->find($content, 0)->innertext;
      $tcontent = wdja_iconv(trim(saveimages($tcontent)));
      if(!ii_isnull($replace)){
        $replaces=explode("\r\n", trim($replace));
        foreach($replaces as $k=>$v) {
          if(!ii_isnull($v)){
            $old = ii_get_lrstr($v, '|', 'left');
            $new = ii_get_lrstr($v, '|', 'right');
            $tcontent = str_replace($old, $new, $tcontent);
          }
        }
      }
      $data['content'] = ii_htmlclear($tcontent,'-1');
    }
    else $data['content'] = '';
  }else{
    $data['image'] = '';
    $data['title'] = '';
    $data['author'] = '';
    $data['content'] = '';
  }
  return $data;
}

function wdja_iconv($str){
    $s1 = iconv('gbk','utf-8//IGNORE',$str);
    $s0 = iconv('utf-8','gbk//IGNORE',$s1);
    if($s0 == $str){
        return $s1;
    }else{
        return $str;
    }
}

//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>