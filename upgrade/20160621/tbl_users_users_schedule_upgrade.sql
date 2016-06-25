ALTER TABLE users_schedule add LastRunTime int(11) DEFAULT 0 COMMENT "最后执行时间";
ALTER TABLE users_schedule add day tinyint(2) DEFAULT 0 COMMENT "间隔执行的天数";
