<?php

namespace App\Repositorys\Contracts;

Interface OssInterface
{
    /**
     * 获取文件
     */
    public function get(string $object);

    /**
     * 上传文件
     */
    public function put(string $object,string $content);

    /**
     * 删除文件
     */
    public function del(string $object);


    /**
     * 删除文件
     */
    public function delBatch(array $object);

    /**
     * 获取文件列表
     */
    public function all();

    /**
     * 获取文件信息
     */
    public function info();

}
