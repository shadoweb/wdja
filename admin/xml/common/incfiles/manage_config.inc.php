<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************

function pp_get_template_select()
{
  global $variable;
  $tmodule = $_GET['module'];
  $tgroup = $_GET['group'];
  $tary = ii_get_valid_module();
  switch($tgroup)
  {
    case 'lng':
      $tthings = '/common/language/module' . XML_SFX;
      break;
    case 'tpl':
      $tthings = '/common/template/' . $GLOBALS['default_skin' ].'/module' . XML_SFX;
    default:
      $tthings = '/common/template/' . $GLOBALS['default_skin' ].'/module' . XML_SFX;
      break;
  }
  if (is_array($tary))
  {
    $tmpstr = '';
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    foreach ($tary as $key => $val)
    {
      $nxml = ii_get_num($variable[ii_cvgenre($val) . '.nxml'],0);
      if ($nxml == 1) {
          if (!ii_isnull($tmodule) && $val == $tmodule) $tmprstr = $option_selected;
          else $tmprstr = $option_unselected;
          if (file_exists(ii_get_actual_route($val) . $tthings)) {
            $tmprstr = str_replace('{$explain}', '(' . mm_get_genre_description($val) . ')' , $tmprstr);
            $tmprstr = str_replace('{$value}', $val, $tmprstr);
          }
          else continue;
        $tmpstr .= $tmprstr;
      }
      else continue;
    }
    return $tmpstr;
  }
}

function pp_get_template_node($item_str)
{
   $titem = $_GET['item'];
   $item_array = explode(',', $item_str);
   if (is_array($item_array))
    {
      $tmpstr = '';
      $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
      $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
      foreach ($item_array as $key => $val)
      {
        if (!ii_isnull($titem) && $val == $titem) $tmprstr = $option_selected;
        else $tmprstr = $option_unselected;
        $tmprstr = str_replace('{$explain}', $val , $tmprstr);
        $tmprstr = str_replace('{$value}', $val, $tmprstr);
        $tmpstr .= $tmprstr;
      }
      return $tmpstr;
    }
}

function pp_get_template_root($strers)
{
  if (!ii_isnull($strers))
  {
    $tary = explode('.', $strers);
    if (count($tary) == 3)
    {
      $tmpstr = ii_get_actual_route($tary[0]);
      if (ii_right($tmpstr, 1) != '/') $tmpstr .= '/';
      switch($tary[1])
      {
        case 'tpl':
          $tmproot = 'common/template/' . $GLOBALS['default_skin' ].'/';
          break;
        case 'lng':
          $tmproot = 'common/language/';
          break;
        default:
          $tmproot = 'common/';
          break;
      }
      $tmpstr = $tmpstr . $tmproot . $tary[2];
      return $tmpstr;
    }
  }
}

function wdja_cms_admin_manage_adddisp()
{
      $tbackurl = $_GET['backurl'];
      $filepath = $_POST['xmlconfig_burl'];
      $nodename = trim($_POST['nodename']);
      if (strpos($tbackurl,'?')) $tbackurl = $tbackurl.'&item='.$nodename;
      else $tbackurl = $tbackurl.'?item='.$nodename;
      if (is_file($filepath))
      {
        $doc = new DOMDocument();
        $doc -> formatOutput = true;
        $doc -> preserveWhiteSpace = false;
        $doc -> load($filepath);
        $xpath = new DOMXPath($doc);
        $query = '//xml/configure/node';
        $node = $xpath -> query($query) -> item(0) -> nodeValue;
        $query = '//xml/configure/field';
        $field = $xpath -> query($query) -> item(0) -> nodeValue;
        $query = '//xml/configure/base';
        $base = $xpath -> query($query) -> item(0) -> nodeValue;
        $fieldArys = explode(',', $field);
        $query = '//xml/' . $base . '/' . $node . '/' . current($fieldArys) . '[text()=\'' . $nodename . '\']';
        $rests = $xpath -> query($query);
        $matchLength = ii_get_num($rests -> length, 0);
        if ($matchLength >= 1) wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
        else
        {
          $baseQuery = '//xml/' . $base;
          $baseDom = $xpath -> query($baseQuery) -> item(0);
          $newNode = $doc -> createElement($node);
          $newNodeName = $doc -> createElement(current($fieldArys));
          $newNodeName -> appendChild($doc -> createCDATASection($nodename));
          $newNode -> appendChild($newNodeName);
          for ($ti = 1; $ti < count($fieldArys); $ti ++)
          {
            $newNodeField = $doc -> createElement($fieldArys[$ti]);
            $newNodeField -> appendChild($doc -> createCDATASection(''));
            $newNode -> appendChild($newNodeField);
          }
          $baseDom -> appendChild($newNode);
          $bool = $doc -> save($filepath);
          if ($bool == false) wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
          else
          {
            wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
          }
        }
      }
}

