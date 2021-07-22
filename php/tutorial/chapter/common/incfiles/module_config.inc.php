<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************

function wdja_cms_module_list()
{
  //不展示篇章列表页,直接跳转至教程模块首页
  $tgourl = ii_get_actual_route('tutorial');
  if (!ii_isnull($tgourl)) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location:$tgourl");
    exit;
  }
}

function wdja_cms_module_detail()
{
  //不展示篇章内容页,直接跳转至教程模块首页
  $tgourl = ii_get_actual_route('tutorial');
  if (!ii_isnull($tgourl)) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location:$tgourl");
    exit;
  }
}

function wdja_cms_module_index()
{
  //不展示篇章首页,直接跳转至教程模块首页
  $tgourl = ii_get_actual_route('tutorial');
  if (!ii_isnull($tgourl)) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location:$tgourl");
    exit;
  }
}

function wdja_cms_module()
{
  switch($_GET['type'])
  {
    case 'list':
      return wdja_cms_module_list();
      break;
    case 'api':
      return wdja_cms_module_api();
      break;
    case 'detail':
      return wdja_cms_module_detail();
      break;
    case 'index':
      return wdja_cms_module_index();
      break;
    default:
      return wdja_cms_module_index();
      break;
  }
}

//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>