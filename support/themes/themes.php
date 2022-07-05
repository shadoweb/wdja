<?php
themes_init();
function themes_init(){
  global $theme_guide;
  $theme_guide = Array();
  $theme_url = 'support/themes/'.DEFAULT_SKIN.':.././support/themes/'.DEFAULT_SKIN.'/manage.php';
  $theme_title = '主题配置';
  $theme_guide[$theme_url] = $theme_title;
  return $theme_guide;
}