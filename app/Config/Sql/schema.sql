CREATE TABLE IF NOT EXISTS groups (
       id INT(11) NOT NULL AUTO_INCREMENT,
       name VARCHAR(100) NOT NULL,
       created DATETIME,
       modified DATETIME,
       PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS users (
       id INT(11) NOT NULL AUTO_INCREMENT,
       username VARCHAR(255) NOT NULL UNIQUE,
       password CHAR(40) NOT NULL,
       group_id INT(11) NOT NULL,
       created DATETIME,
       modified DATETIME,
       PRIMARY KEY(id),
       FOREIGN KEY(group_id) REFERENCES groups(id)
);

CREATE TABLE IF NOT EXISTS servers (
       id INT(11) NOT NULL AUTO_INCREMENT,
       name VARCHAR(100) NOT NULL,
       host VARCHAR(100) NOT NULL,
       port INT(11) NOT NULL,
       created DATETIME,
       modified DATETIME,
       PRIMARY KEY(id)
);

-- Static Tables
CREATE TABLE IF NOT EXISTS engines (
       id INT(11) NOT NULL AUTO_INCREMENT,
       name VARCHAR(100) NOT NULL,
       icon VARCHAR(255) NOT NULL,
       PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS games (
       id INT(11) NOT NULL AUTO_INCREMENT,
       title VARCHAR(128) NOT NULL,
       launch VARCHAR(64) NOT NULL,
       `update` VARCHAR(64) NOT NULL,
       icon VARCHAR(255) NOT NULL,
       url VARCHAR(255) NOT NULL,
       beta BOOLEAN DEFAULT false,
       external BOOLEAN DEFAULT false,
       engine_id INT(11) DEFAULT 5,
       PRIMARY KEY(id),
       FOREIGN KEY(engine_id) REFERENCES engines(id)
);

-- DELETE FROM engines;
-- LOAD DATA INFILE './engines.dat' INTO TABLE engines;
-- DELETE FROM games;
-- LOAD DATA INFILE './games.dat' INTO TABLE games;