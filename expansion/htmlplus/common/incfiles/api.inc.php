<?php
/**
* 操纵文件类
* 来源:https://www.jb51.net/article/88478.htm
* 例子：
* FileUtil::createDir('a/1/2/3'); 测试建立文件夹 建一个a/1/2/3文件夹
* FileUtil::createFile('b/1/2/3'); 测试建立文件 在b/1/2/文件夹下面建一个3文件
* FileUtil::createFile('b/1/2/3.exe'); 测试建立文件 在b/1/2/文件夹下面建一个3.exe文件
* FileUtil::copyDir('b','d/e'); 测试复制文件夹 建立一个d/e文件夹，把b文件夹下的内容复制进去
* FileUtil::copyFile('b/1/2/3.exe','b/b/3.exe'); 测试复制文件 建立一个b/b文件夹，并把b/1/2文件夹中的3.exe文件复制进去
* FileUtil::moveDir('a/','b/c'); 测试移动文件夹 建立一个b/c文件夹,并把a文件夹下的内容移动进去，并删除a文件夹
* FileUtil::moveFile('b/1/2/3.exe','b/d/3.exe'); 测试移动文件 建立一个b/d文件夹，并把b/1/2中的3.exe移动进去 
* FileUtil::unlinkFile('b/d/3.exe'); 测试删除文件 删除b/d/3.exe文件
* FileUtil::unlinkDir('d'); 测试删除文件夹 删除d文件夹
*/
class FileUtil {
  /**
* 建立文件夹
*
* @param string $aimUrl
* @return viod
*/
  function createDir($aimUrl) {
    $aimUrl = str_replace('', '/', $aimUrl);
    $aimDir = '';
    $arr = explode('/', $aimUrl);
    $result = true;
    foreach ($arr as $str) {
      $aimDir .= $str . '/';
      if (!file_exists($aimDir)) {
        $result = mkdir($aimDir);
      }
    }
    return $result;
  }
  /**
* 建立文件
*
* @param string $aimUrl 
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
  function createFile($aimUrl, $overWrite = false) {
    if (file_exists($aimUrl) && $overWrite == false) {
      return false;
    } elseif (file_exists($aimUrl) && $overWrite == true) {
      FileUtil :: unlinkFile($aimUrl);
    }
    $aimDir = dirname($aimUrl);
    FileUtil :: createDir($aimDir);
    touch($aimUrl);
    return true;
  }
  /**
* 移动文件夹
*
* @param string $oldDir
* @param string $aimDir
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
  function moveDir($oldDir, $aimDir, $overWrite = false) {
    $aimDir = str_replace('', '/', $aimDir);
    $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
    $oldDir = str_replace('', '/', $oldDir);
    $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
    if (!is_dir($oldDir)) {
      return false;
    }
    if (!file_exists($aimDir)) {
      FileUtil :: createDir($aimDir);
    }
    @ $dirHandle = opendir($oldDir);
    if (!$dirHandle) {
      return false;
    }
    while (false !== ($file = readdir($dirHandle))) {
      if ($file == '.' || $file == '..') {
        continue;
      }
      if (!is_dir($oldDir . $file)) {
        FileUtil :: moveFile($oldDir . $file, $aimDir . $file, $overWrite);
      } else {
        FileUtil :: moveDir($oldDir . $file, $aimDir . $file, $overWrite);
      }
    }
    closedir($dirHandle);
    return rmdir($oldDir);
  }
  /**
* 移动文件
*
* @param string $fileUrl
* @param string $aimUrl
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
  function moveFile($fileUrl, $aimUrl, $overWrite = false) {
    if (!file_exists($fileUrl)) {
      return false;
    }
    if (file_exists($aimUrl) && $overWrite = false) {
      return false;
    } elseif (file_exists($aimUrl) && $overWrite = true) {
      FileUtil :: unlinkFile($aimUrl);
    }
    $aimDir = dirname($aimUrl);
    FileUtil :: createDir($aimDir);
    rename($fileUrl, $aimUrl);
    return true;
  }
  /**
* 删除文件夹
*
* @param string $aimDir
* @return boolean
*/
  function unlinkDir($aimDir) {
    $aimDir = str_replace('', '/', $aimDir);
    $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
    if (!is_dir($aimDir)) {
      return false;
    }
    $dirHandle = opendir($aimDir);
    while (false !== ($file = readdir($dirHandle))) {
      if ($file == '.' || $file == '..') {
        continue;
      }
      if (!is_dir($aimDir . $file)) {
        FileUtil :: unlinkFile($aimDir . $file);
      } else {
        FileUtil :: unlinkDir($aimDir . $file);
      }
    }
    closedir($dirHandle);
    return rmdir($aimDir);
  }
  /**
* 删除文件
*
* @param string $aimUrl
* @return boolean
*/
  function unlinkFile($aimUrl) {
    if (file_exists($aimUrl)) {
      unlink($aimUrl);
      return true;
    } else {
      return false;
    }
  }
  /**
* 复制文件夹
*
* @param string $oldDir
* @param string $aimDir
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
  function copyDir($oldDir, $aimDir, $overWrite = false) {
    $aimDir = str_replace('', '/', $aimDir);
    $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
    $oldDir = str_replace('', '/', $oldDir);
    $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
    if (!is_dir($oldDir)) {
      return false;
    }
    if (!file_exists($aimDir)) {
      FileUtil :: createDir($aimDir);
    }
    $dirHandle = opendir($oldDir);
    while (false !== ($file = readdir($dirHandle))) {
      if ($file == '.' || $file == '..') {
        continue;
      }
      if (!is_dir($oldDir . $file)) {
        FileUtil :: copyFile($oldDir . $file, $aimDir . $file, $overWrite);
      } else {
        FileUtil :: copyDir($oldDir . $file, $aimDir . $file, $overWrite);
      }
    }
    return closedir($dirHandle);
  }
  /**
* 复制文件
*
* @param string $fileUrl
* @param string $aimUrl
* @param boolean $overWrite 该参数控制是否覆盖原文件
* @return boolean
*/
  function copyFile($fileUrl, $aimUrl, $overWrite = false) {
    if (!file_exists($fileUrl)) {
      return false;
    }
    if (file_exists($aimUrl) && $overWrite == false) {
      return false;
    } elseif (file_exists($aimUrl) && $overWrite == true) {
      FileUtil :: unlinkFile($aimUrl);
    }
    $aimDir = dirname($aimUrl);
    FileUtil :: createDir($aimDir);
    copy($fileUrl, $aimUrl);
    return true;
  }
}

