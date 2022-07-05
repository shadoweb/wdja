<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();

function pp_get_xml_root()
{
  global $ngenre;
  $tmpstr = ii_get_actual_route($ngenre);
  if (ii_right($tmpstr, 1) != '/') $tmpstr .= '/';
  $tmproot = 'common/language/';
  $tmpstr = $tmpstr . $tmproot;
  return $tmpstr;
}

function pp_get_home()
{
  global $conn,$variable,$nurlpre;
  $array = array();
  $arrays = array();
  $tfield = 'url,type,folder';
  $turl = $nurlpre;
  $tmpstr = '';
  $tfieldary = explode(',', $tfield);
  $php_url = $turl . '/index.php';
  $html_url = $turl . '/index.html';
  $array[$tfieldary[0]]['php']= $php_url;
  $array[$tfieldary[0]]['html']= $html_url;
  $array[$tfieldary[1]]= 'home';
  $array[$tfieldary[2]]= '/';
  array_push($arrays,$array);
  $array=array();
  //复制全局静态文件
  $web_folder = ii_itake('global.expansion/htmlplus:config.web_folder','lng');
    if(!ii_isnull($web_folder))
    {
      $tfolder = 'theme/'.DEFAULT_SKIN.',js,iconfont,public/label';
      $tfolderary = explode(',', $tfolder);
      foreach($tfolderary as $folder){
        $from_folder = ii_get_actual_route('common/images/'.$folder.'/');
        $to_folder = ii_get_actual_route($web_folder).'/common/images/'.$folder.'/';
        $fu = new FileUtil();
        $fu->copyDir($from_folder, $to_folder, true);
      }
      $from_upfolder = ii_get_actual_route('upload/');
      $to_upfolder = ii_get_actual_route($web_folder).'/upload/';
      $upfu = new FileUtil();
      $upfu->copyDir($from_upfolder, $to_upfolder, true);
    }
  //复制全局静态文件
  //复制网站地图文件
    if(!ii_isnull($web_folder))
    {
      $tfile = 'sitemap.xml,sitemap.html';
      $tfileary = explode(',', $tfile);
      foreach($tfileary as $file){
        $from_file = ii_get_actual_route($file);
        $to_file = ii_get_actual_route($web_folder).'/'.$file;
        $fu = new FileUtil();
        $fu->copyFile($from_file, $to_file, true);
      }
    }
  //复制网站地图文件
  //复制公共配置静态文件
  $tfolder = 'support/global,support/menu,support/slide,support/sort';
  $tfolderary = explode(',', $tfolder);
  foreach($tfolderary as $folder){
    pp_copy_folder($folder);
  }
  //复制公共配置静态文件
  pp_create_html($arrays);
}

function pp_get_singlepage()
{
  global $conn,$variable,$nurlpre;
  $array = array();
  $arrays = array();
  $tfield = 'url,type,folder';
  $tgenre = ii_itake('config.singlepage','lng');
  if (ii_isnull($tgenre)) return $arrays;
  $turl = $nurlpre;
  $tmpstr = '';
  $tfieldary = explode(',', $tfield);
  $tgenreary = explode(',', $tgenre);
  foreach($tgenreary as $key => &$val)
  {
    $ngenre = $val;
    $array[$tfieldary[0]]['php']= $turl . '/' . $ngenre.'/index.php';
    $array[$tfieldary[0]]['html']= $turl . '/' . $ngenre.'/index.html';
    $array[$tfieldary[1]]= 'singlepage';
    $array[$tfieldary[2]]= $ngenre;
    array_push($arrays,$array);
    $array=array();
    $tgenreary[$key]=null;
    //复制单页静态文件
    pp_copy_folder($ngenre);
  }
  pp_create_html($arrays);
}

