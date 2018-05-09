<?php
/**
 * 饭来张口模块处理程序
 *
 * @author webfunny
 * @url http://api.nanhuaren.cn
 */
defined('IN_IA') or exit('Access Denied');

class Openmouth_eatModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		if ($content=="列表"){
            return $this->respText('您触发了we7_demo模块');
        }
		return $this->respText('');
	}
}