<?php
global $_W,$_GPC;

$op=$_GPC['op']?$_GPC['op']:'display';

$template='user-list';

if ($op=='post'){
	$template='user-post';
    $id = $_GPC['id'];
    if ($id){
        $list = pdo_fetch('SELECT * FROM '.tablename('openmouth_eat_user').' WHERE id=:id',array(':id'=>$id));
    }
    if($_W['ispost']){
		if($_GPC['name'] == ""){
			message("姓名不得为空");
		}
		if($_GPC['mobile'] == ""){
			message("手机号不得为空");
		}
        $data['name']=$_GPC['name'];
		$data['mobile']=$_GPC['mobile'];
		$data['avatarUrl']=safe_gpc_string($_GPC['avatarUrl']);
		$data['nickName']=$_GPC['nickName'];
		$data['status']=$_GPC['status']?$_GPC['status']:'00';
        
        if ($id){
			$data['update_time'] = TIMESTAMP;
            $res = pdo_update('openmouth_eat_user',$data,array('id'=>$id));
            message('更新成功',$this->createWebUrl('user',array('op'=>'display')));
        }else{			
			$data['uniacid'] = $_W['uniacid'];
			$data['create_time'] = TIMESTAMP;
			$res = pdo_insert('openmouth_eat_user',$data);
            message('添加成功',$this->createWebUrl('user',array('op'=>'display')));
        }
    }
} elseif ($op=='display'){
    $page = max(1,intval($_GPC['page']));
    $pagesize = 10;
    $lists = pdo_fetchall('SELECT * FROM '.tablename('openmouth_eat_user').' WHERE uniacid =:uniacid ORDER BY `id` DESC LIMIT '.($page - 1) * $pagesize . "," . $pagesize,array(':uniacid'=>$_W['uniacid']));	
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('openmouth_eat_user').' WHERE uniacid =:uniacid',array(':uniacid'=>$_W['uniacid']) );
    $pagination = pagination($total, $page,$pagesize);
} elseif ($op == 'delete'){
    $id = $_GPC['id'];
    $res = pdo_delete('openmouth_eat_user',array('id'=>$id));
    if ($res) {
        message('删除成功',$this->createWebUrl('user',array('op'=>'display')));
    }
}

load()->func('tpl');
include $this->template($template);
?>