function pp_get_list()
{
  global $nurlpre;
  $array = array();
  $arrays = array();
  $tfield = 'url,type,folder';
  $tgenre = ii_itake('config.module','lng');
  if (ii_isnull($tgenre)) return $arrays;
  $turl = $nurlpre;
  $tmpstr = '';
  $tfieldary = explode(',', $tfield);
  $tgenreary = explode(',', $tgenre);
  foreach($tgenreary as $key => &$val)
  {
    ii_conn_init();
    global $conn, $variable, $slng;
    $ngenre = $val;
    $array[$tfieldary[0]]['php']= $turl . '/' . $ngenre .'/index.php';
    $array[$tfieldary[0]]['html']= $turl . '/' . $ngenre .'/index.html';
    $array[$tfieldary[1]]= 'module';
    $array[$tfieldary[2]]= $ngenre;
    array_push($arrays,$array);
    $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
    $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
    $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
    $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'hidden') . "=0";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('detail', $trow[$nidfield], '0');
      $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('detail', $trow[$nidfield], '1');
      $array[$tfieldary[0]]['php']= $php_url;
      $array[$tfieldary[0]]['html']= $html_url;
      $array[$tfieldary[1]]= 'detail';
      $array[$tfieldary[2]]= $ngenre;
      array_push($arrays,$array);
      $array=array();
      $arrays = array_merge($arrays,pp_get_detail_page($ngenre,$trow[$nidfield],$trow[ii_cfnames($nfpre, 'content')]));//内容分页
    }
    //添加分页链接
    //通过总记录和分页记录数来计算共多少分页来计算
    $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];//分页记录数
    $npagenums = mysqli_num_rows($trs);//总记录数
    $npages = @ceil($npagenums/$npagesize);//总页数
    for($i=0;$i<$npages;$i++){
      $turlkey = $i * $npagesize;//offset
      $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('listpage', 0, '0', 'urlkey=' . $turlkey);
      $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('listpage', 0, '1', 'urlkey=' . $turlkey);
      $array[$tfieldary[0]]['php']= $php_url;
      $array[$tfieldary[0]]['html']= $html_url;
      $array[$tfieldary[1]]= 'listpage';
      $array[$tfieldary[2]]= $ngenre;
      array_push($arrays,$array);
      $array=array();
    }
    $trow=array();
    $trs=array();
    $tgenreary[$key]=null;
    ii_conn_close($conn);
    //复制模块静态文件
    pp_copy_folder($ngenre);
  }
  pp_create_html($arrays);
}

//内容分页
function pp_get_detail_page($genre,$id,$content)
{
    global $nurlpre;
    $array = array();
    $arrays = array();
    $tfield = 'url,type,folder';
    $tfieldary = explode(',', $tfield);
    $turl = $nurlpre;
    $ngenre = $genre;
    $tpagenum = mm_cutepage_content_page($content);//总页数
    if($tpagenum > 1)
    {
      for($i=0;$i<$tpagenum;$i++){
        $tcutekey = $i + 1;//offset
        $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('detail', $id, '0', 'cutekey=' . $tcutekey);
        $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('cutepage', $id, '1', 'cutekey=' . $tcutekey);
        $array[$tfieldary[0]]['php']= $php_url;
        $array[$tfieldary[0]]['html']= $html_url;
        $array[$tfieldary[1]]= 'cutepage';
        $array[$tfieldary[2]]= $ngenre;
        array_push($arrays,$array);
        $array=array();
      }
    }
    return $arrays;
}

