<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************

function getAccessToken() {
  $appid = ii_itake('global.support/global:wechat.appid','lng');
  $secret = ii_itake('global.support/global:wechat.secret','lng');
  $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
  $html = file_get_contents($url);
  $output = json_decode($html, true);
  $access_token = $output['access_token'];
  return $access_token;
}

function getOpenid($code) {
    $appid = ii_itake('global.support/global:wechat.appid','lng');
    $secret = ii_itake('global.support/global:wechat.secret','lng');
    $curl = curl_init();
    $url='https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($data);
    $data = get_object_vars($data);
    return $data['openid'];
}

function send_template_message($id,$openid,$formid,$name,$mobile,$idnum,$date,$info) {
 $color = '#e3e3e3';
 $templateid = ii_itake('global.support/global:wechat.templateid','lng');
 $templateurl = ii_itake('global.support/global:wechat.templateurl','lng');
 $data_arr = array(
  'keyword1' => array( "value" => $name, "color" => $color),//联系人
  'keyword2' => array( "value" => $mobile, "color" => $color),//联系方式
  'keyword3' => array( "value" => $idnum, "color" => $color),//身份证号
  'keyword4' => array( "value" => $date, "color" => $color),//报名时间
  'keyword5' => array( "value" => $info, "color" => $color)//备注
  );
  $post_data = array (
    "touser"           => $openid,
    "template_id"      => $templateid,
    "page"             => $templateurl.'?id='.$id.'&name='.$name, // 点击模板消息后跳转到的页面，可以传递参数
    "form_id"          => $formid,
    "data"             => $data_arr,
    "emphasis_keyword" => "" // 需要强调的关键字，会加大居中显示
  );
  $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".getAccessToken();
  $data = urldecode(json_encode($post_data));
  if ( empty( $url )) {
   return ;
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  if ( $data != '' && !empty( $data )) {
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
  }
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);//超时时间
  $res = curl_exec($ch);
  curl_close($ch);
  return $res;
}

function wdja_cms_wxlogin_api() {
    $sessionid = $_GET['loginid'];//
    $code = $_GET['code'];
    $appid = ii_itake('global.support/global:wechat.appid','lng');
    $secret = ii_itake('global.support/global:wechat.secret','lng');
    $curl = curl_init();
    $url='https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($data);
    $data = get_object_vars($data);
    //从这里开始判断sessionid是否为空,不为空则判断登录是否失效,判断对应的sessionid值是否正确.
    //$data['appid']=$appid;
    //生成第三方3rd_session
      $session3rd  = null;
      $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
      $max = strlen($strPol)-1;
      for($i=0;$i<16;$i++) {
          $session3rd .=$strPol[rand(0,$max)];
      }
      $session3rd = $session3rd;
//生成的字符串作为session值的ID,把用户的信息存储在此session里
    session_start();
    $_SESSION[$session3rd]=$data['openid'].$data['session_key'];
//
     //$data['sessionid']=session_id();
     //$data['code'] = "1";
  //传给小程序session的ID,判断是否已登录,只需查询session是否存在,且值是否为用户信息
    $res['loginid'] = $session3rd;//sessionid
    $res['value'] = $_SESSION[$session3rd];
    $res['openid'] = $data['openid'];
    $res = json_encode($res);
    return $res;
}

function wdja_cms_wxlogin_code_api() {
session_start();
     $sessionid = $_GET['sessionid'];
     $data['sessionid'] = $sessionid;
    if (session_id($sessionid)) {
     $data['code'] = "1";
     $data['value'] = session_id($sessionid);
    }else{
     $data['code'] = "0";
     $data['value'] = session_id($sessionid);
    }
    $data = json_encode($data);
    return $data;
}

