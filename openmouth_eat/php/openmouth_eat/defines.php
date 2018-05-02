<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

!defined('OPENMOUTH_EAT_PATH') && define('OPENMOUTH_EAT_PATH', IA_ROOT . '/addons/openmouth_eat');
!defined('OPENMOUTH_EAT_INC') && define('OPENMOUTH_EAT_INC', OPENMOUTH_EAT_PATH . '/inc');
!defined('OPENMOUTH_EAT_PATH') && define('OPENMOUTH_EAT_PATH', OPENMOUTH_EAT_PATH . '/data/log/');
!defined('OPENMOUTH_EAT_LIB') && define('OPENMOUTH_EAT_LIB', OPENMOUTH_EAT_PATH . '/lib');
define('EVAL_DEBUG', false);
require_once OPENMOUTH_EAT_INC . '/functions.php';