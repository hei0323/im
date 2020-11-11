<?php


namespace App\Validate;


class TestRedisValidate extends BaseValidate
{
    //验证规则
    protected $rule =[
        'id'=>'required',
        'title' => 'required|max:255',
        'content' => 'required',
    ];

    //自定义验证信息
    protected $message = [
        'id.required'=>'缺少文章id',
        'title.required'=>'请输入title',
        'title.max'=>'title长度不能大于 255',
        'content.required'=>'请输入内容',
    ];

    //自定义场景
    protected $scene = [
        'add'=>"title,content",
        'edit'=> ['id','title','content'],
    ];
}
