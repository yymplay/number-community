-- 用户表-------------------------------------------------------------------------- ---|
CREATE TABLE IF NOT EXISTS `szwl_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `account` char(11) NOT NULL COMMENT '手机号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` char(32) NOT NULL COMMENT '密码',
  `face` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '头像',
  `regip` varchar(32) NOT NULL COMMENT '注册时的IP',
  `token` char(32) DEFAULT NULL,
  `last_time` int(10) NOT NULL COMMENT '最后登录时间',
  `last_ip` varchar(32) NOT NULL COMMENT '最后登录IP',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `jyz` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `jifen` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `money` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `name` (`name`),
  KEY `token` (`token`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员';

                                                                                      
-- gender enum('男','女','保密') not null default '保密' comment '性别',

-- qq_openid char(32) not null default '' comment '关联的qq',
-- status tinyint unsigned not null default '0' comment '状态，0：未验证 1：正常',
-- jifen mediumint unsigned not null default '0' comment '积分可以消费',
-- jyz mediumint unsigned not null default '0' comment '经验值【只增不减】计算级别',
-- key qq_openid(qq_openid),
------------------用户详细信息--------------------------------------------------------------------|

CREATE TABLE IF NOT EXISTS `szwl_member_info` (
  `member_id` int(10) unsigned NOT NULL COMMENT '用户Id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '姓名',
  `identity` varchar(20) DEFAULT NULL DEFAULT '' COMMENT '身份证号码',
  `sfzz` varchar(50) DEFAULT NULL DEFAULT '' COMMENT '身份证正面',
  `sfzf` varchar(50) DEFAULT NULL DEFAULT '' COMMENT '身份证反面',
  `province` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '省',
  `city` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '市',
  `area` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '县',
  `address` varchar(100) DEFAULT NULL DEFAULT '' COMMENT '详细地址',
  `detail` longtext comment '详细信息',
  KEY (`member_id`),
  KEY `name` (`name`),
  KEY `province` (`province`),
  KEY `city` (`city`),
  KEY `area` (`area`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员信息';
  -- `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  -- `birthday` int(10) NOT NULL COMMENT '生日',
  -- `email` varchar(150) not null DEFAULT '' comment 'Email',
  -- `job_age` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '工作年限',
  -- `jiguan` varchar(40) DEFAULT NULL DEFAULT '' COMMENT '籍贯',
------------------用户等级--------------------------------------------------------------------|
CREATE TABLE IF NOT EXISTS `szwl_member_level` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '等级名',
  `jyz_bottom` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '经验值下限',
  `jyz_top` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '经验值上限',
  PRIMARY KEY (`id`),
  KEY `jyz_bottom` (`jyz_bottom`),
  KEY `jyz_top` (`jyz_top`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员等级' AUTO_INCREMENT=2 ;

INSERT INTO `szwl_member_level` (`id`, `name`, `jyz_bottom`, `jyz_top`) VALUES
(1, '1级', 0, 100);

------------------用户任务表--------------------------------------------------------------------|
CREATE TABLE IF NOT EXISTS `szwl_member_task` (
  `member_id` int(10) unsigned NOT NULL COMMENT '会员id',
  `task_id` int(10) unsigned NOT NULL COMMENT '任务id',
  KEY `member_id` (`member_id`),
  KEY `task_id` (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


------------------任务表--------------------------------------------------------------------|
CREATE TABLE IF NOT EXISTS `szwl_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类型id',
  `title` varchar(120) NOT NULL DEFAULT '' COMMENT '任务名称',
  `desc` varchar(256) NOT NULL DEFAULT '' COMMENT '任务描述',
  `money` smallint(6) NOT NULL DEFAULT '0' COMMENT '金币奖励',
  `jyz` smallint(6) NOT NULL DEFAULT '0' COMMENT '经验值',
  `jifen` smallint(6) NOT NULL DEFAULT '0' COMMENT '积分',
  `npc` tinyint(30) unsigned NOT NULL DEFAULT '0' COMMENT '游戏NPc角色',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序字段',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

------------------任务类型表--------------------------------------------------------------------|
CREATE TABLE IF NOT EXISTS `szwl_task_type` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务类型id',
  `type_name` varchar(30) NOT NULL COMMENT '任务类型名',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

------------------社区表--------------------------------------------------------------------|
CREATE TABLE IF NOT EXISTS `szwl_community` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '社区名称',
  `member_id` int(10) unsigned NOT NULL COMMENT '管理者id',
  `member_name` varchar(30) NOT NULL DEFAULT '' COMMENT '管理者',
  `password` char(32) NOT NULL COMMENT '密码',
  `count` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '已加入人数',
  `allow_count` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '允许加入人数',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态，0：未支付 1：已支付',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `member_name` (`member_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='社区' AUTO_INCREMENT=10004 ;
------------------社区编号--------------------------------------------------------------------|
CREATE TABLE IF NOT EXISTS `szwl_communityID` (
  `member_id` int(10) unsigned NOT NULL default '0' COMMENT '拥有者Id',
  `community_id` varchar(30) NOT NULL DEFAULT '' COMMENT '社区编号',
  `province` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '省',
  `city` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '市',
  `area` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '县',
  KEY (`member_id`),
  KEY `community_id` (`community_id`),
  KEY `province` (`province`),
  KEY `city` (`city`),
  KEY `area` (`area`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='社区编号表';
-------------------社区订单---------------------------------------------------------------------|
CREATE TABLE IF NOT EXISTS `szwl_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` char(14) NOT NULL default '' COMMENT '订单流水',
  `member_id` int(10) unsigned NOT NULL default '0' COMMENT '拥有者Id',
  `community_id` varchar(30) NOT NULL DEFAULT '' COMMENT '社区编号',
  `price` decimal(10,2) not null comment '价格',
  `gongwei` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '工委',
  `zongdui` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '纵队',
  `province` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '省',
  `recommend_name` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '推荐人',
  `recommend_phone` char(11) NOT NULL DEFAULT '' COMMENT '推荐人联系方式',
  `create_time` int(10) NOT NULL default '0' COMMENT '下单时间',
  `type` tinyint unsigned not null default '1' comment '订单类型:1社区购买',
  `admin_id` int(10) unsigned NOT NULL default '0' comment '默认0,后台添加则为管理员ID,',
  `status` tinyint unsigned not null default '0' comment '0未支付,1：已支付',
  `pay_method` varchar(30) DEFAULT NULL DEFAULT '' COMMENT '支付方式',
  primary KEY `id` (`id`),
  KEY `order_id` (`order_id`),
  KEY `member_id` (`member_id`),
  KEY `province` (`province`),
  KEY `recommend_phone` (`recommend_phone`),
  KEY `create_time` (`create_time`),
  KEY `type` (`type`),
  KEY `admin_id` (`admin_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='订单';
------------------社区成员--------------------------------------------------------------------|
CREATE TABLE IF NOT EXISTS `szwl_community_member` (
  `community_id` int(10) unsigned NOT NULL COMMENT 'Id',
  `member_id` int(10) unsigned NOT NULL COMMENT '成员id',
  KEY `community_id` (`community_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='社区成员';


-- 短信表
create table szwl_send_msg(
	account varchar(20) not null default '' comment '手机号',
	code char(6) not null default '' comment '验证码',
	addtime int unsigned not null default 0 comment '验证码生成时间',
	`ip` int unsigned not null default 0 comment '客户端IP',
	`number` tinyint unsigned not null default 0 comment '短信次数',
	index(account),
	index(addtime),
	index(ip)
)ENGINE=myisam DEFAULT CHARSET=utf8 COMMENT='短信表';







--后台---------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `szwl_admin` (
  `id`          int(10)  unsigned  NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username`    varchar(50) 	   NOT NULL default '' COMMENT '账号唯一',
  `account`     varchar(32) 	   NOT NULL default '' COMMENT '用户手机号',
  `password`    char(32) 		   NOT NULL COMMENT '密码',
  `nickname`    varchar(50) 	   NOT NULL default ''  COMMENT '昵称',
  `create_time` int(10) 		   NOT NULL default '0' COMMENT '创建时间',
  `last_time`   int(10) 		   NOT NULL default '0' COMMENT '最后登录时间',
  `status`      tinyint(1)         NOT NULL default '1' COMMENT '状态,1正常  0禁用',
  `role_id`     int(10)   unsigned NOT NULL COMMENT '权限id',
  PRIMARY KEY (`id`),
  key username(username),
  key status(`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员表';

CREATE table szwl_role(
	id MEDIUMINT UNSIGNED not null auto_increment COMMENT 'id',
	role_name varchar(150) not null comment '角色名',
	primary key (id)
)ENGINE=myisam DEFAULT charset=utf8;


create table szwl_privilege(
	`id` MEDIUMINT UNSIGNED not null auto_increment COMMENT '权限ID',
	`pri_name` varchar(150) not null COMMENT '权限名称',
	`module_name` VARCHAR(30) not null default '' comment '模块名',
	`controller_name` varchar(30) not null default '' comment '控制器',
	`action_name` varchar(30) not null default '' comment '方法',
	`img` varchar(30) not null default '' comment '图标',
	`parent_id` MEDIUMINT UNSIGNED not null default '0' comment '上级权限id',
	`listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
	PRIMARY key(id),
	key(`listorder`)
)ENGINE=myisam default charset=utf8;

CREATE table szwl_role_pri(
	role_id MEDIUMINT UNSIGNED not null COMMENT '角色id',
	pri_id MEDIUMINT UNSIGNED not null comment '权限id',
	key role_id(role_id),
	key pri_id(pri_id)
)ENGINE=myisam DEFAULT charset=utf8;