//分类
function pp_get_sort()
{
  ii_conn_init();
  global $conn, $variable, $nurlpre, $slng;
  global $sort_database, $sort_idfield, $sort_fpre;
  $array = array();
  $arrays = array();
  $turl = $nurlpre;
  $tfield = 'url,type,folder';
  $tfieldary = explode(',', $tfield);
  $tmpstr = '';
  $tsqlstr = "select * from $sort_database where " . ii_cfnames($sort_fpre, 'lng') . "='$slng' and " . ii_cfnames($sort_fpre, 'hidden') . "=0";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $ngenre = $trow[ii_cfnames($sort_fpre, 'genre')];
    $sortid = $trow[$sort_idfield];
    //带分页的分类文件
    $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
    $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
    $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
    $nsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'hidden') . "=0 and " . ii_cfnames($nfpre, 'class') . "=".$sortid;
    $nrs = ii_conn_query($nsqlstr, $conn);
    if($nrs){
        //添加分页链接
        //通过总记录和分页记录数来计算共多少分页来计算
        $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];//分页记录数
        $npagenums = mysqli_num_rows($nrs);//总记录数
        if($npagenums == 0){
              $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('list', $sortid, '0');
              $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('listpage', $sortid, '1');
              $array[$tfieldary[0]]['php']= $php_url;
              $array[$tfieldary[0]]['html']= $html_url;
              $array[$tfieldary[1]]= 'listpage';
              $array[$tfieldary[2]]= $ngenre;
              array_push($arrays,$array);
              $array=array();
        }else{
            $npages = @ceil($npagenums/$npagesize);//总页数
            for($i=0;$i<$npages;$i++){
              $turlkey = $i * $npagesize;//offset
              $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('list', $sortid, '0', 'urlkey=' . $turlkey);
              $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('listpage', $sortid, '1', 'urlkey=' . $turlkey);
              $array[$tfieldary[0]]['php']= $php_url;
              $array[$tfieldary[0]]['html']= $html_url;
              $array[$tfieldary[1]]= 'listpage';
              $array[$tfieldary[2]]= $ngenre;
              array_push($arrays,$array);
              $array=array();
            }
        }
    }else{
      $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('list', $sortid, '0');
      $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('listpage', $sortid, '1');
      $array[$tfieldary[0]]['php']= $php_url;
      $array[$tfieldary[0]]['html']= $html_url;
      $array[$tfieldary[1]]= 'listpage';
      $array[$tfieldary[2]]= $ngenre;
      array_push($arrays,$array);
      $array=array();
    }
  }
  $nrs=array();
  $trow=array();
  $trs=array();
  ii_conn_close($conn);
  //复制分类静态文件
  $tfolder = 'support/sort';
  $tfolderary = explode(',', $tfolder);
  foreach($tfolderary as $folder){
    pp_copy_folder($folder);
  }
  //复制分类静态文件
  pp_create_html($arrays);
}

//专题
function pp_get_pages()
{
  global $nurlpre;
  $array = array();
  $arrays = array();
  $tfield = 'url,type,folder';
  $tgenre = ii_itake('config.pages','lng');
  if (ii_isnull($tgenre)) return $arrays;
  $turl = $nurlpre;
  $tfieldary = explode(',', $tfield);
  $tgenreary = explode(',', $tgenre);
  foreach($tgenreary as $key => &$val)
  {
    ii_conn_init();
    global $conn, $variable, $slng;
    $ngenre = $val;
    $array[$tfieldary[0]]['php']= $turl . '/' . $ngenre .'/index.php';
    $array[$tfieldary[0]]['html']= $turl . '/' . $ngenre .'/index.html';
    $array[$tfieldary[1]]= 'pages';
    $array[$tfieldary[2]]= $ngenre;
    array_push($arrays,$array);
    $arrays = array_merge($arrays,pp_get_pageslist($ngenre));//专题首页列表
    $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
    $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
    $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
    $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'hidden') . "=0";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tid = $trow[$nidfield];
      $arrays = array_merge($arrays,pp_get_pageslist($ngenre,$tid));//专题分类页列表
      $ttype = $trow[ii_cfnames($nfpre, 'type')];
      if($ttype == 0){
        $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('list', $tid, '0');
        $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('list', $tid, '1');
      }else{
        $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('detail', $tid, '0');
        $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('detail', $tid, '1');
        $arrays = array_merge($arrays,pp_get_detail_page($ngenre,$tid,$trow[ii_cfnames($nfpre, 'content')]));//内容分页
      }
      $array[$tfieldary[0]]['php']= $php_url;
      $array[$tfieldary[0]]['html']= $html_url;
      $array[$tfieldary[1]]= 'detail';
      $array[$tfieldary[2]]= $ngenre;
      array_push($arrays,$array);
      $array=array();
    }
    $trow=array();
    $trs=array();
    $tgenreary[$key]=null;
    ii_conn_close($conn);
    //复制模块静态文件
    pp_copy_folder($ngenre);
  }
  pp_create_html($arrays);
}

