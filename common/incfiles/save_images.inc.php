<?php
/**
百度cdn图片,需判断来源是否包含cdn,包含则自动设置Referer
微信公众号图片,限制只替换一次.因img标签含有data-src参数
百度百家号图片,需替换URL中的&amp;为&
部分cdn和oss存储图片采集需指定来源地址
     if (strstr($ref, 'csdn'))  $ref = 'https://blog.csdn.net/';
     if (strstr($ref, 'bcebos'))  $ref = 'https://www.baidu.com/';
     if (strstr($ref, 'alicdn'))  $ref = 'https://www.aliyun.com/';
     if (strstr($ref, 'aliyuncs'))  $ref = 'https://www.aliyun.com/';

**/
function pget($url,$head=false) {
  $curl = curl_init(); // 启动一个CURL会话
  //以下三行代码解决https图片访问受限问题
  $dir = pathinfo($url);//以数组的形式返回图片路径的信息
  $host = $dir['dirname'];//图片路径
  $ref = $host.'/';
  if (strstr($ref, 'csdn'))  $ref = 'https://blog.csdn.net/';
  if (strstr($ref, 'bcebos'))  $ref = 'https://www.baidu.com/';
  if (strstr($ref, 'alicdn'))  $ref = 'https://www.aliyun.com/';
  if (strstr($ref, 'aliyuncs'))  $ref = 'https://www.aliyun.com/';
  if (strstr($ref, 'qpic'))  $ref = 'http://mmbiz.qpic.cn/';
  if (strstr($ref, 'ithome'))  $ref = 'https://www.ithome.com/';
  curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址    
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
  curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
  if ($ref) {
    curl_setopt($curl, CURLOPT_REFERER, $ref);//带来的Referer
  }else{
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
  }
  curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
  curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
  curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
  $tmpInfo = curl_exec($curl); // 执行操作
  if (curl_errno($curl)) {
    $tmpInfo = '';
  }
  if ($head) { $data['head']=curl_getinfo($curl);}
  curl_close($curl); // 关闭CURL会话
  $data['data']=$tmpInfo;
  return $data; // 返回数据
}

//单图片本地化
function saveimage($url) {
  global $upbasefolder,$nuppath,$ngenre;
  $iport = $_SERVER["SERVER_PORT"];
  if ($iport == '443') $basehost = "https://".$_SERVER["HTTP_HOST"];
  else  $basehost = "http://".$_SERVER["HTTP_HOST"];
  if (ii_isnull($nuppath)) $imgPath = 'upload/'.ii_format_date(ii_now(), 2);
  else $imgPath = $nuppath . ii_format_date(ii_now(), 2);
  if (!is_dir($imgPath.'/'))
  {
    mkdir($imgPath, 0777,true);
    chmod($imgPath, 0777);
  }
    $http=pget($url,true);
    $itype=($http['head']['content_type']);
    $icode =($http['head']['http_code']);//图片状态码
    if ($icode != '200' || ii_isnull($http['data'])) { return; }
    if (!preg_match("#\.(jpg|gif|png)#i", $itype))
    {
      if ($itype=='image/gif')
      {
        $itype = ".gif";
      }
      elseif ($itype=='image/png')
      {
        $itype = ".png";
      }
      elseif ($itype=='image/jpeg')
      {
        $itype = ".jpg";
      }
      else
      {
        $itype = '.jpg';
      }
    }
    $runds=md5(time()).$key;
    $rndFileName=$imgPath."/".$runds.$itype;
    if(OSS_SWITCH == 1){
        $sqlurl = mm_oss_upload_data($http['data'],$itype);
    }else{
        $tp = fopen($rndFileName, 'w');
        fwrite($tp, $http['data']);//图片二进制数据写入图片文件
        fclose($tp);
        if (file_exists($rndFileName))
        {
          if (ii_isnull($ngenre)) $sqlurl = '/'.$rndFileName;
          else $sqlurl = '/'.$ngenre.'/'.$rndFileName;
        }
    }
  return $sqlurl;
}

//内容中图片本地化
function saveimages($content) {
  global $upbasefolder,$nuppath,$ngenre;
  $iport = $_SERVER["SERVER_PORT"];
  if ($iport == '443') $basehost = "https://".$_SERVER["HTTP_HOST"];
  else  $basehost = "http://".$_SERVER["HTTP_HOST"];
  $img_array = array();
  //$content = str_replace('&amp;', '&', $content);
  //$content = stripslashes($content);
  preg_match_all('/url\([\'|"]?(.*?)[\'|"]?\)/i',$content,$img_array1);
  preg_match_all('/<img.*?src[\s]*=[\s]*[\'|"](.*?)[\'|"]/i',$content,$img_array2);
  $img_array = array_merge($img_array1[1], $img_array2[1]);
  $img_array = array_unique($img_array);
  if (ii_isnull($nuppath)) $imgPath = 'upload/'.ii_format_date(ii_now(), 2);
  else $imgPath = $nuppath . ii_format_date(ii_now(), 2);
  if (!is_dir($imgPath.'/'))
  {
    mkdir($imgPath, 0777,true);
    chmod($imgPath, 0777);
  }
  foreach($img_array as $key=>$value) {
    if (preg_match("#".$basehost."#i", $value)) 
    {
      continue; 
    }
    if (!preg_match("#^(http|https):\/\/#i", $value))
    {
      continue; 
    }
    $http=pget($value,true);
    $itype=($http['head']['content_type']);
    $icode =($http['head']['http_code']);//图片状态码
    if ($icode != '200') { continue; }
    if (!preg_match("#\.(jpg|gif|png)#i", $itype))
    {
      if ($itype=='image/gif')
      {
        $itype = ".gif";
      }
      elseif ($itype=='image/png')
      {
        $itype = ".png";
      }
      elseif ($itype=='image/jpeg')
      {
        $itype = ".jpg";
      }
      else
      {
        $itype = '.jpg';
      }
    }
    $runds=md5(time()).$key;
    $rndFileName=$imgPath."/".$runds.$itype;
    if(OSS_SWITCH == 1){
        $sqlurl = mm_oss_upload_data($http['data'],$itype);
       $content = str_replace_limit($value, $sqlurl, $content, -1);
    }else{
        $tp = fopen($rndFileName, 'w');
        fwrite($tp, $http['data']);//图片二进制数据写入图片文件
        fclose($tp);
        if (file_exists($rndFileName))
        {
          if (ii_isnull($ngenre)) $sqlurl = '/'.$rndFileName;
          else $sqlurl = '/'.$ngenre.'/'.$rndFileName;
          $content = str_replace_limit($value, $sqlurl, $content, -1);
        }
    }
  }
  return $content;
}

function str_replace_limit($search, $replace, $subject, $limit=-1) {
  //替换次数限制
  if (is_array($search)) {
    foreach ($search as $k=>$v) {
      $search[$k] = '`' . preg_quote($search[$k],'`') . '`';
    }
  }
  else {
    $search = '`' . preg_quote($search,'`') . '`';
  }
  // replacement
  return preg_replace($search, $replace, $subject, $limit);
}
?>