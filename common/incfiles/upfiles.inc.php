<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************

function uu_upload_create_thumbnail($strURL1, $strURL2, $strScale = 0)
{
  //生成缩略图函数.来自缩略图插件
  //使用方法 mm_resizeImage($tfilename,$stfilename,1);//生成缩略图
  global $variable;
  $tstrURL1 = $strURL1;
  $tstrURL2 = $strURL2;
  $strWidth = $variable['common.thumbnail.width'];
  $strHeight = $variable['common.thumbnail.height'];
  $tstrScale = ii_get_num($strScale, 0);
  $tstrWidth = ii_get_num($strWidth, 0);
  $tstrHeight = ii_get_num($strHeight, 0);
  if (!ii_isnull($tstrURL1) && !ii_isnull($tstrURL2) && $tstrWidth != 0 && $tstrHeight != 0)
  {
    $tImageType = ii_get_lrstr($tstrURL1, '.', 'right');
    if ($tImageType == 'jpg' || $tImageType == 'jpeg') $timg = ImageCreateFromJpeg($tstrURL1);
    elseif ($tImageType == 'gif') $timg = ImageCreateFromGif ($tstrURL1);
    elseif ($tImageType == 'png') $timg = ImageCreateFromPng($tstrURL1);
    if ($timg && function_exists('imagecopyresampled'))
    {
      $tImageSize = getImageSize($tstrURL1);
      $tImageWidth = $tImageSize[0];
      $tImageHeight = $tImageSize[1];
      if ($tstrWidth == -1) $tstrWidth = $tImageWidth;
      if ($tstrHeight == -1) $tstrHeight = $tImageHeight;
      if ($tstrScale == 1)
      {
        if ($tImageWidth <= $tstrWidth && $tImageHeight <= $tstrHeight)
        {
          $tstrWidth = $tImageWidth;
          $tstrHeight = $tImageHeight;
        }
        else
        {
          $tScNum1 = $tImageWidth / $tstrWidth;
          $tScNum2 = $tImageHeight / $tstrHeight;
          if ($tImageWidth <= $tstrWidth) $tstrWidth = $tImageWidth / $tScNum2;
          elseif ($tImageHeight <= $tstrHeight) $tstrHeight = $tImageHeight / $tScNum1;
          else
          {
            if ($tScNum1 >= $tScNum2) $tstrHeight = $tImageHeight / $tScNum1;
            else $tstrWidth = $tImageWidth / $tScNum2;
          }
        }
      }
      $timgs = imagecreatetruecolor($tstrWidth, $tstrHeight);
      imagecopyresampled($timgs, $timg, 0, 0, 0, 0, $tstrWidth, $tstrHeight, $tImageWidth, $tImageHeight);
      if ($tImageType == 'jpg' || $tImageType == 'jpeg') imagejpeg ($timgs, $strURL2, 60);//60为压缩质量.默认75,数值0-100imagejpeg() 独有参数
      elseif ($tImageType == 'gif') imagegif ($timgs, $strURL2);
      elseif ($tImageType == 'png') imagepng ($timgs, $strURL2);
      imagedestroy($timg);
    }
  }
}

function uu_get_upload_user()
{
  global $nusername, $admc_username;
  $tusername = $nusername;
  if (ii_isnull($tusername)) $tusername = $admc_username;
  if (ii_isnull($tusername)) $tusername = 'null';
  return $tusername;
}

function uu_get_upload_filename($filetype)
{
  global $upbasefname;
  if (ii_isnull($upbasefname)) $tfilename =  ii_format_date(ii_now(), 0).ii_random(2) . '.' . $filetype;
  else $tfilename = $upbasefname . ii_format_date(ii_now(), 0).ii_random(2) . '.' . $filetype;
  return $tfilename;
}

function uu_get_upload_foldername()
{
  global $upbasefolder;
  if (ii_isnull($upbasefolder)) $tfoldername = ii_format_date(ii_now(), 2);
  else $tfoldername = $upbasefolder . '/' . ii_format_date(ii_now(), 2);
  return $tfoldername;
}

function uu_upload_create_database_note($genre, $filename, $field)
{
  global $conn,$slng;
  global $variable;
  global $nupident;
  $tdatabase = $variable['common.upload.ndatabase'];
  $tidfield = $variable['common.upload.nidfield'];
  $tfpre = $variable['common.upload.nfpre'];
  $tgenre = ii_left($genre, 50);
  $tfilename = ii_left($filename, 250);
  $tfield = ii_left($field, 250);
  $tuser = uu_get_upload_user();
  $tsqlstr = "insert into $tdatabase (" . ii_cfnames($tfpre, 'genre') . "," . ii_cfnames($tfpre, 'upident') . "," . ii_cfnames($tfpre, 'filename') . "," . ii_cfnames($tfpre, 'field') . "," . ii_cfnames($tfpre, 'user') . "," . ii_cfnames($tfpre, 'lng') . "," . ii_cfnames($tfpre, 'time') . ") values ('$tgenre','$nupident','$tfilename','$tfield','$tuser','$slng','" . ii_now() . "')";
  return ii_conn_query($tsqlstr, $conn);
}

function uu_upload_update_database_note($genre, $filename, $field, $id)
{
  global $conn;
  global $variable;
  global $nupident;
  $tdatabase = $variable['common.upload.ndatabase'];
  $tidfield = $variable['common.upload.nidfield'];
  $tfpre = $variable['common.upload.nfpre'];
  $tgenre = ii_left($genre, 50);
  $tfilename = ii_left($filename, 10000);
  $tfield = ii_left($field, 250);
  $tid = ii_get_num($id);
  if (ii_isnull($tfield)) return;
  $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, 'valid') . "=0," . ii_cfnames($tfpre, 'voidreason') . "=2 where " . ii_cfnames($tfpre, 'fid') . "=$tid and " . ii_cfnames($tfpre, 'genre') . "='$tgenre' and " . ii_cfnames($tfpre, 'upident') . "='$nupident' and " . ii_cfnames($tfpre, 'field') . "='$tfield'";
  ii_conn_query($tsqlstr, $conn);
  $tary = explode('|', $tfilename);
  foreach($tary as $key => $val)
  {
    if (!ii_isnull($val))
    {
      $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, 'fid') . "=$tid," . ii_cfnames($tfpre, 'valid') . "=1 where " . ii_cfnames($tfpre, 'genre') . "='$tgenre' and " . ii_cfnames($tfpre, 'upident') . "='$nupident' and " . ii_cfnames($tfpre, 'field') . "='$tfield ' and " . ii_cfnames($tfpre, 'filename') . "='$val'";
      ii_conn_query($tsqlstr, $conn);
    }
  }
}

function uu_upload_delete_database_note($genre, $idary)
{
  global $conn;
  global $variable;
  global $nupident;
  $tgenre = ii_left($genre, 50);
  $tidary = $idary;
  if (ii_cidary($tidary))
  {
    $tdatabase = $variable['common.upload.ndatabase'];
    $tidfield = $variable['common.upload.nidfield'];
    $tfpre = $variable['common.upload.nfpre'];
    $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, 'valid') . "=0," . ii_cfnames($tfpre, 'voidreason') . "=1 where " . ii_cfnames($tfpre, 'genre') . "='$tgenre' and " . ii_cfnames($tfpre, 'upident') . "='$nupident' and " . ii_cfnames($tfpre, 'fid') . " in ($tidary)";
    return ii_conn_query($tsqlstr, $conn);
  }
}

function uu_upload_init()
{
  global $variable;
  global $nupmaxsize, $upload_tpl_href, $upload_tpl_kong, $upload_tpl_back;
  global $upform, $uptext, $upfname, $upftype, $upsimg, $upbasefname, $upbasefolder;
  if (ii_get_num($nupmaxsize) == 0) $nupmaxsize = ii_get_num($variable['common.nupmaxsize'], 0);
  $upload_tpl_href = ii_itake('global.tpl_config.a_href_self', 'tpl');
  $upload_tpl_kong = ii_itake('global.tpl_config.html_kong', 'tpl');
  $upload_tpl_back = str_replace('{$explain}', ii_itake('global.lng_config.back', 'lng'), $upload_tpl_href);
  $upload_tpl_back = str_replace('{$value}', 'javascript:history.go(-1);', $upload_tpl_back);
  $upform = $_GET['upform'];
  $uptext = $_GET['uptext'];
  $upfname = $_GET['upfname'];
  $upftype = $_GET['upftype'];
  $upsimg = ii_get_num($_GET['upsimg'], 0);//缩略图开关
  $upbasefname = $_GET['upbasefname'];
  $upbasefolder = $_GET['upbasefolder'];
}

function uu_upload_msg($msg)
{
  mm_client_alert(ii_ireplace('global.lng_upfiles.file_' . $msg, 'lng'),-1);
}

function uu_upload_files()
{
  uu_upload_init();
  global $ngenre;
  global $nupmaxsize, $nuptype, $nuppath, $variable;
  global $upform, $uptext, $upftype, $upsimg;
  $doriginal = ii_get_num($variable['common.thumbnail.original']);//删除原图
  $sfile = $variable['common.thumbnail.file'];
  $tfilesize = ii_get_num($_FILES['file1']['size']);
  if ($tfilesize <= 0) uu_upload_msg('null');
  if ($tfilesize > $nupmaxsize) uu_upload_msg('max');
  $tfilename = $_FILES['file1']['name'];
  $tmp_filename = $_FILES['file1']['tmp_name'];
  if(OSS_SWITCH == 1){
      $tfiletype = strtolower(ii_get_filetype($tfilename));
      $tfilename = mm_oss_upload_File($tmp_filename,$tfiletype);
      mm_client_redirect('?type=upload&upform=' . $upform . '&uptext=' . $uptext . '&upftype=' . $upftype . '&upsimg=' . $upsimg . '&upfname='. $tfilename);//补全上传图片返回路径为带模块文件夹
  }else{
      $tfiletype = strtolower(ii_get_filetype($tfilename));
      if (ii_cinstr($nuptype, $tfiletype, '.'))
      {
        $tfilefolder = $nuppath . uu_get_upload_foldername();
        if (!(is_dir($tfilefolder))) ii_mkdir($tfilefolder);
        $nfilename = uu_get_upload_filename($tfiletype);
        $tfilename = $tfilefolder . '/' .$nfilename ; 
        if ($upsimg == 1)
        {
          $stfilefolder = $nuppath.$sfile.'/' . uu_get_upload_foldername();//缩略图文件夹
          if (!(is_dir($stfilefolder))) ii_mkdir($stfilefolder);//判断是否存在缩略图,不存在则创建
          $stfilename = $stfilefolder . '/' . $nfilename; 
        }
        if (move_uploaded_file($tmp_filename, $tfilename))
        {
          chmod($tfilename, 0755);
          if ($upsimg == 1 && ($tfiletype == 'jpg' || $tfiletype == 'png' || $tfiletype == 'gif' || $tfiletype == 'jpeg' || $tfiletype == 'bmp')) {
             uu_upload_create_thumbnail($tfilename,$stfilename,1);//生成缩略图
             if ($doriginal == '1') unlink($tfilename);//删除原图
             else uu_upload_create_database_note($ngenre, '/' .$ngenre .'/' .$tfilename, $uptext);
             uu_upload_create_database_note($ngenre, '/' .$ngenre .'/' .$stfilename, $uptext);
             mm_client_redirect('?type=upload&upform=' . $upform . '&uptext=' . $uptext . '&upftype=' . $upftype . '&upsimg=' . $upsimg . '&upfname=' .$stfilename);//补全上传图片返回路径为带模块缩略图文件夹
          }else{
            uu_upload_create_database_note($ngenre, '/' .$ngenre .'/' .$tfilename, $uptext);
            mm_client_redirect('?type=upload&upform=' . $upform . '&uptext=' . $uptext . '&upftype=' . $upftype . '&upsimg=' . $upsimg . '&upfname='. $tfilename);//补全上传图片返回路径为带模块文件夹
          }
        }
      else uu_upload_msg('sudd');
    }
    else uu_upload_msg('uptype');
  }
}

function uu_uploads_files()
{
  uu_upload_init();
  global $ngenre;
  global $nupmaxsize, $nuptype, $nuppath, $variable;
  global $upform, $uptext, $upftype, $upsimg;
  $doriginal = ii_get_num($variable['common.thumbnail.original']);//删除原图
  $sfile = $variable['common.thumbnail.file'];//
  $tfilesize = ii_get_num($_FILES['file1']['size']);
  if ($tfilesize <= 0) uu_upload_msg('null');
  if ($tfilesize > $nupmaxsize) uu_upload_msg('max');
  $tfilename = $_FILES['file1']['name'];
  $tmp_filename = $_FILES['file1']['tmp_name'];
  if(OSS_SWITCH == 1){
      $tfiletype = strtolower(ii_get_filetype($tfilename));
      $tfilename = mm_oss_upload_File($tmp_filename,$tfiletype);
      mm_client_redirect('?type=uploads&upform=' . $upform . '&uptext=' . $uptext . '&upftype=' . $upftype . '&upsimg=' . $upsimg . '&upfname='. $tfilename);//补全上传图片返回路径为带模块文件夹
  }else{
      $tfiletype = strtolower(ii_get_filetype($tfilename));
      if (ii_cinstr($nuptype, $tfiletype, '.'))
      {
        $tfilefolder = $nuppath . uu_get_upload_foldername();
        if (!(is_dir($tfilefolder))) ii_mkdir($tfilefolder);
        $nfilename = uu_get_upload_filename($tfiletype);
        $tfilename = $tfilefolder . '/' .$nfilename ; 
        if ($upsimg == 1)
        {
          $stfilefolder = $nuppath.$sfile.'/' . uu_get_upload_foldername();//缩略图文件夹
          if (!(is_dir($stfilefolder))) ii_mkdir($stfilefolder);//判断是否存在缩略图,不存在则创建
          $stfilename = $stfilefolder . '/' . $nfilename; 
        }
        if (move_uploaded_file($tmp_filename, $tfilename))
        {
          chmod($tfilename, 0755);
          if ($upsimg == 1 && ($tfiletype == 'jpg' || $tfiletype == 'png' || $tfiletype == 'gif' || $tfiletype == 'jpeg' || $tfiletype == 'bmp')) {
             uu_upload_create_thumbnail($tfilename,$stfilename,1);//生成缩略图
             if ($doriginal == '1') unlink($tfilename);//删除原图
             else uu_upload_create_database_note($ngenre, '/' .$ngenre .'/' .$tfilename, $uptext);
             uu_upload_create_database_note($ngenre, '/' .$ngenre .'/' .$stfilename, $uptext);
             mm_client_redirect('?type=uploads&upform=' . $upform . '&uptext=' . $uptext . '&upftype=' . $upftype . '&upsimg=' . $upsimg . '&upfname=' .$stfilename);//补全上传图片返回路径为带模块缩略图文件夹
          }else{
            uu_upload_create_database_note($ngenre, '/' .$ngenre .'/' .$tfilename, $uptext);
            mm_client_redirect('?type=uploads&upform=' . $upform . '&uptext=' . $uptext . '&upftype=' . $upftype . '&upsimg=' . $upsimg . '&upfname='. $tfilename);//补全上传图片返回路径为带模块文件夹
          }
        }
        else uu_upload_msg('sudd');
      }
    else uu_upload_msg('uptype');
  }
}

function uu_upload_files_html($strers)
{
  uu_upload_init();
  $tmpstr = ii_ireplace('global.tpl_upfiles.' . $strers, 'tpl');
  mm_clear_show($tmpstr);
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>