<?php
global $_W,$_GPC;

$op=$_GPC['op']?$_GPC['op']:'display';

$template='goods-list';

if ($op=='post'){
	$template='goods-post';
    $id = $_GPC['id'];
    if ($id){
        $list = pdo_fetch('SELECT * FROM '.tablename('openmouth_eat_goods').' WHERE id=:id',array(':id'=>$id));
    }
    if($_W['ispost']){
		if($_GPC['goods_name'] == ""){
			message("名称不得为空");
		}
		if($_GPC['pic_url'] == ""){
			message("图片不得为空");
		}
        $data['goods_name']=$_GPC['goods_name'];
		$data['pic_url']=safe_gpc_string($_GPC['pic_url']);
		$data['price']=$_GPC['price'];
		$data['packing_fee']=$_GPC['packing_fee'];
		$data['num']=$_GPC['num'];
		$data['has_standard']=$_GPC['has_standard'];
		$data['catalog_id']=$_GPC['catalog_id'];
		$data['status']=$_GPC['status']?$_GPC['status']:'00';
		$data['priority']=$_GPC['priority']?$_GPC['priority']:10;
        
        if ($id){
			$data['update_time'] = TIMESTAMP;
            $res = pdo_update('openmouth_eat_goods',$data,array('id'=>$id));
            message('更新成功',$this->createWebUrl('goods',array('op'=>'display')));
        }else{			
			$data['seller_id'] = $_GPC['seller_id'];
			$data['sales'] = 0;
			$data['uniacid'] = $_W['uniacid'];
			$data['create_time'] = TIMESTAMP;
			$res = pdo_insert('openmouth_eat_goods',$data);
            message('添加成功',$this->createWebUrl('goods',array('op'=>'display')));
        }
    }
} elseif ($op=='display'){
    $page = max(1,intval($_GPC['page']));
    $pagesize = 10;
    $lists = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_goods').' WHERE uniacid =:uniacid ORDER BY `id` ASC LIMIT '.($page - 1) * $pagesize . "," . $pagesize,array(':uniacid'=>$_W['uniacid']));	
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('openmouth_eat_goods').' WHERE uniacid =:uniacid',array(':uniacid'=>$_W['uniacid']) );
    $pagination = pagination($total, $page,$pagesize);
} elseif ($op == 'delete'){
    $id = $_GPC['id'];
    $res = pdo_delete('openmouth_eat_goods',array('id'=>$id));
    if ($res) {
        message('删除成功',$this->createWebUrl('goods',array('op'=>'display')));
    }
}

load()->func('tpl');
include $this->template($template);
?>