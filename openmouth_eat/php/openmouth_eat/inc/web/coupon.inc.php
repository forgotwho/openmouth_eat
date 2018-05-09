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

	$name = $_GPC['name'];

	$where = 'and 1=1';
	
	$params = array(':uniacid'=>$_W['uniacid']);

	if(!empty($name)){
		$params['name'] = '%'.$name.'%';
		$where = $where.' and name like :name ';
	}

	$sql = 'SELECT * FROM '.tablename('openmouth_eat_coupon').' WHERE uniacid =:uniacid '.$where.' ORDER BY priority DESC,`id` ASC LIMIT '.($page - 1) * $pagesize . "," . $pagesize;

    $lists = pdo_fetchall($sql,$params);	
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('openmouth_eat_coupon').' WHERE uniacid =:uniacid '.$where.' ',$params );
    $pagination = pagination($total, $page,$pagesize);

} elseif ($op == 'delete'){
    $id = $_GPC['id'];
    $res = pdo_delete('openmouth_eat_coupon',array('id'=>$id));
    if ($res) {
        message('删除成功',$this->createWebUrl('coupon',array('op'=>'display')));
    }
} elseif ($op=='publish'){
	$template='coupon-select';
	$user_id = $_GPC['user_id'];
	$coupons = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_coupon').' WHERE uniacid =:uniacid ORDER BY priority DESC,`id` ASC',array(':uniacid'=>$_W['uniacid']));
    if($_W['ispost']){
		$coupon_ids=$_GPC['coupon_id'];
		if(empty($coupon_ids)){
			message("请选择卡券");
		}
        
		$data['user_id']=$user_id;
        foreach($coupon_ids as $key => $value){
			$data['coupon_id']=$value[$key];
			$data['coupon_no']=TIMESTAMP;
			$data['use_status']='0';
			$data['seller_id'] = $_W['uniacid'];		
			$data['uniacid'] = $_W['uniacid'];
			$data['create_time'] = TIMESTAMP;
			$res = pdo_insert('openmouth_eat_user_coupon',$data);
		}
		message('添加成功',$this->createWebUrl('user',array('op'=>'display')));
    }
} 

load()->func('tpl');
include $this->template($template);
?>