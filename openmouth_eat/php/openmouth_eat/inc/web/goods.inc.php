<?php
global $_W,$_GPC;

$op=$_GPC['op']?$_GPC['op']:'display';

$template='goods-list';

if ($op=='post'){
	$template='goods-post';
    $id = $_GPC['id'];
    if ($id){
        $list = pdo_fetch('SELECT * FROM '.tablename('openmouth_eat_goods').' WHERE id=:id',array(':id'=>$id));
		$list['tags'] = explode(',',$list['tags']);
    }
	
	$catalogs = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_catalog').' WHERE uniacid=:uniacid  ORDER BY `priority` DESC,id ASC ',array(':uniacid'=>$_W['uniacid']));

	$goods_catalogs = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_goods_catalog').' WHERE uniacid=:uniacid  ORDER BY `priority` DESC,id ASC ',array(':uniacid'=>$_W['uniacid']));

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
		$tags=$_GPC['tags'];
		$data['tags']=implode(',',$tags).',';
		$data['status']=$_GPC['status']?$_GPC['status']:'00';
		$data['priority']=$_GPC['priority']?$_GPC['priority']:10;
        if ($id){
			$data['update_time'] = TIMESTAMP;
            $res = pdo_update('openmouth_eat_goods',$data,array('id'=>$id));
            message('更新成功',$this->createWebUrl('goods',array('op'=>'display')));
        }else{			
			$data['seller_id'] = $_W['uniacid'];
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

	$goods_name = $_GPC['goods_name'];
	$catalog_id = $_GPC['catalog_id'];
	$tag = $_GPC['tag'];

	$catalogs = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_catalog').' WHERE uniacid=:uniacid  ORDER BY `priority` DESC,id ASC ',array(':uniacid'=>$_W['uniacid']));

	$goods_catalogs = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_goods_catalog').' WHERE uniacid=:uniacid  ORDER BY `priority` DESC,id ASC ',array(':uniacid'=>$_W['uniacid']));
	
	$where = 'and 1=1';
	
	$params = array(':uniacid'=>$_W['uniacid']);

	if(!empty($goods_name)){
		$params['goods_name'] = '%'.$goods_name.'%';
		$where = $where.' and g.goods_name like :goods_name ';
	}

	if(!empty($catalog_id)){
		$params['catalog_id'] = $catalog_id;
		$where = $where.' and g.catalog_id=:catalog_id ';
	}
	if(!empty($tag)){
		$params['tag'] = '%'.$tag.',%';
		$where = $where.' and g.tags like :tag ';
	}
	$sql = 'SELECT g.*,c.menu_name FROM '.tablename('openmouth_eat_goods').' g left join '.tablename('openmouth_eat_goods_catalog').' c on  g.uniacid = c.uniacid and g.catalog_id = c.id WHERE g.uniacid =:uniacid '.$where.' ORDER BY g.id ASC LIMIT '.($page - 1) * $pagesize . "," . $pagesize;

    $lists = pdo_fetchall($sql,$params);
	
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('openmouth_eat_goods').' g WHERE g.uniacid =:uniacid '.$where.'',$params );
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