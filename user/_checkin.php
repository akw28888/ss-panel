<?php
session_start();
require_once '../lib/config.php';
require_once '_check.php';
//加入防签到系统平台，如果不是在用户中心点的签到都不会奖励流量。
if($_SESSION['assp']==false){
    $a['code'] = '0';
    $a['msg'] = "非法访问";
}
//权限检查
elseif(!$oo->is_able_to_check_in()){
    $transfer_to_add = 0;
}else {
    if ($oo->unused_transfer() < 5000 * $tomb) {
        $transfer_to_add = rand(10000, 10000);
    } else {
        $transfer_to_add = rand($check_min, $check_max);
    }
    session_destroy();  //清空当前用户所有的Session信息
    $oo->add_transfer($transfer_to_add*$tomb);
    $oo->update_last_check_in_time();
    $a['msg'] = "获得了".$transfer_to_add."MB流量";
}

echo json_encode($a,JSON_UNESCAPED_UNICODE);
