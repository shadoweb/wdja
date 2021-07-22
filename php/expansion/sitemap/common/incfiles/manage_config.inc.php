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

function pp_get_htmlmap()
{
    global $conn,$variable,$nurlpre;
    $array = array();
    $arrays = array();
    $tfield = 'loc,lastmod,changefreq,priority';
    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng').'/sitemap.html';
    if (ii_isnull($turl)) $turl = $nurlpre;
    $tmpstr = '';
    $tfieldary = explode(',', $tfield);
    $tfieldary = explode(',', $tfield);
    $array[$tfieldary[0]]= $turl;
    $array[$tfieldary[1]]= ii_format_date(ii_now(),1);
    $array[$tfieldary[2]]= 'daily';
    $array[$tfieldary[3]]= 1.0;
    array_push($arrays,$array);
    $array=array();
    return $arrays;
}

function pp_get_home()
{
    global $conn,$variable,$nurlpre;
    $array = array();
    $arrays = array();
    $tfield = 'loc,lastmod,changefreq,priority';
    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
    if (ii_isnull($turl)) $turl = $nurlpre;
    $tmpstr = '';
    $tfieldary = explode(',', $tfield);
    $array[$tfieldary[0]]= $turl;
    $array[$tfieldary[1]]= ii_format_date(ii_now(),1);
    $array[$tfieldary[2]]= 'daily';
    $array[$tfieldary[3]]= 1.0;
    array_push($arrays,$array);
    $array=array();
    return $arrays;
}

function pp_get_home_html()
{
    global $conn,$variable,$nurlpre, $slng;
    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
    if (ii_isnull($turl)) $turl = $nurlpre;
    $tmpstr = '';
      $tmpstr .= '   <div id="nav">' . CRLF;
      $tmpstr .= '      <a href="' . $turl .'" target="_blank">'.ii_itake('global.support/global:basic.web_name','lng').'</a>»<a href="' . $turl .'/sitemap.html">'.ii_itake('global.expansion/sitemap:module.sitemap', 'lng').'</a>' . CRLF;
      $tmpstr .= '    </div>' . CRLF;
    return $tmpstr;
}

function pp_get_singlepage()
{
    global $conn,$variable,$nurlpre;
    $array = array();
    $arrays = array();
    $tfield = 'loc,lastmod,changefreq,priority';
    $tgenre = ii_itake('config.singlepage','lng');
    if (ii_isnull($tgenre)) return;
    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
    if (ii_isnull($turl)) $turl = $nurlpre;
    $tmpstr = '';
    $tfieldary = explode(',', $tfield);
    $tgenreary = explode(',', $tgenre);
    foreach($tgenreary as $key => &$val)
    {
        $array[$tfieldary[0]]= $turl . '/' . $val;
        $array[$tfieldary[1]]= ii_format_date(ii_now(),1);
        $array[$tfieldary[2]]= 'weekly';
        $array[$tfieldary[3]]= 0.8;
        array_push($arrays,$array);
        $array=array();
        $tgenreary[$key]=null;
    }
return $arrays;
}

function pp_get_singlepage_html()
{
    global $conn,$variable,$nurlpre, $slng;
    $array = array();
    $arrays = array();
    $tgenre = ii_itake('config.singlepage','lng');
    if (ii_isnull($tgenre)) return;
    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
    if (ii_isnull($turl)) $turl = $nurlpre;
    $tgenreary = explode(',', $tgenre);
      foreach($tgenreary as $key => &$val)
      {
          $array['title'] = ii_itake('global.expansion/sitemap:module.page', 'lng');
          $array['url'] = $turl . '/' . $val;
          $array['topic'] = ii_itake('global.' . $val .':module.topic','lng');
          array_push($arrays,$array);
          $array=array();
          $tgenreary[$key]=null;
      }
    return $arrays;
}

