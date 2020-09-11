<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'store';

    /**
     * 约束表主键
     * @var string
     */
    protected $primaryKey = 'store_id';

    /**
     * 禁止自动管理 created_at 和 updated_at 列
     * @var bool
     */
    public $timestamps = false;


    /**
     * 获取店铺关联用户数据
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany('App\Models\Member','store_id','store_id');
    }

}
