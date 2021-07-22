<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_module_index()
{
  mm_cntitle('');
  $tmpstr = ii_ireplace('module.index', 'tpl');
  return $tmpstr;
}

function wdja_cms_module()
{
    return wdja_cms_module_index();
}

//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>