function pp_get_list()
{
    global $nurlpre;
    $array = array();
    $arrays = array();
    $tfield = 'loc,lastmod,changefreq,priority';
    $tgenre = ii_itake('config.module','lng');
    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
    if (ii_isnull($turl)) $turl = $nurlpre;
    $tmpstr = '';
    $tfieldary = explode(',', $tfield);
    $tgenreary = explode(',', $tgenre);
    foreach($tgenreary as $key => &$val)
    {
      ii_conn_init();
      global $conn, $variable, $slng;
      $ngenre = $val;
      $array[$tfieldary[0]]= $turl . '/' . $ngenre .'/';
      $array[$tfieldary[1]]= ii_format_date(ii_now(),1);
      $array[$tfieldary[2]]= 'weekly';
      $array[$tfieldary[3]]= 0.8;
      array_push($arrays,$array);
      $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
      $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
      $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
      $nurltype = $variable[ii_cvgenre($ngenre) . '.nurltype'];
      $ncreatefolder = $variable[ii_cvgenre($ngenre) . '.ncreatefolder'];
      $ncreatefiletype = $variable[ii_cvgenre($ngenre) . '.ncreatefiletype'];
        $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'hidden') . "=0";
        $trs = ii_conn_query($tsqlstr, $conn);
        while ($trow = ii_conn_fetch_array($trs))
        {
            $array[$tfieldary[0]]= $turl . '/'.$ngenre.'/'.ii_iurl('detail', $trow[$nidfield], $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype);
            $array[$tfieldary[1]]= ii_format_date($trow[ii_cfnames($nfpre, 'time')],1);
            $array[$tfieldary[2]]= 'weekly';
            $array[$tfieldary[3]]= 0.6;
            array_push($arrays,$array);
            $array=array();
        }
        $trow=array();
        $trs=array();
        $tgenreary[$key]=null;
        ii_conn_close($conn);
    }
    return $arrays;
}

function pp_get_list_html()
{
    global $nurlpre;
    $array = array();
    $arrays = array();
    $tgenre = ii_itake('config.module','lng');
    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
    if (ii_isnull($turl)) $turl = $nurlpre;
    $tgenreary = explode(',', $tgenre);
    foreach($tgenreary as $key => &$val)
    {
      ii_conn_init();
      global $conn, $variable, $slng;
      $ngenre = $val;
      $array['title'] = ii_itake('global.expansion/sitemap:module.module', 'lng');
      $array['url'] = $turl . '/' . $ngenre .'/';
      $array['topic'] = ii_itake('global.' . $val .':module.channel_title','lng');
      if (!ii_isnull($array['topic'])) array_push($arrays,$array);
      $array = array();
      $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
      $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
      $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
      $nurltype = $variable[ii_cvgenre($ngenre) . '.nurltype'];
      $ncreatefolder = $variable[ii_cvgenre($ngenre) . '.ncreatefolder'];
      $ncreatefiletype = $variable[ii_cvgenre($ngenre) . '.ncreatefiletype'];
        $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'hidden') . "=0 and " . ii_cfnames($nfpre, 'lng') . "='$slng'";
        $trs = ii_conn_query($tsqlstr, $conn);
          while ($trow = ii_conn_fetch_array($trs))
          {
              $array['title'] = ii_itake('global.expansion/sitemap:module.content', 'lng');
              $array['url'] = $turl . '/'.$ngenre.'/'.ii_iurl('detail', $trow[$nidfield], $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype);
              $array['topic'] = $trow[ii_cfnames($nfpre, 'topic')];
              array_push($arrays,$array);
              $array=array();
          }
          $trow=array();
          $trs=array();
          $tgenreary[$key]=null;
          ii_conn_close($conn);
    }
    return $arrays;
}

function pp_get_sort()
{
  ii_conn_init();
  global $conn, $variable, $nurlpre, $slng;
  global $sort_database, $sort_idfield, $sort_fpre;
  $array = array();
  $arrays = array();
  $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
  if (ii_isnull($turl)) $turl = $nurlpre;
  $tfield = 'loc,lastmod,changefreq,priority';
  $tfieldary = explode(',', $tfield);
  $tmpstr = '';
  $tsqlstr = "select * from $sort_database where " . ii_cfnames($sort_fpre, 'lng') . "='$slng' and " . ii_cfnames($sort_fpre, 'hidden') . "=0";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $ngenre = $trow[ii_cfnames($sort_fpre, 'genre')];
    $nurltype = $variable[ii_cvgenre($ngenre) . '.nurltype'];
    $ncreatefolder = $variable[ii_cvgenre($ngenre) . '.ncreatefolder'];
    $ncreatefiletype = $variable[ii_cvgenre($ngenre) . '.ncreatefiletype'];
    $array[$tfieldary[0]]= $turl . '/' . $ngenre .'/'. ii_iurl('list', $trow[$sort_idfield], $nurltype, 'folder='.$ncreatefolder.';filetype='.$ncreatefiletype);
    $array[$tfieldary[1]]= ii_format_date($trow[ii_cfnames($sort_fpre, 'time')],1);
    $array[$tfieldary[2]]= 'daily';
    $array[$tfieldary[3]]= 1.0;
    array_push($arrays,$array);
    $array=array();
  }
  $trow=array();
  $trs=array();
  ii_conn_close($conn);
  return $arrays;
}

