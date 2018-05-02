<?php
global $_W,$_GPC;

$op=$_GPC['op']?$_GPC['op']:'display';

$template='user-coupon-list';

if ($op=='post'){
	$template='user-coupon-post';
    $id = $_GPC['id'];
    if ($id){
        $list = pdo_fetch('SELECT * FROM '.tablename('openmouth_eat_user_coupon').' WHERE id=:id',array(':id'=>$id));
    }
    if($_W['ispost']){
		if($_GPC['coupon_id'] == ""){
			message("请选择优惠券");
		}
		if($_GPC['user_id'] == ""){
			message("请选择用户");
		}
        $data['coupon_id']=$_GPC['coupon_id'];
		$data['user_id']=$_GPC['user_id'];
		$data['use_status']=$_GPC['use_status']?$_GPC['status']:0;
		$data['priority']=$_GPC['priority']?$_GPC['priority']:10;
        
        if ($id){
			$data['update_time'] = TIMESTAMP;
            $res = pdo_update('openmouth_eat_user_coupon',$data,array('id'=>$id));
            message('更新成功',$this->createWebUrl('user_coupon',array('op'=>'display')));
        }else{			
			$data['coupon_no'] = TIMESTAMP;
			$data['use_status'] = 0;
			$data['overall']=5;
			$data['quality']=5;
			$data['service']=5;
			$data['uniacid'] = $_W['uniacid'];
			$data['create_time'] = TIMESTAMP;
			$res = pdo_insert('openmouth_eat_user_coupon',$data);
            message('添加成功',$this->createWebUrl('user_coupon',array('op'=>'display')));
        }
    }
} elseif ($op=='display'){
    $page = max(1,intval($_GPC['page']));
    $pagesize = 10;
    $lists = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_user_coupon').' WHERE uniacid =:uniacid ORDER BY `id` ASC LIMIT '.($page - 1) * $pagesize . "," . $pagesize,array(':uniacid'=>$_W['uniacid']));	
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('openmouth_eat_user_coupon').' WHERE uniacid =:uniacid',array(':uniacid'=>$_W['uniacid']) );
    $pagination = pagination($total, $page,$pagesize);
} elseif ($op == 'delete'){
    $id = $_GPC['id'];
    $res = pdo_delete('openmouth_eat_user_coupon',array('id'=>$id));
    if ($res) {
        message('删除成功',$this->createWebUrl('user_coupon',array('op'=>'display')));
    }
}

load()->func('tpl');
include $this->template($template);
?>