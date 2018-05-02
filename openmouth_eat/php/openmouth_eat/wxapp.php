<?php
/**
 * 饭来张口模块小程序接口定义
 *
 * @author webfunny
 * @url http://api.nanhuaren.cn
 */
defined('IN_IA') or exit('Access Denied');

class Openmouth_eatModuleWxapp extends WeModuleWxapp {
	public function doPageTest(){
		global $_GPC, $_W;
		$errno = 0;
		$message = '返回消息';
		$data = array();
		return $this->result($errno, $message, $data);
	}
}