function wdja_cms_form_api()
{
  global $conn, $variable;
  ii_conn_init();
  ii_get_variable_init();
  $mail = ii_itake('global.support/global:wechat.mail','lng');
  $mail_topic = ii_itake('global.support/global:wechat.mail_topic','lng');
  $mail_body = ii_itake('global.support/global:wechat.mail_body','lng');
  $ngenre = 'register';
  $nlng = 'chinese';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tname = $_GET['name'];
  $tmobile = $_GET['mobile'];
  $tidnum = $_GET['idnum'];
  $tdate = ii_get_date(ii_cstr($_GET['date']), 50);
  $tarea = $_GET['area'];
  $tinfo = $_GET['info'];
  $tcode = $_GET['code'];
  $tformid = $_GET['formid'];
  $topenid = getOpenid($tcode);
  $ttime = ii_now();
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfnames($nfpre,'openid') . ",
    " . ii_cfnames($nfpre,'formid') . ",
    " . ii_cfnames($nfpre,'name') . ",
    " . ii_cfnames($nfpre,'mobile') . ",
    " . ii_cfnames($nfpre,'idnum') . ",
    " . ii_cfnames($nfpre,'date') . ",
    " . ii_cfnames($nfpre,'area') . ",
    " . ii_cfnames($nfpre,'content') . ",
    " . ii_cfnames($nfpre,'hidden') . ",
    " . ii_cfnames($nfpre,'lng') . ",
    " . ii_cfnames($nfpre,'time') . "
    ) values (
    '" . ii_left($topenid, 50) . "',
    '" . ii_left($tformid, 50) . "',
    '" . ii_left($tname, 50) . "',
    '" . ii_left($tmobile, 50) . "',
    '" . ii_left($tidnum, 50) . "',
    '" . ii_get_date($tdate, 50) . "',
    " . ii_get_num($tarea,0) . ",
    '" . ii_left(ii_cstr($tinfo), 10000) . "',
    " . ii_get_num($_POST['hidden'],0) . ",
    '$nlng',
    '" . $ttime . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $tid = ii_conn_insert_id($conn);//当前报名信息ID
      $status = '1';
      $title = '留言成功';
      //send_template_message($tid,$topenid,$tformid,$tname,$tmobile,$tidnum,$tdate,$tinfo);//($id,$openid,$formid,$name,$mobile,$idnum,$date,$info)
      if (!ii_isnull($tidnum)) mm_sendemail($mail, $mail_topic, $mail_body);
    }else{
      $status = '0';
      $title = '留言失败';
    }
    $status = array();
    $res = array();
    $status["status"] = $status;
    $status["title"] = $title;
    array_push($res, $tmpstr);
    $res = json_encode($res,JSON_UNESCAPED_UNICODE);
    return $res;
}

function wdja_cms_sort_api($module)
{
  global $conn, $nlng, $variable;
  ii_conn_init();
  ii_get_variable_init();
  $ngenre = $module;
  $ndatabase = $variable['common.sort.ndatabase'];
  $nidfield = $variable['common.sort.nidfield'];
  $nfpre = $variable['common.sort.nfpre'];
  $tsqlstr = "select * from $ndatabase";
  $tsqlstr .= " where " . ii_cfnames($nfpre,'genre') . " = '".$ngenre."'";
  $tsqlstr .= " order by " . ii_cfnames($nfpre,'order') . " desc";
  $trs = ii_conn_query($tsqlstr, $conn);
  $tmpstr = array();
  $res = array();
   while ($trow = ii_conn_fetch_array($trs))
    {
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $val = str_replace('/'.$module.'/', $nurl.'/'.$module.'/', $val);
        $val = str_replace(array("　","\t","\n\r","\n","\r"), '', $val);
        $tmpstr[$tkey] = $val;
      }
        array_push($res, $tmpstr);
    }
      $res = json_encode($res,JSON_UNESCAPED_UNICODE);
      return $res;
}

