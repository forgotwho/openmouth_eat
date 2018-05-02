<?php
global $_W,$_GPC;

$op=$_GPC['op']?$_GPC['op']:'display';

$template='coupon-list';

if ($op=='post'){
	$template='coupon-post';
    $id = $_GPC['id'];
    if ($id){
        $list = pdo_fetch('SELECT * FROM '.tablename('openmouth_eat_coupon').' WHERE id=:id',array(':id'=>$id));
    }
    if($_W['ispost']){
		if($_GPC['name'] == ""){
			message("名称不得为空");
		}
		if($_GPC['code'] == ""){
			message("编号不得为空");
		}
        $data['name']=$_GPC['name'];
		$data['code']=$_GPC['code'];
		$data['money']=$_GPC['money'];
		$data['num']=$_GPC['num'];
		$data['publish_time']=$_GPC['publish_time'];
		$data['deadline_time']=$_GPC['deadline_time'];
		$data['useful_type']=$_GPC['useful_type'];
		$data['status']=$_GPC['status']?$_GPC['status']:'00';
		$data['priority']=$_GPC['priority']?$_GPC['priority']:10;
        
        if ($id){
			$data['update_time'] = TIMESTAMP;
            $res = pdo_update('openmouth_eat_coupon',$data,array('id'=>$id));
            message('更新成功',$this->createWebUrl('coupon',array('op'=>'display')));
        }else{			
			$data['seller_id'] = $_W['uniacid'];		
			$data['uniacid'] = $_W['uniacid'];
			$data['create_time'] = TIMESTAMP;
			$res = pdo_insert('openmouth_eat_coupon',$data);
            message('添加成功',$this->createWebUrl('coupon',array('op'=>'display')));
        }
    }
} elseif ($op=='display'){
    $page = max(1,intval($_GPC['page']));
    $pagesize = 10;
    $lists = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_coupon').' WHERE uniacid =:uniacid ORDER BY `id` ASC LIMIT '.($page - 1) * $pagesize . "," . $pagesize,array(':uniacid'=>$_W['uniacid']));	
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('openmouth_eat_coupon').' WHERE uniacid =:uniacid',array(':uniacid'=>$_W['uniacid']) );
    $pagination = pagination($total, $page,$pagesize);
} elseif ($op == 'delete'){
    $id = $_GPC['id'];
    $res = pdo_delete('openmouth_eat_coupon',array('id'=>$id));
    if ($res) {
        message('删除成功',$this->createWebUrl('coupon',array('op'=>'display')));
    }
}

load()->func('tpl');
include $this->template($template);
?>