//专题列表
function pp_get_pageslist($genre,$fsid = '0')
{
    global $nurlpre;
    global $conn, $variable, $slng;
    $tfield = 'url,type,folder';;
    $tfieldary = explode(',', $tfield);
    $turl = $nurlpre;
    $ngenre = $genre;
    $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
    $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
    $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
    $array = array();
    $arrays = array();
    //添加分页链接
    //查询子内容数据
    $tid = $fsid;
    $nsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'hidden') . "=0 and " . ii_cfnames($nfpre, 'type') . "=1 and " . ii_cfnames($nfpre, 'fsid') . "= ".$tid;
    $nrs = ii_conn_query($nsqlstr, $conn);
    if($nrs){
        //添加分页链接
        //通过总记录和分页记录数来计算共多少分页来计算
        $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];//分页记录数
        $npagenums = mysqli_num_rows($nrs);//总记录数
        $npages = @ceil($npagenums/$npagesize);//总页数
        for($i=0;$i<$npages;$i++){
          $turlkey = $i * $npagesize;//offset
          $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('list', $tid, '0', 'urlkey=' . $turlkey);
          $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('listpage', $tid, '1', 'urlkey=' . $turlkey);
          $array[$tfieldary[0]]['php']= $php_url;
          $array[$tfieldary[0]]['html']= $html_url;
          $array[$tfieldary[1]]= 'listpage';
          $array[$tfieldary[2]]= $ngenre;
          array_push($arrays,$array);
          $array=array();
        }
    }else{
      $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('list', $tid, '0');
      $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('listpage', $tid, '1');
      $array[$tfieldary[0]]['php']= $php_url;
      $array[$tfieldary[0]]['html']= $html_url;
      $array[$tfieldary[1]]= 'listpage';
      $array[$tfieldary[2]]= $ngenre;
      array_push($arrays,$array);
      $array=array();
    }
  return $arrays;
}

//标签
function pp_get_tags()
{
  ii_conn_init();
  global $conn, $variable, $nurlpre, $slng;
  $array = array();
  $arrays = array();
  $turl = $nurlpre;
  $tfield = 'url,type,folder';
  $tgenre = ii_itake('config.tags','lng');
  if (ii_isnull($tgenre)) return $arrays;
  $tfieldary = explode(',', $tfield);
  $tgenreary = explode(',', $tgenre);
  foreach($tgenreary as $key => &$val)
  {
    ii_conn_init();
    $ngenre = $val;
    $array[$tfieldary[0]]['php']= $turl . '/' . $ngenre .'/index.php';
    $array[$tfieldary[0]]['html']= $turl . '/' . $ngenre .'/index.html';
    $array[$tfieldary[1]]= 'tags';
    $array[$tfieldary[2]]= $ngenre;
    array_push($arrays,$array);
    $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
    $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
    $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
    $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre,'hidden') . "=0";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
        $tid = $trow[$nidfield];
        $tgourl = mm_get_field($ngenre,$tid,'gourl');
        if (ii_isnull($tgourl)){
            $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('detail', $tid, '0');
            $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('detail', $tid, '1');
            $array[$tfieldary[0]]['php']= $php_url;
            $array[$tfieldary[0]]['html']= $html_url;
            $array[$tfieldary[1]]= 'detail';
            $array[$tfieldary[2]]= $ngenre;
            array_push($arrays,$array);
            $array=array();
            $arrays = array_merge($arrays,pp_get_detail_page($ngenre,$tid,$trow[ii_cfnames($nfpre, 'content')]));//内容分页
            $arrays = array_merge($arrays,pp_get_tagslist($ngenre,$tid));//标签详情页列表
        }
    }
    //复制模块静态文件
    pp_copy_folder($ngenre);
  }
  pp_create_html($arrays);
}