function wdja_cms_search_detail_api($module,$array)
{
  global $conn, $nlng, $variable;
  ii_conn_init();
  ii_get_variable_init();
  $ngenre = $module;
  $nurl = ii_itake('global.support/global:wechat.url','lng');
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tid = ii_get_num($id);
  $tmobile = $array['mobile'];
  $tidnum = $array['idnum'];
  $tsqlstr = "select * from $ndatabase where ".ii_cfnames($nfpre,'mobile')."=$tmobile and ".ii_cfnames($nfpre,'idnum')."=$tidnum";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tmpstr = array();
  $res = array();
  if ($trs)
  {
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      if ($tkey == 'date') $val = ii_format_date($val, 1);
      $val = str_replace('/'.$module.'/', $nurl.'/'.$module.'/', $val);
      $val = str_replace(array("　","\t","\n\r","\n","\r"), '', $val);
      $tmpstr[$tkey] = $val;
    }
      array_push($res, $tmpstr);
      $res = json_encode($res,JSON_UNESCAPED_UNICODE);
      return $res;
  }
}

function wdja_cms_search_list_api($module)
{
  global $conn, $nlng, $variable;
  ii_conn_init();
  ii_get_variable_init();
  $ngenre = $module;
  $nurl = ii_itake('global.support/global:wechat.url','lng');
  $tkeywords = $_GET['keywords'];
  $tpage =  ii_get_num($_GET['page'])==0?1:ii_get_num($_GET['page']);
  $tpage_size =  ii_get_num($_GET['page_size']);
  $tclassid =  ii_get_num($_GET['classid']);
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $nclstype =$variable[ii_cvgenre($ngenre) . '.nclstype'];
  $nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
  $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
  if ($tpage_size !=0 ) $npagesize = $tpage_size;
  $toffset = ($tpage - 1)*$npagesize;
  $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre,'hidden') . "=0";
  if (!ii_isnull($tkeywords)) $tsqlstr .= " and " . ii_cfnames($nfpre,'topic') . " like '%" . $tkeywords . "%'";
  $tsqlstr .= " order by " . ii_cfnames($nfpre,'time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> listkey = $tclassid;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  $tmpstr = array();
  $res = array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $val = str_replace('/'.$module.'/', $nurl.'/'.$module.'/', $val);
        $val = str_replace(array("　","\t","\n\r","\n","\r"), '', $val);
        $tmpstr[$tkey] = $val;
      }
        array_push($res, $tmpstr);
    }
  }
      $res = json_encode($res,JSON_UNESCAPED_UNICODE);
      return $res;
}

function wdja_cms_detail_api($module,$id)
{
  global $conn, $nlng, $variable;
  ii_conn_init();
  ii_get_variable_init();
  $ngenre = $module;
  $nurl = ii_itake('global.support/global:wechat.url','lng');
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tid = ii_get_num($id);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tmpstr = array();
  $res = array();
  if ($trs)
  {
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      if ($tkey == 'date') $val = ii_format_date($val, 1);
      $val = str_replace('/'.$module.'/', $nurl.'/'.$module.'/', $val);
      $val = str_replace(array("　","\t","\n\r","\n","\r"), '', $val);
      $tmpstr[$tkey] = $val;
    }
      array_push($res, $tmpstr);
      $res = json_encode($res,JSON_UNESCAPED_UNICODE);
      return $res;
  }
}

function wdja_cms_list_api($module,$num='')
{
  global $conn, $nlng, $variable;
  ii_conn_init();
  ii_get_variable_init();
  $ngenre = $module;
  $nurl = ii_itake('global.support/global:wechat.url','lng');
  $toffset =  ii_get_num($_GET['offset']);
  $tpage =  ii_get_num($_GET['page'],'');
  $tpage_size =  ii_get_num($_GET['page_size'],'');
  $tnum =  ii_get_num($num,'');
  $tclassid =  ii_get_num($_GET['classid']);
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $nclstype =$variable[ii_cvgenre($ngenre) . '.nclstype'];
  $nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
  if (!ii_isnull($tnum)) {
    $npagesize = $tnum;
  }elseif (!ii_isnull($tpage) && !ii_isnull($tpage_size)) {
    $toffset = ($tpage - 1)*$tpage_size;
    $npagesize = $tpage_size;
  }else{
    $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
  }
  $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre,'hidden') . "=0";
  if ($tclassid != 0)
  {
      $tsqlstr .= " and " . ii_cfnames($nfpre,'cls') . " like '%|" . $tclassid . "|%'";
  }
  $tsqlstr .= " order by " . ii_cfnames($nfpre,'time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> listkey = $tclassid;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  $tmpstr = array();
  $res = array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $val = str_replace('/'.$module.'/', $nurl.'/'.$module.'/', $val);
        $val = str_replace(array("　","\t","\n\r","\n","\r"), '', $val);
        $tmpstr[$tkey] = $val;
      }
      array_push($res, $tmpstr);
    }
  }
      $res = json_encode($res,JSON_UNESCAPED_UNICODE);
      return $res;
}

