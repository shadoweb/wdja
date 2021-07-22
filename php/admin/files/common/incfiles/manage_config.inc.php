<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
$nshow_path_str = stripslashes(ii_cstr($_GET['show_path']));
$nshow_path = iconv(CHARSET, 'cp936', $nshow_path_str);
if (ii_isnull($nshow_path) || !is_dir($nshow_path))
{
  $nshow_path = ii_get_actual_route('./');
  $nshow_path_str = ii_get_actual_route('./');
}
else
{
  if (ii_right($nshow_path, 1) != '/') $nshow_path .= '/';
  if (ii_right($nshow_path_str, 1) != '/') $nshow_path_str .= '/';
}

function pp_manage_navigation()
{
  $tmpstr = ii_ireplace('manage.navigation', 'tpl');
  return $tmpstr;
}

function wdja_cms_admin_manage_add_folderdisp()
{
  $tbackurl = $_GET['backurl'];
  $tfolder_name = stripslashes(ii_cstr($_POST['folder_name']));
  $tfolder_path = stripslashes(ii_cstr($_POST['folder_path'])).$tfolder_name;
  $tfolder_path = iconv(CHARSET, 'cp936', $tfolder_path);
  if (!is_dir($tfolder_path))
  {
    ii_mkdir($tfolder_path);
    wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_edit_folderdisp()
{
  $tbackurl = $_GET['backurl'];
  $tfolder_patha = stripslashes(ii_cstr($_POST['folder_patha']));
  $tfolder_patha = iconv(CHARSET, 'cp936', $tfolder_patha);
  $tfolder_pathb = stripslashes(ii_cstr($_POST['folder_pathb1']).ii_cstr($_POST['folder_pathb2']));
  $tfolder_pathb = iconv(CHARSET, 'cp936', $tfolder_pathb);
  $tbackurl2 = '?type=show_files&show_path='.urlencode(rtrim(ii_cstr($_POST['folder_pathb1']),'/'));
  if (!is_dir($tfolder_pathb))
  {
    if (rename($tfolder_patha, $tfolder_pathb)) wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl2 , 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_delete_folderdisp()
{
  $tbackurl = $_GET['backurl'];
  $tfolder_path = stripslashes(ii_cstr($_GET['folder_path']));
  $tfolder_path = iconv(CHARSET, 'cp936', $tfolder_path);
  @ii_deldir($tfolder_path);
  mm_client_redirect($tbackurl);
}

function wdja_cms_admin_manage_add_filedisp()
{
  $tbackurl = $_GET['backurl'];
  $tfile_name = stripslashes(ii_cstr($_POST['file_name']));
  $tfile_path = stripslashes(ii_cstr($_POST['file_path'])).$tfile_name;
  $tfile_path = iconv(CHARSET, 'cp936', $tfile_path);
  $tfiletext = stripslashes(ii_cstr($_POST['filetext']));
  if (file_put_contents($tfile_path, $tfiletext) >= 0) wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
  else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_edit_filedisp()
{
  $tbackurl = $_GET['backurl'];
  $tfile_path = stripslashes(ii_cstr($_POST['file_path']));
  $tfile_path = iconv(CHARSET, 'cp936', $tfile_path);
  $tfiletext = stripslashes(ii_cstr($_POST['filetext']));
  if (file_put_contents($tfile_path, $tfiletext)) wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
  else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_delete_filedisp()
{
  $tbackurl = $_GET['backurl'];
  $tfile_path = stripslashes(ii_cstr($_GET['file_path']));
  $tfile_path = iconv(CHARSET, 'cp936', $tfile_path);
  @unlink($tfile_path);
  mm_client_redirect($tbackurl);
}

function wdja_cms_admin_manage_count_foldersizedisp()
{
  $tfloder = stripslashes(ii_cstr($_GET['folder']));
  $tfloder = iconv(CHARSET, 'cp936', $tfloder);
  if (is_dir($tfloder)) $tsize = ii_get_dirsize($tfloder);
  else $tsize = 0;
  $tsize = ii_csize($tsize);
  echo $tsize;
  exit();
}

function wdja_cms_admin_manage_uploaddisp()
{
  $tbackurl = $_GET['backurl'];
  $tpath = stripslashes(ii_cstr($_GET['path']));
  $tpath = iconv('cp936', CHARSET, realpath($tpath));
  if (!ii_isnull($tpath))
  {
    if (ii_left($tpath, 1) == '/')
    {
      if (ii_right($tpath, 1) != '/') $tpath .= '/';
    }
    else
    {
      if (ii_right($tpath, 1) != '\\') $tpath .= '\\';
    }
    $tfilesize = ii_get_num($_FILES['file1']['size']);
    if ($tfilesize > 0)
    {
      $tfilename = $_FILES['file1']['name'];
      $tfilename = iconv(CHARSET, 'cp936', $tpath . $tfilename);
      $tmp_filename = $_FILES['file1']['tmp_name'];
      if (move_uploaded_file($tmp_filename, $tfilename)) mm_client_redirect($tbackurl);
      else wdja_cms_admin_msg(ii_itake('global.lng_public.failed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_action()
{
  switch($_GET['action'])
  {
    case 'add_folder':
      wdja_cms_admin_manage_add_folderdisp();
      break;
    case 'edit_folder':
      wdja_cms_admin_manage_edit_folderdisp();
      break;
    case 'delete_folder':
      wdja_cms_admin_manage_delete_folderdisp();
      break;
    case 'add_file':
      wdja_cms_admin_manage_add_filedisp();
      break;
    case 'edit_file':
      wdja_cms_admin_manage_edit_filedisp();
      break;
    case 'delete_file':
      wdja_cms_admin_manage_delete_filedisp();
      break;
    case 'count_foldersize':
      wdja_cms_admin_manage_count_foldersizedisp();
      break;
    case 'upload':
      wdja_cms_admin_manage_uploaddisp();
      break;
  }
}

function wdja_cms_admin_manage_add_folder()
{
  global $nshow_path, $nshow_path_str;
  $nshow_path = str_replace('../.././','/',$nshow_path);
  $tmpstr = ii_ireplace('manage.add_folder', 'tpl');
  $tmpstr = str_replace('{$path}', get_npath_url(ii_htmlencode(iconv('cp936', CHARSET, $nshow_path))), $tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_edit_folder()
{
  global $nshow_path, $nshow_path_str;
  $nfolder_path = stripslashes(ii_cstr($_GET['folder_path']));
  $nfolder_path = str_replace('../.././','/',$nfolder_path);
  $nshow_path = ii_get_lrstr(rtrim($nfolder_path, '/'), '/', 'leftr');
  $nfolder_name = str_replace('/','',str_replace($nshow_path,'',$nfolder_path));
  $tmpstr = ii_ireplace('manage.edit_folder', 'tpl');
  $tmpstr = str_replace('{$path}', get_npath_url(ii_htmlencode(iconv('cp936', CHARSET, $nshow_path))), $tmpstr);//当前文件夹上级文件夹路径
  $tmpstr = str_replace('{$folder_pathb1}', '../../.'.$nshow_path.'/', $tmpstr);
  $tmpstr = str_replace('{$folder_pathb2}', $nfolder_name, $tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_add_file()
{
  global $nshow_path, $nshow_path_str;
  $nshow_path = str_replace('../.././','/',$nshow_path);
  $tmpstr = ii_ireplace('manage.add_file', 'tpl');
  $tmpstr = str_replace('{$path}', get_npath_url(ii_htmlencode(iconv('cp936', CHARSET, $nshow_path))), $tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_edit_file()
{
  $tmpedittype = '.asp.aspx.css.cfm.htm.html.ini.inc.wdja.jsp.jspa.js.jtml.php.phtml.shtml.txt.vbs.xml.xsl.xslt';
  $tmptypestr = stripslashes(ii_cstr($_GET['file_path']));
  $nshow_path = str_replace('../.././','/',$tmptypestr);
  $tmptypestr = ii_get_lrstr($tmptypestr, '.', 'right');
  if (!ii_cinstr($tmpedittype, $tmptypestr, '.')) mm_client_alert(ii_itake('manage.cannot', 'lng'), -1);
  $tmpstr = ii_ireplace('manage.edit_file', 'tpl');
  $tmpstr = str_replace('{$path}', get_npath_url(ii_htmlencode(iconv('cp936', CHARSET, $nshow_path))), $tmpstr);
  return $tmpstr;
}

function get_npath_url($npath) {
   $tftype = '.asp.aspx.css.cfm.htm.html.ini.inc.wdja.jsp.jspa.js.jtml.php.phtml.shtml.txt.vbs.xml.xsl.xslt';
	$res = '<a href="?type=show_files&show_path=../.././">/</a>';
	$v = '';
	$npath_array = Array();
	$npath_array = explode('/',$npath);
	foreach ($npath_array as $tkey => $tval) {
		if (!ii_isnull($tval)) {
		 $nval = $v.$tval;
         $tfstr = ii_get_lrstr($tval, '.', 'right');
         if (ii_cinstr($tftype, $tfstr, '.') && strpos($tval,'.') !== false) $res .= '<a href="?type=edit_file&file_path=../.././'.urlencode($nval).'">'.$tval.'</a>';
			 else $res .= '<a href="?type=show_files&show_path=../.././'.urlencode($nval).'">'.$tval.'/</a>';
			 $v .= $tval.'/';
		}
	}
	return $res;
}

function wdja_cms_admin_manage_list()
{
  global $nshow_path, $nshow_path_str;
  $tfloders = Array();
  $tfiles = Array();
  $twebdir = dir($nshow_path);
  while($tentry = $twebdir -> read())
  {
    if (is_file($nshow_path . $tentry)) $tfiles[iconv('cp936', CHARSET, $tentry)] = array(filesize($nshow_path . $tentry), filemtime($nshow_path . $tentry));
    else if (!(is_numeric(strpos($tentry, '.')))) $tfloders[iconv('cp936', CHARSET, $tentry)] = iconv('cp936', CHARSET, $tentry);
  }
  $twebdir -> close();
  $npath = $nshow_path;
  $nshow_path = str_replace('../.././','/',$nshow_path);
  $tmpstr = ii_ireplace('manage.list', 'tpl');
  $tmpstr = str_replace('{$path}', get_npath_url(ii_htmlencode(iconv('cp936', CHARSET, $nshow_path))), $tmpstr);
  $tmpstr = str_replace('{$npath}', $npath, $tmpstr);
  $tmpstr = str_replace('{$foldercount}', count($tfloders), $tmpstr);
  $tmpstr = str_replace('{$filescount}', count($tfiles), $tmpstr);
  $tmprstr = '';
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  foreach ($tfloders as $key => $val)
  {
    $tmptstr = str_replace('{$nfoldername}', $key, $tmpastr);
    $tmptstr = str_replace('{$nfoldernamestr}', ii_encode_scripts($key), $tmptstr);
    $tmptstr = str_replace('{$nfolderpath}', urlencode($nshow_path_str . $key), $tmptstr);
    $tmptstr = str_replace('{$nfolderpaths}', $nshow_path_str . $key, $tmptstr);
    $tmprstr .= $tmptstr;
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmprstr = '';
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idb}');
  foreach ($tfiles as $key => $val)
  {
    $tmptstr = str_replace('{$nfilename}', $key, $tmpastr);
    $tmptstr = str_replace('{$nfilenamestr}', ii_encode_scripts($key), $tmptstr);
    $tmptstr = str_replace('{$nftype}', ii_fileico($key), $tmptstr);
    $tmptstr = str_replace('{$nfiletime}', date('Y-m-d G:i:s', $val[1]), $tmptstr);
    $tmptstr = str_replace('{$nfilesize}', ii_csize($val[0]), $tmptstr);
    $tmptstr = str_replace('{$nfilepath}', urlencode($nshow_path_str . $key), $tmptstr);
    $tmptstr = str_replace('{$nfilepaths}', $nshow_path_str . $key, $tmptstr);
    $tmprstr .= $tmptstr;
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'add_folder':
      return wdja_cms_admin_manage_add_folder();
      break;
    case 'edit_folder':
      return wdja_cms_admin_manage_edit_folder();
      break;
    case 'add_file':
      return wdja_cms_admin_manage_add_file();
      break;
    case 'edit_file':
      return wdja_cms_admin_manage_edit_file();
      break;
    case 'list':
      return wdja_cms_admin_manage_list();
      break;
    default:
      return wdja_cms_admin_manage_list();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>