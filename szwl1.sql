create table szwl_member
(
	`id` int unsigned not null auto_increment comment 'Id',
	`account` char(11) not null comment '手机号',
	`name` varchar(30) not null default '' comment '昵称',
	`password` char(32) not null comment '密码',
	`face` tinyint unsigned not null default '1' comment '头像',
	`regip` varchar(32) NOT NULL COMMENT '注册时的IP',
	`token` char(32) DEFAULT NULL,
	`last_time` int(10) NOT NULL COMMENT '最后登录时间',
	`last_ip` varchar(32) NOT NULL COMMENT '最后登录IP',
	`create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
	primary key (id),
	key account(account),
	key name(name),
	key token(token)
)engine=MyISAM default charset=utf8 comment '会员';


gender enum('男','女','保密') not null default '保密' comment '性别',

qq_openid char(32) not null default '' comment '关联的qq',
status tinyint unsigned not null default '0' comment '状态，0：未验证 1：正常',
jifen mediumint unsigned not null default '0' comment '积分可以消费',
jyz mediumint unsigned not null default '0' comment '经验值【只增不减】计算级别',
key qq_openid(qq_openid),



create table szwl_community
(
	`id` int unsigned not null auto_increment comment 'Id',
	`name` varchar(30) not null default '' comment '社区名称',
	`member_id` int unsigned not null comment '管理者id',
	`member_name` varchar(30) not null default '' comment '管理者',
	`password` char(32) not null comment '密码',
	`count` tinyint unsigned not null default '1' comment '允许加入人数',
	primary key (id),
	key account(account),
	key name(name)
)engine=MyISAM default charset=utf8 comment '社区';

create table szwl_community_member
(
	`community_id` int unsigned not null comment 'Id',
	`member_id` int unsigned not null comment '成员id',
	key (community_id),
	key (member_id),
)engine=MyISAM default charset=utf8 comment '社区成员';


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


-- 任务表
CREATE TABLE `szwl_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL DEFAULT '0' comment '类型id',
  `title` varchar(120) NOT NULL DEFAULT '' COMMENT '任务名称',
  `desc` varchar(256) NOT NULL DEFAULT '' COMMENT '任务描述',
  `money` smallint(6) NOT NULL DEFAULT '0' COMMENT '金币奖励',
  `jyz` smallint(6) NOT NULL DEFAULT '0' COMMENT '经验值',
  `jifen` smallint(6) NOT NULL DEFAULT '0' COMMENT '积分',
  `npc` tinyint(30) unsigned NOT NULL DEFAULT '0' COMMENT '游戏NPc角色',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序字段',
  PRIMARY KEY (`id`),
  key (type_id),
  key (sort)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--任务类型表
CREATE TABLE `szwl_task_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务类型id',
  `task_name` varchar(30) NOT NULL COMMENT '任务类型名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--任务类型表
CREATE TABLE `szwl_member_task` (
  `member_id` int(10) unsigned NOT NULL COMMENT '会员id',
  `task_id` int(10) unsigned NOT NULL COMMENT '任务id',
  KEY (`member_id`),
  KEY (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

