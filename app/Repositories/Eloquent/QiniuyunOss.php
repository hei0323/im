<?php

namespace App\Repositories\Eloquent;

use App\Repositorys\Contracts\OssInterface;
use OSS\Core\OssException;
use OSS\OssClient;

class QiniuyunOss extends OssRepository implements OssInterface
{
    private $accessKeyId = "LTAI4Fzjkkpzjauut9VshBtz";
    private $accessKeySecret = "6lFRbZlQciykXSYEZ2719sBV0W3rzk";
    private $endpoint = "chejj-oss.oss-cn-hangzhou.aliyuncs.com";
    private $bucket = "chejj-oss";
    private $ossClient;


    private function __construct()
    {
        try {
            $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function get($object)
    {
        $object = "oss-php-sdk-test/upload-test-object-name.txt";
        $options = array();
        try {
            $content = $this->ossClient->getObject($this->bucket, $object, $options);
        } catch (OssException $e) {
            return;
        }
    }

    /**
     * @inheritDoc
     */
    public function put($object,$content)
    {
        $object = "images/xiaoming.txt";
        $content = "Hello, OSS!"; // 上传的文件内容
        try {
            $this->ossClient->putObject($this->bucket, $object, $content);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }

    /**
     * （批量）删除对象
     * @param$object
     */
    public function del(string $object)
    {
        $object = "oss-php-sdk-test/upload-test-object-name.txt";
        try {
            if(is_array($object)){
                $this->ossClient->deleteObjects($this->bucket, $object);
            }else{
                $this->ossClient->deleteObject($this->bucket, $object);
            }
        } catch (OssException $e) {
            return;
        }
    }

    /**
     * （批量）删除对象
     * @param$object
     */
    public function delBatch(array $object)
    {
        $object = "oss-php-sdk-test/upload-test-object-name.txt";
        try {
            if(is_array($object)){
                $this->ossClient->deleteObjects($this->bucket, $object);
            }else{
                $this->ossClient->deleteObject($this->bucket, $object);
            }
        } catch (OssException $e) {
            return;
        }
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        // TODO: Implement all() method.
    }

    /**
     * @inheritDoc
     */
    public function info()
    {
        // TODO: Implement info() method.
    }
}