function pp_get_sort_html()
{
  ii_conn_init();
  global $conn, $variable, $nurlpre, $slng;
  global $sort_database, $sort_idfield, $sort_fpre;
  $array = array();
  $arrays = array();
  $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
  if (ii_isnull($turl)) $turl = $nurlpre;
  $tfield = 'loc,lastmod,changefreq,priority';
  $tfieldary = explode(',', $tfield);
  $tsqlstr = "select * from $sort_database where " . ii_cfnames($sort_fpre, 'lng') . "='$slng' and " . ii_cfnames($sort_fpre, 'hidden') . "=0";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
      $ngenre = $trow[ii_cfnames($sort_fpre, 'genre')];
      $nurltype = $variable[ii_cvgenre($ngenre) . '.nurltype'];
      $ncreatefolder = $variable[ii_cvgenre($ngenre) . '.ncreatefolder'];
      $ncreatefiletype = $variable[ii_cvgenre($ngenre) . '.ncreatefiletype'];
      $array['title'] = ii_itake('global.expansion/sitemap:module.sort', 'lng');
      $array['url'] = $turl . '/' . $ngenre .'/'. ii_iurl('list', $trow[$sort_idfield], $nurltype, 'folder='.$ncreatefolder.';filetype='.$ncreatefiletype);
      $array['topic'] = $trow[ii_cfnames($sort_fpre, 'sort')];
      array_push($arrays,$array);
      $array=array();
  }
  $trow=array();
  $trs=array();
  $arrays = $arrays;
  ii_conn_close($conn);
  return $arrays;
}

function wdja_cms_admin_manage_createdisp()
{
  global $nsaveimages,$nurlpre, $slng;
  $array = array();
  $arrays = array();
  $sort = ii_itake('global.expansion/sitemap:config.sort','lng');
  $save = ii_itake('global.expansion/sitemap:config.save','lng');
  $tbackurl = $_GET['backurl'];
    $tmpstr = '';
    $arrays = array_merge($arrays,pp_get_htmlmap());
    $arrays = array_merge($arrays,pp_get_home());
    if ($sort == 1) $arrays = array_merge($arrays,pp_get_sort());
    $arrays = array_merge($arrays,pp_get_list());
    $arrays = array_merge($arrays,pp_get_singlepage());
    $page_num = ii_itake('global.expansion/sitemap:config.page_num', 'lng');
    $num = count($arrays);
    $long = ceil($num/$page_num);
    if ($num > $page_num) {
        for($i=1;$i<$long+1;$i++) {
              if ($slng == 'chinese') $xname = 'sitemap_'.$i.'.xml';
              else $xname = 'sitemap_'.$slng.'_'.$i.'.xml';
              if ($save == 1) {
                  $dir = ii_get_actual_route('./') . 'xml/';
                  if (!file_exists($dir))mkdir ($dir,0777,true);
                  $tburl = $dir.$xname;
              }
              else 
              {
                  $tburl = pp_get_xml_root() . $xname;
              }
              if (!file_exists($tburl)) fopen($tburl,'w');
              if (file_exists($tburl))
              {
                $tmpstr = '';
                $tmpstr .= '<?xml version="1.0" encoding="' . CHARSET . '"?>' . CRLF;
                $tmpstr .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . CRLF;
                $num_sub = ($i-1)*$page_num + $j;
                $array = array_slice($arrays,$num_sub,$page_num);
                foreach($array as $rows) {
                    $tmpstr .= '    <url>' . CRLF;
                    foreach($rows as $key => &$val) {
                      $tmpstr .= '      <' . $key . '>'.$val.'</' . $key . '>' . CRLF;
                      $rows[$key]=null;
                    }
                    $tmpstr .= '    </url>' . CRLF;
                }
                $tmpstr .= '</urlset>';
                $array=array();
                $rows=array();
                if (file_put_contents($tburl, $tmpstr)) $tmpstr=null;
              }
        }
    }
          if ($slng == 'chinese') $xname = 'sitemap.xml';
          else $xname = 'sitemap_'.$slng.'.xml';
          if ($save == 1) $tburl = ii_get_actual_route('./').$xname;
          else $tburl = pp_get_xml_root() . $xname;
          if (!file_exists($tburl)) fopen($tburl,'w');
          if (file_exists($tburl))
          {
            $tmpstr = '';
            $tmpstr .= '<?xml version="1.0" encoding="' . CHARSET . '"?>' . CRLF;
            $tmpstr .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . CRLF;
            $limit_num = 0;
            foreach($arrays as $rows) {
                $tmpstr .= '    <url>' . CRLF;
                foreach($rows as $key => &$val) {
                  $tmpstr .= '      <' . $key . '>'.$val.'</' . $key . '>' . CRLF;
                  $rows[$key]=null;
                }
                  $tmpstr .= '    </url>' . CRLF;
                  $limit_num++;
                if ($limit_num == $page_num) break;
            }
            $tmpstr .= '</urlset>';
            $arrays=array();
            $rows=array();
            if (file_put_contents($tburl, $tmpstr)) {
                $tmpstr=null;
                wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
            }
            else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
          }
}


