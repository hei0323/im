<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * businessWorker进程启动时触发
     */
    public static function onWorkerStart($businessWorker)
    {
        echo "Workman服务启动成功\n";
    }

    /**
     * 当客户端连接上gateway进程时(TCP三次握手完毕时)触发的回调函数
     */
    public static function onConnect($clientId)
    {
//        echo '用户'.$client_id."完成onConnect连接\n";
//        Gateway::sendToClient($client_id, json_encode(array(
//            'type'      => 'init',
//            'client_id' => $client_id
//        )));
    }

    /**
     * 当客户端连接上gateway完成websocket握手时触发的回调函数
     */
    public static function onWebSocketConnect($clientId, $data)
    {
        //直接返回$client_id 由让web项目处理
        echo '用户'.$clientId."完成onWebSocketConnect连接\n";
        Gateway::sendToClient($clientId, json_encode(array(
            'type'      => 'init',
            'clientId' => $clientId
        )),256);
    }

    /**
     * 有消息时触发该方法
     */
    public static function onMessage($clientId, $message)
    {
        //不做任何业务处理
        echo '用户'.$clientId.":$message\n";
        Gateway::sendToClient($client_id, json_encode(array(
            'msg'      => 'init',
            'content' => $clientId,
            'client_id' => $message
        )));
    }

    /**
     * 当用户断开连接时触发的方法
     */
    public static function onClose($clientId)
    {
        echo '用户'.$clientId."断开连接\n";
    }
}
