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
	$coupons = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_coupon').' WHERE uniacid =:uniacid ORDER BY priority DESC,`id` ASC',array(':uniacid'=>$_W['uniacid']));
	$users = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_user').' WHERE uniacid =:uniacid ORDER BY `id` ASC',array(':uniacid'=>$_W['uniacid']));
    if($_W['ispost']){
		if($_GPC['coupon_id'] == ""){
			message("请选择卡券");
		}
		if($_GPC['user_id'] == ""){
			message("请选择用户");
		}
        $data['coupon_id']=$_GPC['coupon_id'];
		$data['user_id']=$_GPC['user_id'];
		$data['use_status']=$_GPC['use_status']?$_GPC['use_status']:0;
		$data['priority']=$_GPC['priority']?$_GPC['priority']:10;
        
        if ($id){
			$data['update_time'] = TIMESTAMP;
            $res = pdo_update('openmouth_eat_user_coupon',$data,array('id'=>$id));
            message('更新成功',$this->createWebUrl('user_coupon',array('op'=>'display')));
        }else{			
			$data['coupon_no'] = TIMESTAMP;
			$data['use_status'] = 0;
			$data['uniacid'] = $_W['uniacid'];
			$data['create_time'] = TIMESTAMP;
			$res = pdo_insert('openmouth_eat_user_coupon',$data);
			$coupon = pdo_fetch('SELECT * FROM '.tablename('openmouth_eat_coupon').' WHERE id=:id',array(':id'=>$data['coupon_id']));
			$coupon_data['publish_num'] = $coupon['publish_num']+1;
			pdo_update('openmouth_eat_coupon',$coupon_data,array('id'=>$data['coupon_id']));
            message('添加成功',$this->createWebUrl('user_coupon',array('op'=>'display')));
        }
    }
} elseif ($op=='display'){
    $page = max(1,intval($_GPC['page']));
    $pagesize = 10;

	$coupons = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_coupon').' WHERE uniacid =:uniacid ORDER BY priority DESC,`id` ASC',array(':uniacid'=>$_W['uniacid']));

	$users = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_user').' WHERE uniacid =:uniacid ORDER BY `id` ASC',array(':uniacid'=>$_W['uniacid']));
	
	$coupon_id = $_GPC['coupon_id'];

	$user_id = $_GPC['user_id'];

	$where = 'and 1=1';
	
	$params = array(':uniacid'=>$_W['uniacid']);

	if(!empty($coupon_id)){
		$params['coupon_id'] = $coupon_id;
		$where = $where.' and uc.coupon_id = :coupon_id ';
	}

	if(!empty($user_id)){
		$params['user_id'] = $user_id;
		$where = $where.' and uc.user_id = :user_id ';
	}

	$sql = 'SELECT uc.*,c.name as coupon_name,c.deadline_time,u.name as user_name FROM '.tablename('openmouth_eat_user_coupon').' uc left join '.tablename('openmouth_eat_coupon').' c on uc.uniacid = c.uniacid and  uc.coupon_id = c.id left join '.tablename('openmouth_eat_user').' u on uc.uniacid = u.uniacid and  uc.user_id = u.id WHERE uc.uniacid =:uniacid '.$where.' ORDER BY uc.id ASC LIMIT '.($page - 1) * $pagesize . "," . $pagesize;
	$lists = pdo_fetchall($sql,$params);
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('openmouth_eat_user_coupon').' uc WHERE uc.uniacid =:uniacid  '.$where,$params );
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