function wdja_cms_admin_manage_editdisp()
{
      $tbackurl = $_GET['backurl'];
      $keyword = $_POST['xmlconfig_field'];
      $sourceFile = $_POST['xmlconfig_burl'];
      $name = trim($_POST['name']);
      $value = $_POST[$name];
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
        if ($docSave !== false) wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
        else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_deletedisp()
{
  $trootstr = $_GET['xml'];
  $tbackurl = $_GET['backurl'];
  if (strpos($tbackurl,'&item=')) $tbackurl_array = explode('&', $tbackurl);
   if (is_array($tbackurl_array))
    {
     $tbackurl = '';
     foreach($tbackurl_array as $k => $v) {
         if (strpos($v,'item=') !== false) continue;
         if ($k == 0) $tbackurl .= $v;
         else $tbackurl .= '&'.$v;
     }
    }
  $tdelnode = $_GET['node'];
  $tdoc = new DOMDocument();
  $tdoc -> load($trootstr);
  $txpath = new DOMXPath($tdoc);
  $tquery = '//xml/configure/node';
  $tnode = $txpath -> query($tquery) -> item(0) -> nodeValue;
  $tquery = '//xml/configure/field';
  $tfield = $txpath -> query($tquery) -> item(0) -> nodeValue;
  $tquery = '//xml/configure/base';
  $tbase = $txpath -> query($tquery) -> item(0) -> nodeValue;
  $tdp_node_ary = explode(',', $tfield);
  $tdp_node = $tdp_node_ary[0];
  $tquery = '//xml/' . $tbase . '/' . $tnode ;
  $tquery = '//xml/' . $tbase . '/' . $tnode . '[' . $tdp_node . '=\'' . $tdelnode . '\']';
  $trests = @$txpath -> query($tquery);
  if ($trests)
  {
    $tremoveNode = $trests -> item(0);
    $tparentNode = $tremoveNode -> parentNode;
    $tparentNode -> removeChild($tremoveNode);
    $tdoc -> save($trootstr);
    wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
}


function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
    case 'add':
      wdja_cms_admin_manage_adddisp();
      break;
    case 'edit':
      wdja_cms_admin_manage_editdisp();
      break;
    case 'delete':
      wdja_cms_admin_manage_deletedisp();
      break;
  }
}

function wdja_cms_admin_manage_edit()
{
  $txml = $_GET['xml'];
  $titem = $_GET['item'];
  if (ii_isnull($txml)) $txml = '.tpl.module';
  $trootstr = pp_get_template_root($txml) . XML_SFX;
  if (file_exists($trootstr))
  {
    $tmpstr = ii_ireplace('manage.template', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@xml_recurrence_ida}');
    $delete_notice = ii_itake('global.lng_public.delete_notice', 'lng');
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
    $t = 1;
    foreach ($trests as $trest)
    {
      $tnodelength = $trest -> childNodes -> length;
      for ($i = 0; $i <= $tlength; $i += 1)
      {
        $ti = $i * 2 + 1;
        if ($ti < $tnodelength)
        {
          $trows = 5;
          if ($ti == 1)
          {
            $trows = 1;
            $tdisplay = '';
            $tname = $trest -> childNodes -> item($ti) -> nodeValue;
            $torder .= $tname . ',';
            if (!ii_isnull($titem) && $titem != $tname) break;
            if (ii_isnull($titem) && $t >1) break;
          }
          if (ii_isnull($titem)) $titem = $trest -> childNodes -> item(1) -> nodeValue;
          $tmptstr = $tmpastr;
          if ($trest -> childNodes -> item($ti) -> nodeName == 'tpl_default' || $trest -> childNodes -> item($ti) -> nodeName == 'chinese') {
          $tdisplay = '';
          $tmptstr = str_replace('{$rows}', $trows, $tmptstr);
          $tmptstr = str_replace('{$disinfo}', ii_htmlencode($trest -> childNodes -> item($ti) -> nodeName), $tmptstr);
          $tmptstr = str_replace('{$item}', $tname, $tmptstr);
          $tmptstr = str_replace('{$name}', $tname, $tmptstr);
          $tmptstr = str_replace('{$namestr}', urlencode($tname), $tmptstr);
          $tmptstr = str_replace('{$value}', ii_htmlencode($trest -> childNodes -> item($ti) -> nodeValue), $tmptstr);
          $tmptstr = str_replace('{$delete_notice}', ii_encode_scripts(str_replace('[]', '[' . $tname . ']', $delete_notice)), $tmptstr);
          $tmprstr = $tmprstr . $tmptstr;
          }
        }
        else continue;
      }
      $t++;
    }
    $torder = rtrim($torder, ',');
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = str_replace('{$node}', $tnode, $tmpstr);
    $tmpstr = str_replace('{$item}', $titem, $tmpstr);
    $tmpstr = str_replace('{$field}', $tfield, $tmpstr);
    $tmpstr = str_replace('{$base}', $tbase, $tmpstr);
    $tmpstr = str_replace('{$burl}', $trootstr, $tmpstr);
    $tmpstr = str_replace('{$file_url}', str_replace('./','',str_replace('../','',$trootstr)), $tmpstr);
    $tmpstr = str_replace('{$item_option}', pp_get_template_node($torder), $tmpstr);
    $tmpstr = str_replace('{$order}', $torder, $tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('manage.notexists', 'lng'), -1);
}

function wdja_cms_admin_manage_add()
{
  $tbackurl = $_GET['backurl'];
  if (strpos($tbackurl,'&item=')) $tbackurl_array = explode('&', $tbackurl);
   if (is_array($tbackurl_array))
    {
     $tbackurl = '';
     foreach($tbackurl_array as $k => $v) {
         if (strpos($v,'item=') !== false) continue;
         if ($k == 0) $tbackurl .= $v;
         else $tbackurl .= '&'.$v;
     }
    }
  $xmlconfig_burl = $_GET['burl'];
  $tmpstr = ii_itake('manage.add', 'tpl');
  $tmpstr = str_replace('{$xmlconfig_burl}', $xmlconfig_burl, $tmpstr);
  $tmpstr = str_replace('{$backurl}', urlencode($tbackurl), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'add':
      return wdja_cms_admin_manage_add();
      break;
    case 'edit':
      return wdja_cms_admin_manage_edit();
      break;
    default:
      return wdja_cms_admin_manage_edit();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>