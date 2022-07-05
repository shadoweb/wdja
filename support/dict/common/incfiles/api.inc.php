<?php

function api_dict_add($did=0)
{
  $tmpstr = ii_itake('global.support/dict:api.api_dict_add', 'tpl');
  $tmpstr = str_replace('{$did}', ii_get_num($did), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_dict_edit($did=0,$id=0)
{
  $tmpstr = ii_itake('global.support/dict:api.api_dict_edit', 'tpl');
  $tmpstr = str_replace('{$did}', ii_get_num($did), $tmpstr);
  $tmpstr = str_replace('{$id}', ii_get_num($id), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

?>