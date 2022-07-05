<?php
function api_label_name($id)
{
  return api_label_field($id,'topic');
}

function api_label_content($id)
{
  $tcontent = api_label_field($id,'content');
  $ttype = api_label_field($id,'type');
  $tinputs_type = api_label_field($id,'inputs_type');
  switch($ttype)
  {
    case '0':
      $tcontent = $tcontent;
      break;
    case '1':
      $tcontent = '/expansion/label/'.$tcontent;
      break;
    case '2':
      $tcontent = $tcontent;
      break;
    case '3':
      $tcontent = api_label_content_inputs($tcontent,$id,$tinputs_type);
      break;
    case '4':
      $tcontent = api_label_content_atts($tcontent,$id);
      break;
    case '5':
      $tcontent = str_replace('src="common/','src="/expansion/label/common/',$tcontent);
      $tcontent = mm_encode_content($tcontent);
      break;
    default:
      $tcontent = $tcontent;
      break;
  }
  
return $tcontent;
  
}

function api_label_content_inputs($content,$id,$type)
{
    if ($type == 'link') $tmpstr = ii_itake('global.expansion/label:api.inputs_link', 'tpl');
    else $tmpstr = ii_itake('global.expansion/label:api.inputs_text', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    if (!ii_isnull($content))
    {
      $ticount = 1;
      $tinfosary = explode('{|||}', $content);
      $tinfoscount = count($tinfosary);
      for ($i = 1; $i <= $tinfoscount; $i ++)
      {
        $tinfostr = $tinfosary[$i - 1];
        if (!ii_isnull($tinfostr))
        {
          $tinfostrary = explode('{:::}', $tinfostr);
          if (count($tinfostrary) == 2)
          {
            $tmptstr = str_replace('{$key}', $tinfostrary[0], $tmpastr);
            $tmptstr = str_replace('{$val}', $tinfostrary[1], $tmptstr);
            $tmptstr = str_replace('{$i}', $ticount, $tmptstr);
            $ticount += 1;
            $tmptstr = ii_creplace($tmptstr);
            $tmprstr .= $tmptstr;
          }
        }
      }
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = str_replace('{$id}', $id, $tmpstr);
    return $tmpstr;
}

function api_label_content_atts($content,$id)
{
  $timages_tpl = api_label_field($id,'images_tpl');
  if (!ii_isnull($timages_tpl)) $tmpstr = ii_itake('global.tpl_transfer.' . $timages_tpl, 'tpl');
  else $tmpstr = ii_itake('global.expansion/label:api.images', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@}');
  $tmprstr = '';
    if (!ii_isnull($content))
    {
        $tstrary = explode('|', $content);
        for ($ti = 0; $ti < count($tstrary); $ti ++)
        //for ($ti = count($tstrary) - 1; $ti > -1; $ti --)//倒序
        {
        $sary = explode('#:#', $tstrary[$ti]);
        $tmptstr = str_replace('{$title}', $sary[0], $tmpastr);
        $tmptstr = str_replace('{$desc}', $sary[1], $tmptstr);
        $tmptstr = str_replace('{$url}', '/expansion/label/'.$sary[2], $tmptstr);
        $tmptstr = str_replace('{$i}', $ti, $tmptstr);
        $tmptstr = ii_creplace($tmptstr);
        $tmprstr .= $tmptstr;
        }
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = str_replace('{$id}', $id, $tmpstr);
    return $tmpstr;
}

function api_label_field($id,$field)
{
  global $conn;
  $fgenre = 'expansion/label';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tsqlstr = "select * from $fdatabase where $fidfield=$id and " .ii_cfnames($ffpre,'hidden')."=0";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $tvalue = $trs[ii_cfnames($ffpre,$field)];
  return $tvalue;
}