//标签详情页列表
function pp_get_tagslist($genre,$id)
{
  ii_conn_init();
  global $conn, $variable, $nurlpre;
  $array = array();
  $arrays = array();
  $turl = $nurlpre;
  $tfield = 'url,type,folder';
  $tfieldary = explode(',', $tfield);
  $tid = $id;
  //通过id获取标签标题topic
  $ngenre = $genre;
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre,'hidden') . "=0 and $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tgourl = mm_get_field($ngenre,$tid,'gourl');
    if (!ii_isnull($tgourl)) return;//如果有跳转链接,排除
    else $tshkeyword = ii_get_safecode($trs[ii_cfnames($nfpre,'topic')]);
  }
  if (!ii_isnull($tshkeyword)){
      global $slng;
      $nsearch_genre = $variable[ii_cvgenre($ngenre) . '.nsearch_genre'];
      $nsearch_field = $variable[ii_cvgenre($ngenre) . '.nsearch_field'];
      $tndatabases = explode(',', $nsearch_genre);
      $tnfields = explode(',', $nsearch_field);
      $tsqlstr = "";
      for ($ti = 0; $ti < count($tndatabases); $ti ++)
      {
        $tndatabase = $tndatabases[$ti];
        $tdatabase = $variable[ii_cvgenre($tndatabase) . '.ndatabase'];
        $tidfield = $variable[ii_cvgenre($tndatabase) . '.nidfield'];
        $tfpre = $variable[ii_cvgenre($tndatabase) . '.nfpre'];
        $tunion = " union all ";
        $tsqlstr .= "select * from (";
        $tsqlstr .= "select " . $tidfield . " as un_id from " . $tdatabase . " where " . ii_cfnames($tfpre, 'hidden') . "=0 and " . ii_cfnames($tfpre, 'lng') . "='$slng'";
        foreach ($tnfields as $tnfield)
        {
          if ($tnfield == 'topic') $tsqlstr .= " and " . ii_cfnames($tfpre, $tnfield) . " like '%" . $tshkeyword . "%'";
          else $tsqlstr .= " or " . ii_cfnames($tfpre, $tnfield) . " like '%" . $tshkeyword . "%'";
        }
        if ($ti == count($tndatabases) - 1) $tsqlstr .= " order by " . ii_cfnames($tfpre, 'time') . " desc) as un_" . $tndatabase;
        else $tsqlstr .= " order by " . ii_cfnames($tfpre, 'time') . " desc) as un_" . $tndatabase . $tunion;
      }
      $nrs = ii_conn_query($tsqlstr, $conn);
      if($nrs){
        $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];//分页记录数
        $npagenums = mysqli_num_rows($nrs);//总记录数
        $npages = @ceil($npagenums/$npagesize);//总页数
        for($i=0;$i<$npages;$i++){
          $tcutekey = $i * $npagesize;
          $php_url = $turl . '/'.$ngenre.'/'.ii_iurl('tags', $id, '0', 'cutekey=' . $tcutekey);
          $html_url = $turl . '/'.$ngenre.'/'.ii_iurl('tags', $id, '1', 'cutekey=' . $tcutekey);
          $array[$tfieldary[0]]['php']= $php_url;
          $array[$tfieldary[0]]['html']= $html_url;
          $array[$tfieldary[1]]= 'listpage';
          $array[$tfieldary[2]]= $ngenre;
          array_push($arrays,$array);
          $array=array();
        }
      }
  }
