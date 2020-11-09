<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMMsg extends Model
{
    use SoftDeletes;
    /**
     * 关联模型的数据表名
     * @var string
     */
    protected $table = 'im_msg';

    /**
     * 约束表主键
     * @var int
     */
    protected $primaryKey = 'msg_id';

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
     * 获取消息关联用户数据
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
/*    public function sender()
    {
        return $this->belongsTo('App\Models\Member','member_id','sender_id');
    }*/

    /**
     * 获取消息关联用户数据
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
 /*   public function receiver()
    {
        return $this->belongsTo('App\Models\Member','member_id','receiver_id');
    }*/
}
