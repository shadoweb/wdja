<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function pp_get_xml_root()
{
  global $ngenre;
  $tmpstr = ii_get_actual_route($ngenre);
  if (ii_right($tmpstr, 1) != '/') $tmpstr .= '/';
  $tmproot = 'common/language/';
  $tmpstr = $tmpstr . $tmproot . 'module';
  return $tmpstr;
}

function wdja_cms_module_detail()
{
  global $conn;
  global $ngenre,$ndatabase, $nidfield, $nfpre;
  global $nvalidate;
  $trootstr = pp_get_xml_root() . XML_SFX;
  if (file_exists($trootstr))
  {
    $tmpstr = ii_itake('module.detail' , 'tpl');
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
          if (ii_isnull($GLOBALS['RS_' . $k])) $GLOBALS['RS_' . $k] = $nodeValue;
          $tmpstr = str_replace('{$'.$k.'}', ii_htmlencode($nodeValue), $tmpstr);
          if ($k == 'topic') mm_cntitle(ii_htmlencode(ii_htmlencode($nodeValue)));
          if ($k == 'titles' && !ii_isnull($nodeValue)) mm_cntitle(ii_htmlencode(ii_htmlencode($nodeValue)));
          if ($k == 'keywords') mm_cnkeywords(ii_htmlencode(ii_htmlencode($nodeValue)));
          if ($k == 'description') mm_cndescription(ii_htmlencode(ii_htmlencode($nodeValue)));
        }
      }
    }
    $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
    $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('manage.notexists', 'lng'), -1);
}

function wdja_cms_module()
{
  switch($_GET['type'])
  {
    case 'detail':
      return wdja_cms_module_detail();
      break;
    default:
      return wdja_cms_module_detail();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>