/**
 * ftp上传类
 * 来源:https://www.cnblogs.com/phproom/p/9683612.html
 */
class Ftp{
  private $host='';//远程服务器地址
  private $user='';//ftp用户名
  private $pass='';//ftp密码
  private $port=21;//ftp登录端口
  private $error='';//最后失败时的错误信息
  protected $conn;//ftp登录资源

  /**
         * 可以在实例化类的时候配置数据，也可以在下面的connect方法中配置数据
         * Ftp constructor.
         * @param array $config
         */
  public function __construct(array $config=[])
  {
    empty($config) OR $this->initialize($config);
  }

  /**
         * 初始化数据
         * @param array $config 配置文件数组
         */
  public function initialize(array $config=[]){
    $this->host = $config['host'];
    $this->user = $config['user'];
    $this->pass = $config['pass'];
    $this->port = isset($config['port']) ?: 21;
  }

  /**
         * 连接及登录ftp
         * @param array $config 配置文件数组
         * @return bool
         */
  public function connect(array $config=[]){
    empty($config) OR $this->initialize($config);
    if (FALSE == ($this->conn = @ftp_connect($this->host))){
      $this->error = "主机连接失败";
      return FALSE;
    }
    if ( ! $this->_login()){
      $this->error = "服务器登录失败";
      return FALSE;
    }
    return TRUE;
  }

  /**
         * 上传文件到ftp服务器
         * @param string $local_file 本地文件路径
         * @param string $remote_file 服务器文件地址
         * @param bool $permissions 文件夹权限
         * @param string $mode 上传模式(ascii和binary其中之一)
         */
  public function upload($local_file='',$remote_file='',$mode='auto',$permissions=NULL){
    if ( ! file_exists($local_file)){
      $this->error = "本地文件不存在";
      return FALSE;
    }
    if ($mode == 'auto'){
      $ext = $this->_get_ext($local_file);
      $mode = $this->_set_type($ext);
    }
    //创建文件夹
    $this->_create_remote_dir($remote_file);
    $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
    $result = @ftp_put($this->conn,$remote_file,$local_file,$mode);//同步上传
    if ($result === FALSE){
      $this->error = "文件上传失败";
      return FALSE;
    }
    return TRUE;
  }

  /**
         * 从ftp服务器下载文件到本地
         * @param string $local_file 本地文件地址
         * @param string $remote_file 远程文件地址
         * @param string $mode 上传模式(ascii和binary其中之一)
         */
  public function download($local_file='',$remote_file='',$mode='auto'){
    if ($mode == 'auto'){
      $ext = $this->_get_ext($remote_file);
      $mode = $this->_set_type($ext);
    }
    $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
    $result = @ftp_get($this->conn, $local_file, $remote_file, $mode);
    if ($result === FALSE){
      return FALSE;
    }
    return TRUE;
  }

