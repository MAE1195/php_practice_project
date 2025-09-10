DROP DATABASE IF EXISTS php_practice_project;

CREATE DATABASE php_practice_project DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE php_practice_project;

-- ユーザテーブル
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  login_id TEXT NOT NULL,
  login_pass TEXT NOT NULL,
  name TEXT NOT NULL,
  mail TEXT NOT NULL,
  create_user BIGINT(20) UNSIGNED NOT NULL,
  update_user BIGINT(20) UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  delete_flg BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO users( 
    login_id,
    login_pass,
    name,
    mail
) VALUES ( 
    'ebaeba',
    '$2y$10$dF4m13FUEkvscDcrMnzQYu8UxKx9usItPYnjBJ8cpE2m2WhiMA7N.',
    '管理者1',
    'test@ebacorp.jp'
);