return $arrays;
}

function wdja_cms_admin_manage_createdisp()
{
  global $nsaveimages,$nurlpre, $slng;
  $array = array();
  $arrays = array();
  $sort = ii_itake('global.expansion/htmlplus:config.sort','lng');
  if (!(is_dir(ii_get_actual_route($html_folder)))) ii_mkdir($html_folder);
  $tbackurl = $_GET['backurl'];
  $tmpstr = '';
  pp_get_home();
  if ($sort == 1) pp_get_sort();
  pp_get_list();
  pp_get_pages();
  pp_get_tags();
  pp_get_singlepage();
  wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
}

function pp_upload_oss($web_folder = '')
{
  $config_folder = ii_itake('global.expansion/htmlplus:config.web_folder','lng');
  $root_folder = ii_get_actual_route($config_folder);
  if(ii_isnull($web_folder)) $web_folder = $root_folder;
    $dirHandle = opendir($web_folder);
    while (false !== ($file = readdir($dirHandle))) {
      if ($file == '.' || $file == '..') {
        continue;
      }
      if (!is_dir($web_folder .'/'. $file)) {
          $local_file = str_replace('//','/', $web_folder .'/'. $file);
          $remote_file = str_replace($root_folder.'/','', $local_file);
          if(mm_oss_upload_Files($local_file,$remote_file)) echo $remote_file."上传成功<br/>";
      } else {
        pp_upload_oss($web_folder .'/'. $file);
      }
    }
    return closedir($dirHandle);
}

function pp_create_html($arr){
  $web_folder = ii_itake('global.expansion/htmlplus:config.web_folder','lng');
  $web_folder = ii_get_actual_route($web_folder);
  if(count($arr) > 0){
      foreach($arr as $ary){
        $html_filename = ii_get_lrstr($ary['url']['html'], '/', 'right');
        if(ii_get_lrstr($html_filename, '.', 'right') == 'html'){
          $turl = $ary['url']['php'];
          if(!ii_isnull($web_folder)) $html_folder = $web_folder.'/'.$ary['folder'];
          else $html_folder = $ary['folder'];
          $tfileDATA = pp_geturl_content($turl);
          global $nurlpre;
          $confit_url = ii_itake('global.expansion/htmlplus:config.url', 'lng');
          if(!ii_isnull($web_folder)) $tfileDATA = str_replace($nurlpre, $confit_url, $tfileDATA);
          $tfileHTMLURL = $html_folder.'/'.$html_filename;
          if (!(is_dir(ii_get_actual_route($html_folder)))) ii_mkdir($html_folder);
          pp_put_content($tfileHTMLURL, $tfileDATA);
        }
      }
  }
}

function pp_put_content($file,$data){
  if(!ii_isnull($data)){
    $tp = fopen($file, 'w');
    fwrite($tp, $data);
    fclose($tp);
  }
}

function pp_geturl_content($url) {
    if (function_exists('file_get_contents')) {
        $file_contents = @file_get_contents($url);
    }
    if ($file_contents =='') {
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
    }
    return $file_contents;
}

function pp_copy_folder($ngenre){
    //复制单页静态文件
    $web_folder = ii_itake('global.expansion/htmlplus:config.web_folder','lng');
    if(!ii_isnull($web_folder))
    {
        $from_folder = ii_get_actual_route($ngenre).'/common/upload/';
        $to_folder = ii_get_actual_route($web_folder).'/'.$ngenre.'/common/upload/';
        $from_folder2 = ii_get_actual_route($ngenre).'/common/images/';
        $to_folder2 = ii_get_actual_route($web_folder).'/'.$ngenre.'/common/images/';
        $fu = new FileUtil();
        if(is_dir($from_folder)) $fu->copyDir($from_folder, $to_folder, true);
        if(is_dir($from_folder2)) $fu->copyDir($from_folder2, $to_folder2, true);
    }
    //复制单页静态文件
}

