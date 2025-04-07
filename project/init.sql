DROP USER IF EXISTS 'circuitforge'@'%';

CREATE USER 'circuitforge'@'%' IDENTIFIED BY '123';

DROP DATABASE IF EXISTS circuitforge;

CREATE DATABASE circuitforge;

GRANT ALL PRIVILEGES ON circuitforge.* TO 'circuitforge'@'%';

FLUSH PRIVILEGES;