function wdja_cms_admin_manage_createdisp_html()
{
  global $nsaveimages,$nurlpre,$slng;
  $array = array();
  $arrays = array();
  $array2 = array();
  $arrays2 = array();
  $sort = ii_itake('config.sort','lng');
  $save = ii_itake('config.save','lng');
  $tbackurl = $_GET['backurl'];
  if ($slng == 'chinese') $hname = 'sitemap.html';
  else $hname = 'sitemap_'.$slng.'.html';
  $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
  if (ii_isnull($turl)) $turl = $nurlpre;
  if ($save == 1) $tburl = ii_get_actual_route('./') . $hname;
  else $tburl = pp_get_xml_root() . $hname;
  if (!file_exists($tburl)) fopen($tburl,'w');
  if (file_exists($tburl))
  {
    if ($sort == 1) $arrays = array_merge($arrays,pp_get_sort_html());
    $arrays = array_merge($arrays,pp_get_list_html());
    $arrays = array_merge($arrays,pp_get_singlepage_html());
    $page_num = ii_itake('global.expansion/sitemap:config.page_num', 'lng');
    $num = count($arrays);
    $long = ceil($num/$page_num);
    $page = '<div id="footer">'.ii_itake('global.expansion/sitemap:module.pagi', 'lng').': ';
    for($i=1;$i<$long+1;$i++) {
        if ($slng == 'chinese') $xname = 'sitemap_'.$i.'.html';
        else $xname = 'sitemap_'.$slng.'_'.$i.'.html';
                    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
                    if (ii_isnull($turl)) $turl = $nurlpre;
                    $tburl = $turl.'/xml/'.$xname;
        $page .= '<strong style="padding: 3px 5px;"><a href="'.$tburl.'">'.$i.'</a></strong>';
    }
    $page .= '</div>';
    if ($num > $page_num) {
        for($i=1;$i<$long+1;$i++) {
              if ($slng == 'chinese') $xname = 'sitemap_'.$i.'.html';
              else $xname = 'sitemap_'.$slng.'_'.$i.'.html';
              if ($save == 1) {
                  $dir = ii_get_actual_route('./') . 'xml/';
                  if (!file_exists($dir))mkdir ($dir,0777,true);
                  $tburl = $dir.$xname;
              }
              else 
              {
                  $tburl = pp_get_xml_root() . $xname;
              }
              if (!file_exists($tburl)) fopen($tburl,'w');
              if (file_exists($tburl))
              {
                $tmpstr = '';
                $tmpstr .= ii_ireplace('module.header' , 'tpl') . CRLF;
                $tmpstr .= pp_get_home_html() . CRLF;
                $tmpstr .= '    <div id="content"><h3>'.ii_itake('global.expansion/sitemap:module.list', 'lng').'</h3><ul>' . CRLF;
                $num_sub = ($i-1)*$page_num + $j;
                $array = array_slice($arrays,$num_sub,$page_num);
                foreach($array as $rows) {
                    $tmpstr .= '<li>['.$rows["title"].'] <a href="'.$rows["url"].'" target="_blank">'.$rows["topic"].'</a></li>';
                }
                $array=array();
                $rows=array();
                $tmpstr .= '    </ul></div>' . CRLF . $page . CRLF;
                $tmpstr .= ii_ireplace('module.footer' , 'tpl');
                if (file_put_contents($tburl, $tmpstr)) {
                    $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
                    if (ii_isnull($turl)) $turl = $nurlpre;
                    $array2['title'] = ii_itake('global.expansion/sitemap:module.mappagi', 'lng');
                    $array2['url'] = $turl.'/xml/'.$xname;
                    $array2['topic'] = $xname;
                    array_push($arrays2,$array2);
                    $array2=array();
                }
              }
        }
          if ($slng == 'chinese') $xname = 'sitemap.html';
          else $xname = 'sitemap_'.$slng.'.html';
          if ($save == 1) $tburl = ii_get_actual_route('./').$xname;
          else $tburl = pp_get_xml_root() . $xname;
          if (!file_exists($tburl)) fopen($tburl,'w');
          if (file_exists($tburl))
          {
            $tmpstr = '';
            $tmpstr .= ii_ireplace('module.header' , 'tpl') . CRLF;
            $tmpstr .= pp_get_home_html() . CRLF;
            $tmpstr .= '    <div id="content"><h3>'.ii_itake('global.expansion/sitemap:module.maplist', 'lng').'</h3><ul>' . CRLF;
            foreach($arrays2 as $rows2) {
                $tmpstr .= '<li>['.$rows2["title"].'] <a href="'.$rows2["url"].'" target="_blank">'.$rows2["topic"].'</a></li>';
            }
            $arrays2=array();
            $rows2=array();
            $tmpstr .= '    </ul></div>' . CRLF;
            $tmpstr .= ii_ireplace('module.footer' , 'tpl');
            if (file_put_contents($tburl, $tmpstr)) {
                $tmpstr=null;
                wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
            }
            else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
          }
    }else{
        
          if ($slng == 'chinese') $xname = 'sitemap.html';
          else $xname = 'sitemap_'.$slng.'.html';
          if ($save == 1) $tburl = ii_get_actual_route('./').$xname;
          else $tburl = pp_get_xml_root() . $xname;
          if (!file_exists($tburl)) fopen($tburl,'w');
          if (file_exists($tburl))
          {
            $tmpstr = '';
            $tmpstr .= ii_ireplace('module.header' , 'tpl') . CRLF;
            $tmpstr .= pp_get_home_html() . CRLF;
            $tmpstr .= '    <div id="content"><h3>'.ii_itake('global.expansion/sitemap:module.maplist', 'lng').'</h3><ul>' . CRLF;
            foreach($arrays as $rows) {
                $tmpstr .= '<li>['.$rows["title"].'] <a href="'.$rows["url"].'" target="_blank">'.$rows["topic"].'</a></li>';
            }
            $arrays=array();
            $rows=array();
            $tmpstr .= '    </ul></div>' . CRLF;
            $tmpstr .= ii_ireplace('module.footer' , 'tpl');
            if (file_put_contents($tburl, $tmpstr)) {
                $tmpstr=null;
                wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
            }
            else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
          }
    }
  }
}

