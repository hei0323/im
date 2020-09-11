<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'member';

    /**
     * 约束表主键
     * @var string
     */
    protected $primaryKey = 'member_id';

    /**
     * 禁止自动管理 created_at 和 updated_at 列
     * @var bool
     */
    public $timestamps = false;


    /**
     * 获取用户关联店铺
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store','store_id','store_id');
    }
}
