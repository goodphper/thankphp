#哈哈琴行sql语句

##########################################################################
#栏目表

create table jxsr3623_category (
cat_id int auto_increment primary key,
cat_name varchar(20) not null default'',
intro varchar(100) not null default '',
parent_id int not null default 0
)engine myisam charset utf8;

##########################################################################

#商品表brand_id所属品牌，goods_number库存，goods_brief简短描述，goods_desc详细描述，ori_img原始图片

CREATE TABLE IF NOT EXISTS `goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_sn` char(15) NOT NULL DEFAULT '',
  `cat_id` smallint(6) NOT NULL DEFAULT '0',
  `goods_name` varchar(100) NOT NULL DEFAULT '',
  `class_start` int(10) unsigned NOT NULL DEFAULT '0',
  `class_end` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `goods_number` smallint(6) NOT NULL DEFAULT '1',
  `goods_brief` varchar(100) NOT NULL DEFAULT '',
  `goods_desc` text NOT NULL,
  `thumb_img` varchar(100) NOT NULL DEFAULT '',
  `goods_img` varchar(100) NOT NULL DEFAULT '',
  `ori_img` varchar(100) NOT NULL DEFAULT '',
  `is_sale` tinyint(4) NOT NULL DEFAULT '1',
  `is_hot` tinyint(4) NOT NULL DEFAULT '1',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`goods_id`),
  UNIQUE KEY `goods_sn` (`goods_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

##########################################################################

#后台用户表 采用
CREATE TABLE IF NOT EXISTS `jxsr3623_user` (
  `user_id` int unsigned not null auto_increment primary key,
  `user_name` char(20) NOT NULL DEFAULT '',
  `cn_name` varchar(20) NOT NULL DEFAULT '',
  `pwd` char(32) NOT NULL DEFAULT '',
  `email` varchar(50) not null default '',
  `mobile` char(20) not null default '',
  `reg_time` int unsigned not null default 0,
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` char(10) NOT NULL DEFAULT '',
  `lock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


#后台用户表 未采用
create table jxsr3623_user (
user_id int unsigned not null auto_increment primary key,
user_name varchar(50) not null default '',
email varchar(50) not null default '',
passwd char(50) not null default '',
reg_time int unsigned not null default 0,
last_login_ip int(10) unsigned NOT NULL DEFAULT '0',
last_login int unsigned not null default 0,
`lock` tinyint(1) unsigned NOT NULL DEFAULT '0',
) engine myisam charset utf8;


##########################################################################
#顾客资料表



##########################################################################

#创建订单表

收货人姓名: 	(必填)
电子邮件地址: 	(必填)
详细地址: 	(必填)
邮政编码: 	
电话: 	(必填)
手机: 	
标志建筑:

create table jxsr3623_orderinfo(
order_id int unsigned auto_increment primary key,
order_sn char(15) not null default '',
user_id int unsigned not null default 0,
username varchar(20) not null default '',
address varchar(30) not null default '',
zipcode char(6) not null default '',
reciver varchar(10) not null default '',
email   varchar(40) not null default '',
tel    varchar(20) not null default '',
mobile char(20) not null default '',
add_time int unsigned not null default 0,
order_amount decimal(10,2) not null default 0.0,
pay tinyint(1) not null default 0
) engine myisam charset utf8;

##########################################################################

#订单与商品的对应表
create table jxsr3623_ordergoods(
og_id int unsigned auto_increment primary key,
order_id int unsigned not null default 0,
order_sn char(15) not null default '',
goods_id  int unsigned not null default 0,
goods_name varchar(60) not null default '',
goods_number smallint not null default 1,
shop_price decimal(10,2) not null default 0.0,
subtotal  decimal(10,2) not null default 0.0
) engine myisam charset utf8;



##########################################################################



#取自TP的RBAC类

CREATE TABLE IF NOT EXISTS `jxsr3623_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jxsr3623_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jxsr3623_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `jxsr3623_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;