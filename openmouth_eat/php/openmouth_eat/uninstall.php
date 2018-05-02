<?php

$sql = <<<EOT


DROP TABLE `openmouth_eat_catalogs`; 
DROP TABLE `openmouth_eat_coupon`; 
DROP TABLE `openmouth_eat_goods`; 
DROP TABLE `openmouth_eat_goods_catalogs`; 
DROP TABLE `openmouth_eat_goods_sub`; 
DROP TABLE `openmouth_eat_orders`; 
DROP TABLE `openmouth_eat_orders_flow`; 
DROP TABLE `openmouth_eat_shops`; 
DROP TABLE `openmouth_eat_shops_promotion`; 
DROP TABLE `openmouth_eat_users`; 
DROP TABLE `openmouth_eat_users_address`; 
DROP TABLE `openmouth_eat_users_coupon`;

EOT;

pdo_query($sql);