  /**
         * 删除ftp服务器端文件
         * @param string $remote_file 文件地址
         */
  public function delete_file(string $remote_file=''){
    $result = @ftp_delete($this->conn,$remote_file);
    if ($result === FALSE){
      return FALSE;
    }
    return TRUE;
  }
  /**
         * ftp创建多级目录
         * @param string $remote_file 要上传的远程图片地址
         */
  private function _create_remote_dir($remote_file='',$permissions=NULL){
    $remote_dir = dirname($remote_file);
    $path_arr = explode('/',$remote_dir); // 取目录数组
    //$file_name = array_pop($path_arr); // 弹出文件名
    $path_div = count($path_arr); // 取层数
    foreach($path_arr as $val) // 创建目录
    {
      if(@ftp_chdir($this->conn,$val) == FALSE)
      {
        $tmp = @ftp_mkdir($this->conn,$val);//此处创建目录时不用使用绝对路径(不要使用:2018-02-20/ceshi/ceshi2，这种路径)，因为下面ftp_chdir已经已经把目录切换成当前目录
        if($tmp == FALSE)
        {
          echo "目录创建失败，请检查权限及路径是否正确！";
          exit;
        }
        if ($permissions !== NULL){
          //修改目录权限
          $this->_chmod($val,$permissions);
        }
        @ftp_chdir($this->conn,$val);
      }
    }

    for($i=0;$i<$path_div;$i++) // 回退到根,因为上面的目录切换导致当前目录不在根目录
    {
      @ftp_cdup($this->conn);
    }
  }

  /**
         * 递归删除ftp端目录
         * @param string $remote_dir ftp目录地址
         */
  public function delete_dir(string $remote_dir=''){
    $list = $this->list_file($remote_dir);
    if ( ! empty($list)){
      $count = count($list);
      for ($i=0;$i<$count;$i++){
        if ( ! preg_match('#\.#',$list[$i]) && !@ftp_delete($this->conn,$list[$i])){
          //这是一个目录，递归删除
          $this->delete_dir($list[$i]);
        }else{
          $this->delete_file($list[$i]);
        }
      }
    }
    if (@ftp_rmdir($this->conn,$remote_dir) === FALSE){
      return FALSE;
    }
    return TRUE;
  }

  /**
         * 更改 FTP 服务器上的文件或目录名
         * @param string $old_file 旧文件/文件夹名
         * @param string $new_file 新文件/文件夹名
         */
  public function remane(string $old_file='',string $new_file=''){
    $result = @ftp_rename($this->conn,$old_file,$new_file);
    if ($result === FALSE){
      $this->error = "移动失败";
      return FALSE;
    }
    return TRUE;
  }

  /**
         * 列出ftp指定目录
         * @param string $remote_path
         */
  public function list_file(string $remote_path=''){
    $contents = @ftp_nlist($this->conn, $remote_path);
    return $contents;
  }

  /**
         * 获取文件的后缀名
         * @param string $local_file
         */
  private function _get_ext($local_file=''){
    return (($dot = strrpos($local_file,'.'))==FALSE) ? 'txt' : substr($local_file,$dot+1);
  }

  /**
         * 根据文件后缀获取上传编码
         * @param string $ext
         */
  private function _set_type($ext=''){
    //如果传输的文件是文本文件，可以使用ASCII模式，如果不是文本文件，最好使用BINARY模式传输。
    return in_array($ext, ['txt', 'text', 'php', 'phps', 'php4', 'js', 'css', 'htm', 'html', 'phtml', 'shtml', 'log', 'xml'], TRUE) ? 'ascii' : 'binary';
  }

  /**
         * 修改目录权限
         * @param $path 目录路径
         * @param int $mode 权限值
         */
  private function _chmod($path,$mode=0755){
    if (FALSE == @ftp_chmod($this->conn,$path,$mode)){
      return FALSE;
    }
    return TRUE;
  }

  /**
         * 登录Ftp服务器
         */
  private function _login(){
    $bool = @ftp_login($this->conn,$this->user,$this->pass);
    if($bool) @ftp_pasv($this->conn,TRUE);//被动连接模式
    else $bool = false;
    return $bool;
  }

  /**
         * 获取上传错误信息
         */
  public function get_error_msg(){
    return $this->error;
  }
  /**
 * 关闭ftp连接
 * @return bool
 */
  public function close(){
    return $this->conn ? @ftp_close($this->conn_id) : FALSE;
  }

}


?>