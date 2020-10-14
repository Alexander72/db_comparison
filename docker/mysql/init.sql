CREATE DATABASE db_comparison_test;

USE db_comparison_test;

CREATE TABLE route
(
    id             INT AUTO_INCREMENT NOT NULL,
    origin         VARCHAR(10)        NOT NULL,
    destination    VARCHAR(10)        NOT NULL,
    price          int(10)        NOT NULL,
    departure      date        NOT NULL,
    INDEX  (origin),
    INDEX  (destination),
    INDEX  (price),
    INDEX  (departure),
    INDEX  (origin, destination, price, departure),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB
