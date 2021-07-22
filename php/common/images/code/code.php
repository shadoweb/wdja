<?php
class CCheckCodeFile
{
  private $mCheckCodeNum  = 4;
  private $mCheckCode   = '';
  private $mCheckImage  = '';
  private $mDisturbColor  = '';
  private $mCheckImageWidth = '66';
  private $mCheckImageHeight  = '20';

  private function OutFileHeader()
  {
    header ("Content-type: image/png");
  }

  private function CreateCheckCode()
  {
    $this -> mCheckCode = strtoupper(substr(md5(rand()), 0, $this -> mCheckCodeNum));
    session_start();
    //session_register('valcode');
    $_SESSION['valcode'] = ($this -> mCheckCode);
    return $this -> mCheckCode;
  }

  private function CreateImage()
  {
    $this -> mCheckImage = @imagecreate ($this -> mCheckImageWidth, $this -> mCheckImageHeight);
    imagecolorallocate ($this -> mCheckImage, 255, 255, 255);
    return $this -> mCheckImage;
  }

  private function SetDisturbColor()
  {
    for ($i=0; $i <= 128; $i ++)
    {
      $this -> mDisturbColor = imagecolorallocate ($this -> mCheckImage, rand(0, 255), rand(0, 255), rand(0, 255));
      imagesetpixel($this -> mCheckImage, rand(2, 128), rand(2, 38), $this -> mDisturbColor);
    }
  }

  public function SetCheckImageWH($width, $height)
  {
    if ($width == '' || $height == '') return false;
    $this -> mCheckImageWidth  = $width;
    $this -> mCheckImageHeight = $height;
    return true;
  }

  private function WriteCheckCodeToImage()
  {
    for ($i = 0; $i < $this -> mCheckCodeNum; $i ++)
    {
      $bg_color = imagecolorallocate ($this -> mCheckImage, rand(0, 255), rand(0, 128), rand(0, 255));
      $x = floor($this -> mCheckImageWidth / $this -> mCheckCodeNum) * $i + 5;
      $y = rand(0, $this -> mCheckImageHeight - 15);
      imagechar ($this -> mCheckImage, 6, $x, $y, $this -> mCheckCode[$i], $bg_color);
    }
  }

  public function OutCheckImage()
  {
    $this -> OutFileHeader();
    $this -> CreateCheckCode();
    $this -> CreateImage();
    $this -> SetDisturbColor();
    $this -> WriteCheckCodeToImage();
    imagepng($this -> mCheckImage);
    imagedestroy($this -> mCheckImage);
  }
}

if (!defined('E_DEPRECATED')) error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
else error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
$c_check_code_image = new CCheckCodeFile();
$c_check_code_image -> SetCheckImageWH(66, 20);
$c_check_code_image -> OutCheckImage();
?>