<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
//小程序使用接口,未作处理,不可使用,需后续开发.
require('common/incfiles/autoload.php');
wdja();

function wdja_cms_api() {
  $appid = ii_itake('global.support/global:wechat.appid','lng');
  if ($appid != $_GET['appid']) {
  echo "404";
  exit();
  }
}

function wdja_cms_module_api() {
    $tmpstr = array();
    $res = array();
    $tmpstr["slide"] = json_decode(wdja_cms_page_api('support/slide'), JSON_UNESCAPED_UNICODE);
    $tmpstr["product"] = json_decode(wdja_cms_list_api('product',4), JSON_UNESCAPED_UNICODE);
    $tmpstr["news"] = json_decode(wdja_cms_list_api('news',6), JSON_UNESCAPED_UNICODE);
    array_push($res, $tmpstr);
    $res = json_encode($res,JSON_UNESCAPED_UNICODE);
    echo $res;
}

function wdja() {
  wdja_cms_api();
  $module = $_GET['module'];
  $id = $_GET['id'];
  $mobile = $_GET['mobile'];
  $idnum = $_GET['idnum'];
  switch($_GET['type'])
  {
    case 'wxlogin':
      echo wdja_cms_wxlogin_api();
      break;
    case 'wxlogin_code':
      echo wdja_cms_wxlogin_code_api();
      break;
    case 'search_list':
      echo wdja_cms_search_list_api($module);
      break;
    case 'search_detail':
      echo wdja_cms_search_detail_api($module,array('mobile'=>$mobile,'idnum'=>$idnum));
      break;
    case 'sort':
      echo wdja_cms_sort_api($module);
      break;
    case 'list':
      echo wdja_cms_list_api($module);
      break;
    case 'detail':
      echo wdja_cms_detail_api($module,$id);
      break;
    case 'page':
      echo wdja_cms_page_api($module);
      break;
    case 'singlepage':
      echo wdja_cms_singlepage_api($module);
      break;
    case 'form':
      echo wdja_cms_form_api();
      break;
    default:
      echo wdja_cms_module_api();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>