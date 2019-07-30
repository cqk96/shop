<?php
namespace VirgoUtil;
use \OSS\OssClient as OssClient;
use \OSS\Core\OssException as OssException;
class OssInUe{
    function uploadToAliOSS($file,$fileType){
        $entension = $fileType; //上传文件的后缀
        $newName = date('YmdHis').mt_rand(100000,999999).$entension;//上传到oss的文件名
        $accessKeyId = 'LTAIjwgLo3OQV1id';//你的阿里云AccessKeyId
        $accessKeySecret = 'mwyY42coH7cikRxDQv3EzzQXwrodan';//涉及到隐私就不放出来了
        $endpoint = 'oss-ap-southeast-1.aliyuncs.com';//域名
        $bucket= 'southeastcod';//" <您使用的Bucket名字，注意命名规范>";
        $object = 'cod/'.$newName;//" <您使用的Object名字，注意命名规范>";
        $content = $file;//上传的文件

        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->setTimeout(3600 /* seconds */);
            $ossClient->setConnectTimeout(10 /* seconds */);
            //$ossClient->putObject($bucket, $object, $content);
            // 先把本地的example.jpg上传到指定$bucket, 命名为$object
           $rs=$ossClient->uploadFile($bucket, $object, $content);
           
            $signedUrl = $ossClient->signUrl($bucket, $object);
            $path = explode('?',$signedUrl)[0];
            $obj['status'] = true;
            $obj['path'] = $path;
        } catch (OssException $e) {
            $obj['status'] = false;
            $obj['path'] = "";
            print $e->getMessage();
        }

        return $obj;
    }
}