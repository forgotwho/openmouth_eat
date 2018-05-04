<?php
global $_W,$_GPC;

$op=$_GPC['op']?$_GPC['op']:'display';

$template='order-list';

if ($op=='post'){
	$template='order-post';
    $id = $_GPC['id'];
    if ($id){
        $list = pdo_fetch('SELECT * FROM '.tablename('openmouth_eat_order').' WHERE id=:id',array(':id'=>$id));
    }
    if($_W['ispost']){
		if($_GPC['seller_name'] == ""){
			message("名称不得为空");
		}
		if($_GPC['pic_url'] == ""){
			message("LOGO不得为空");
		}
        $data['seller_name']=$_GPC['seller_name'];
		$data['pic_url']=safe_gpc_string($_GPC['pic_url']);
		$data['min_price']=$_GPC['min_price'];
		$data['reach_time']=$_GPC['reach_time'];
		$data['longitude']=$_GPC['longitude'];
		$data['latitude']=$_GPC['latitude'];
		$data['is_rest']=$_GPC['is_rest'];
		$data['catalog_id']=$_GPC['catalog_id'];
		$data['title']=$_GPC['title'];
        $data['notice']=$_GPC['notice'];
		$data['phone']=$_GPC['phone'];
		$data['qrcode']=$_GPC['qrcode'];
		$data['address']=$_GPC['address'];
		$data['sell_time']=$_GPC['sell_time'];
		$data['express_info']=$_GPC['express_info'];
		$data['delivery_fee']=$_GPC['delivery_fee'];
		$data['tags']=$_GPC['tags'];
		$data['status']=$_GPC['status']?$_GPC['status']:'00';
		$data['priority']=$_GPC['priority']?$_GPC['priority']:10;
        
        if ($id){
			$data['update_time'] = TIMESTAMP;
            $res = pdo_update('openmouth_eat_order',$data,array('id'=>$id));
            message('更新成功',$this->createWebUrl('order',array('op'=>'display')));
        }else{			
			$data['seller_id'] = $_W['uniacid'];
			$data['sales'] = 0;
			$data['overall']=5;
			$data['quality']=5;
			$data['service']=5;
			$data['uniacid'] = $_W['uniacid'];
			$data['create_time'] = TIMESTAMP;
			$res = pdo_insert('openmouth_eat_order',$data);
            message('添加成功',$this->createWebUrl('order',array('op'=>'display')));
        }
    }
} elseif ($op=='display'){
    $page = max(1,intval($_GPC['page']));
    $pagesize = 10;
    $lists = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_order').' WHERE uniacid =:uniacid ORDER BY `id` ASC LIMIT '.($page - 1) * $pagesize . "," . $pagesize,array(':uniacid'=>$_W['uniacid']));	
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('openmouth_eat_order').' WHERE uniacid =:uniacid',array(':uniacid'=>$_W['uniacid']) );
    $pagination = pagination($total, $page,$pagesize);
} elseif ($op == 'delete'){
    $id = $_GPC['id'];
    $res = pdo_delete('openmouth_eat_order',array('id'=>$id));
    if ($res) {
        message('删除成功',$this->createWebUrl('order',array('op'=>'display')));
    }
}

load()->func('tpl');
include $this->template($template);
?>