<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMMsgList extends Model
{
    use SoftDeletes;
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'im_msg_list';

    /**
     * 约束表主键
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 禁止自动管理 created_at 和 updated_at 列
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型日期列的存储格式
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * 不可批量赋值的属性
     * @var array
     */
    protected $guarded = [];


    /**
     * 获取消息关联用户数据
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /**
     * 获取消息关联用户数据
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function member()
    {
        return $this->hasOne('App\Models\Member','member_id','sender_id');
    }

    /**
     * 获取消息关联用户数据
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
     public function group()
     {
         return $this->hasOne('App\Models\IMGroup','group_id','sender_id');
     }

}
