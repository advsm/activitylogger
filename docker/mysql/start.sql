create database if not exists `activity` ;
use activity;

create table if not exists `visit` (
    `id` int(11) not null auto_increment,
    `domain` varchar(64) not null,
    `url` varchar(512) collate utf8_unicode_ci not null,
    `ip` varchar(16) collate utf8_unicode_ci not null,
    `user_agent` varchar(512) not null,
    `created_at` datetime not null,
    `updated_at` timestamp not null on update current_timestamp,
    index (`domain`),
    index (`url`),
    primary key (`id`)
) engine=InnoDB default charset=utf8 collate=utf8_unicode_ci;