function wdja_cms_admin_manage_configdisp()
{
  global $nsaveimages,$nurlpre;
  $tbackurl = $_GET['backurl'];
  $tburl = pp_get_xml_root() .'config' . XML_SFX;
  $tnode = 'item';
  $tfield = 'disinfo,chinese';
  $tbase = 'language_list';
  $torder = 'url,sort,module,singlepage,page_num,save,';
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
      if ($nsaveimages == '1' && $val == 'content') $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . saveimages($_POST[$val]) . ']]></' . $tfieldary[1] . '>' . CRLF;
      elseif ($val == 'url' && ii_isnull(ii_itake('global.expansion/sitemap:config.url', 'lng'))) $tmpstr .= '      <' . $tfieldary[1] . '><![CDATA[' . $nurlpre . ']]></' . $tfieldary[1] . '>' . CRLF;
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
    case 'create_html':
      return wdja_cms_admin_manage_createdisp_html();
      break;
  }
}

function wdja_cms_admin_manage_config()
{
  global $conn,$nurlpre,$ngenre,$slng;
  global $ndatabase, $nidfield, $nfpre;
  $save = ii_itake('config.save','lng');
  $trootstr = pp_get_xml_root() . 'config'. XML_SFX;
  $turl = ii_itake('global.expansion/sitemap:config.url', 'lng');
  if ($slng == 'chinese') $sname = 'sitemap';
  else $sname = 'sitemap_'.$slng;
  if (ii_isnull($turl)) $turl = $nurlpre;
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
    if ($save == 1) {
      $sitemap = $turl . '/'.$sname.'.xml';
      $sitemap_html = $turl . '/'.$sname.'.html';
    }else{
      $sitemap = $turl .'/'.ii_get_actual_route($ngenre). '/common/language/'.$sname.'.xml';
      $sitemap_html = $turl .'/'.ii_get_actual_route($ngenre). '/common/language/'.$sname.'.html';
    }
    $sitemap = str_replace('../', '', $sitemap);
    $sitemap_html = str_replace('../', '', $sitemap_html);
    $tmpstr = str_replace('{$sitemap}', $sitemap, $tmpstr);
    $tmpstr = str_replace('{$sitemap_html}', $sitemap_html, $tmpstr);
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