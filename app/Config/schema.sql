CREATE TABLE users (
       id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(255) NOT NULL UNIQUE,
       password CHAR(40) NOT NULL,
       group_id INT(11) NOT NULL,
       created DATETIME,
       modified DATETIME,
       FOREIGN KEY(group_id) REFERENCES groups(id)
) ENGINE=InnoDB;

CREATE TABLE groups (
       id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(100) NOT NULL,
       created DATETIME,
       modified DATETIME
) ENGINE=InnoDB;

CREATE TABLE managers (
       id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(100) NOT NULL,
       host VARCHAR(100) NOT NULL,
       port INT(11) NOT NULL,
       created DATETIME,
       modified DATETIME
) ENGINE=InnoDB;