function wdja_cms_page_api($module)
{
   global $conn, $nlng, $variable;
  ii_conn_init();
  ii_get_variable_init();
  $ngenre = $module;
  $nurl = ii_itake('global.support/global:wechat.url','lng');
  $toffset = '0';
  $tid =  ii_get_num($_GET['id'],'');
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];;
  $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
  $tsqlstr = "select * from $ndatabase where ".ii_cfnames($nfpre,'hidden')." = '0' ";
  if (!ii_isnull($tid)) $tsqlstr .= " and " . $nidfield . " = ".$tid;
  $tsqlstr .= " order by " . ii_cfnames($nfpre,'time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  $tmpstr = array();
  $res = array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $val = str_replace('/'.$module.'/', $nurl.'/'.$module.'/', $val);
        $val = str_replace(array("　","\t","\n\r","\n","\r"), '', $val);
        $tmpstr[$tkey] = $val;
      }
      array_push($res, $tmpstr);
    }
  }
      $res = json_encode($res,JSON_UNESCAPED_UNICODE);
      return $res;
}

function wdja_cms_singlepage_api($module)
{
  $ngenre = $module;
  $nurl = ii_itake('global.support/global:wechat.url','lng');
  $trootstr = ii_get_actual_route($ngenre).'/common/language/module.wdja';
  if (file_exists($trootstr))
  {
    $tdoc = new DOMDocument();
    $tdoc -> load($trootstr);
    $txpath = new DOMXPath($tdoc);
    $tquery = '//xml/configure/node';
    $tnode = $txpath -> query($tquery) -> item(0) -> nodeValue;
    $tquery = '//xml/configure/field';
    $tfield = $txpath -> query($tquery) -> item(0) -> nodeValue;
    $tquery = '//xml/configure/base';
    $tbase = $txpath -> query($tquery) -> item(0) -> nodeValue;
    $tfieldary = explode(',', $tfield);
    $tlength = count($tfieldary) - 1;
    $tquery = '//xml/' . $tbase . '/' . $tnode;
    $trests = $txpath -> query($tquery);
    $tmpstr = array();
    $res = array();
    foreach ($trests as $trest)
    {
      $tnodelength = $trest -> childNodes -> length;
      for ($i = 0; $i <= $tlength; $i += 1)
      {
        $ti = $i * 2 + 1;
        if ($ti < $tnodelength)
        {
          $nodeValue = $trest -> childNodes -> item($ti) -> nodeValue;
        }
        if ($i < $tlength) $k = ii_htmlencode($nodeValue);
        if ($i == $tlength) {
          if (ii_isnull($GLOBALS['RS_' . $k])) $GLOBALS['RS_' . $k] = $nodeValue;
          $nodeValue = str_replace('/'.$module.'/', $nurl.'/'.$module.'/', $nodeValue);
          $nodeValue = str_replace(array("　","\t","\n\r","\n","\r"), '', $nodeValue);
          $tmpstr[$k] = $nodeValue;
        }
      }
    }
      array_push($res, $tmpstr);
      $res = json_encode($res,JSON_UNESCAPED_UNICODE);
      return $res;
  }
}

//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>