function wdja_cms_admin_manage_configdisp()
{
  global $nurlpre;
  $tbackurl = $_GET['backurl'];
  $tburl = pp_get_xml_root() .'config' . XML_SFX;
  $tnode = 'item';
  $tfield = 'disinfo,chinese';
  $tbase = 'language_list';
  $torder = 'url,sort,module,pages,tags,singlepage,web_folder,';
  if (ii_right($torder, 1) == ',') $torder = ii_left($torder, (strlen($torder) - 1));
  if (file_exists($tburl) && (!ii_isnull($tnode)) && (!ii_isnull($tfield)) && (!ii_isnull($tbase)))
  {
    $tmpstr = '';
    $tmode = ii_get_xrootatt($tburl, 'mode');
    $tfieldary = explode(',', $tfield);
    $torderary = explode(',', $torder);
    $tub = count($tfieldary);
    $tmpstr .= '<?xml version="1.0" encoding="' . CHARSET . '"?>' . CRLF;
    $tmpstr .= '<xml mode="' . $tmode . '" author="wdja">' . CRLF;
    $tmpstr .= '  <configure>' . CRLF;
    $tmpstr .= '    <node>' . $tnode . '</node>' . CRLF;
    $tmpstr .= '    <field>' . $tfield . '</field>' . CRLF;
    $tmpstr .= '    <base>' . $tbase . '</base>' . CRLF;
    $tmpstr .= '  </configure>' . CRLF;
    $tmpstr .= '  <' . $tbase . '>' . CRLF;
    foreach($torderary as $key => &$val)
    {
      $tmpstr .= '    <' . $tnode . '>' . CRLF;
      $tmpstr .= '      <' . $tfieldary[0] . '><![CDATA[' . $val . ']]></' . $tfieldary[0] . '>' . CRLF;
      if ($val == 'url' && ii_isnull(ii_itake('global.expansion/htmlplus:config.url', 'lng'))) $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . $nurlpre . ']]></' . $tfieldary[1] . '>' . CRLF;
      elseif ($val == 'page_num') $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . ii_get_num($_POST[$val],1000) . ']]></' . $tfieldary[1] . '>' . CRLF;
      else $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . $_POST[$val] . ']]></' . $tfieldary[1] . '>' . CRLF;
      $tmpstr .= '    </' . $tnode . '>' . CRLF;
      $torderary[$key]=null;
    }
    $tmpstr .= '  </' . $tbase . '>' . CRLF;
    $tmpstr .= '</xml>' . CRLF;
    if (file_put_contents($tburl, $tmpstr)) wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_action()
{
  switch($_GET['action'])
  {
    case 'config':
      return wdja_cms_admin_manage_configdisp();
      break;
    case 'create':
      return wdja_cms_admin_manage_createdisp();
      break;
    case 'upload_oss':
      if(OSS_SWITCH == 1) return pp_upload_oss();
      break;
  }
}

function wdja_cms_admin_manage_config()
{
  global $conn,$nurlpre,$ngenre,$slng;
  global $ndatabase, $nidfield, $nfpre;
  $trootstr = pp_get_xml_root() . 'config'. XML_SFX;
  if (file_exists($trootstr))
  {
    $tmpstr = ii_itake('manage.config' , 'tpl');
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
          if ($k == 'url' && ii_isnull($nodeValue)) $nodeValue = $nurlpre;
          if (ii_isnull($GLOBALS['RS_' . $k])) $GLOBALS['RS_' . $k] = $nodeValue;
          $tmpstr = str_replace('{$'.$k.'}', ii_htmlencode($nodeValue), $tmpstr);
        }
      }
    }
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('manage.notexists', 'lng'), -1);
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'config':
      return wdja_cms_admin_manage_config();
      break;
    default:
      return wdja_cms_admin_manage_config();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>