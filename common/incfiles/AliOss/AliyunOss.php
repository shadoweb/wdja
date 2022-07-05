<?php
header("Content-Type: text/html;charset=utf-8");
require_once(__DIR__.'/autoload.php');

use OSS\OssClient;
use OSS\Core\OssException;

class AliyunOss
{
    private $accessKeyId;
    private $accessKeySecret;
    private $endpoint;
    private $bucket;

    public function __construct()
    {
        $oss_config=array(
        'accessKeyId' => OSS_ID,
        'accessKeySecret' => OSS_KEY,
        'endpoint' => OSS_POINT,
        'bucket' => OSS_BUCKET
        );
        $this->accessKeyId = $oss_config['accessKeyId'];
        $this->accessKeySecret = $oss_config['accessKeySecret'];
        // Endpoint以杭州为例，其它Region请按实际情况填写。 $endpoint="http://oss-cn-hangzhou.aliyuncs.com";
        $this->endpoint = $oss_config['endpoint'];
        // 存储空间名称
        $this->bucket = $oss_config['bucket'];
    }

    public function upload_file($file_path, $file_name)
    {
        try {
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $result = $ossClient->uploadFile($this->bucket, $file_name, $file_path);//$result['info']['url'] 返回上传成功的oss文件地址
            $arr = array(
                'oss_file' =>$result['info']['url'],
                'local_path' => $file_name
            );
            return $arr;
        } catch (OssException $e) {
            // printf(__FUNCTION__ . ": FAILED\n");
            // printf($e->getMessage() . "\n");
            log_msg('文件上传失败',$e->getMessage());
            log_msg('文件上传失败',$file_path.'---'.$file_name);
            return false;
        }
    }

    public function signedUrl($filepath) {//filepath为bucket下不含bucket名称为图片路径
        $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        // 生成一个带图片处理参数的签名的URL，有效期是3600秒，可以直接使用浏览器访问。
        $timeout = 3600;// URL的有效期是3600秒
        $options = array(
                OssClient::OSS_PROCESS => "image/resize,m_lfit,h_800,w_800" //m_lfit等比缩放。设置图片缩放高宽为800
            );
        $signedUrl = $ossClient->signUrl($this->bucket, $filepath, $timeout, "GET", $options);
        return $signedUrl;
    }

    public function uploadObject($data,$fileName) {
        //上传二进制图片文件
        try{
            //实例化对象 将配置传入
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $result = $ossClient->putObject($this->bucket, $fileName, $data);
            $arr = array(
                'oss_file' =>$result['info']['url'],
                'local_path' => $fileName
            );
            return $arr;
        } catch (OssException $e) {
            // printf(__FUNCTION__ . ": FAILED\n");
            // printf($e->getMessage() . "\n");
            log_msg('文件上传失败',$e->getMessage());
            log_msg('文件上传失败','---'.$file_name);
            return false;
        }
    }

}