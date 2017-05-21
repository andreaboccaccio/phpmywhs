--
-- phpmywhs - An open source warehouse management software.
-- Copyright (C)2012 Andrea Boccaccio
-- contact email: andrea@andreaboccaccio.it
-- 
-- This file is part of phpmywhs.
-- 
-- phpmywhs is free software: you can redistribute it and/or modify
-- it under the terms of the GNU Affero General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
-- 
-- phpmywhs is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU Affero General Public License for more details.
-- 
-- You should have received a copy of the GNU Affero General Public License
-- along with phpmywhs. If not, see <http://www.gnu.org/licenses/>.
-- 
--
CREATE TABLE IF NOT EXISTS COUNTRY (id BIGINT AUTO_INCREMENT PRIMARY KEY
,codealpha2 VARCHAR(2) NOT NULL
,codealpha3 VARCHAR(3) NOT NULL
,number VARCHAR(3) NOT NULL
,enname VARCHAR(50) NOT NULL
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS COUNTRY_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,codealpha2 VARCHAR(2) NOT NULL
,codealpha3 VARCHAR(3) NOT NULL
,number VARCHAR(3) NOT NULL
,enname VARCHAR(50) NOT NULL
,description VARCHAR(255)
);

CREATE TRIGGER TRG_COUNTRY_INSERT_AFT AFTER INSERT
ON COUNTRY
FOR EACH ROW
INSERT INTO COUNTRY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,codealpha2
	,codealpha3
	,number
	,enname
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.codealpha2
	,NEW.codealpha3
	,NEW.number
	,NEW.enname
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_COUNTRY_UPDATE_BFR BEFORE UPDATE
ON COUNTRY
FOR EACH ROW
BEGIN
UPDATE COUNTRY_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO COUNTRY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,codealpha2
	,codealpha3
	,number
	,enname
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.codealpha2
	,NEW.codealpha3
	,NEW.number
	,NEW.enname
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_COUNTRY_DELETE_BFR BEFORE DELETE
ON COUNTRY
FOR EACH ROW
BEGIN
UPDATE COUNTRY_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO COUNTRY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,codealpha2
	,codealpha3
	,number
	,enname
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.codealpha2
	,OLD.codealpha3
	,OLD.number
	,OLD.enname
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS PROVINCE (id BIGINT AUTO_INCREMENT PRIMARY KEY
,country BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,name VARCHAR(20)
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS PROVINCE_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,country BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,name VARCHAR(20)
,description VARCHAR(255)
);

CREATE TRIGGER TRG_PROVINCE_INSERT_AFT AFTER INSERT
ON PROVINCE
FOR EACH ROW
INSERT INTO PROVINCE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,country
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.country
	,NEW.code
	,NEW.name
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_PROVINCE_UPDATE_BFR BEFORE UPDATE
ON PROVINCE
FOR EACH ROW
BEGIN
UPDATE PROVINCE_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO PROVINCE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,country
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.country
	,NEW.code
	,NEW.name
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_PROVINCE_DELETE_BFR BEFORE DELETE
ON PROVINCE
FOR EACH ROW
BEGIN
UPDATE PROVINCE_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO PROVINCE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,country
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.country
	,OLD.code
	,OLD.name
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS CITY (id BIGINT AUTO_INCREMENT PRIMARY KEY
,province BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,name VARCHAR(50)
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS CITY_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,province BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,name VARCHAR(50)
,description VARCHAR(255)
);

CREATE TRIGGER TRG_CITY_INSERT_AFT AFTER INSERT
ON CITY
FOR EACH ROW
INSERT INTO CITY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,province
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.province
	,NEW.code
	,NEW.name
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_CITY_UPDATE_BFR BEFORE UPDATE
ON CITY
FOR EACH ROW
BEGIN
UPDATE CITY_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO CITY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,province
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.province
	,NEW.code
	,NEW.name
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_CITY_DELETE_BFR BEFORE DELETE
ON CITY
FOR EACH ROW
BEGIN
UPDATE CITY_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO CITY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,province
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.province
	,OLD.code
	,OLD.name
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS ZIP (id BIGINT AUTO_INCREMENT PRIMARY KEY
,code VARCHAR(20) NOT NULL
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS ZIP_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,description VARCHAR(255)
);

CREATE TRIGGER TRG_ZIP_INSERT_AFT AFTER INSERT
ON ZIP
FOR EACH ROW
INSERT INTO ZIP_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.code
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_ZIP_UPDATE_BFR BEFORE UPDATE
ON ZIP
FOR EACH ROW
BEGIN
UPDATE ZIP_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ZIP_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.code
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_ZIP_DELETE_BFR BEFORE DELETE
ON ZIP
FOR EACH ROW
BEGIN
UPDATE ZIP_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ZIP_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.code
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS ZIPCITY (id BIGINT AUTO_INCREMENT PRIMARY KEY
,zip BIGINT NOT NULL
,city BIGINT NOT NULL
,CONSTRAINT UNIQUE(zip, city)
);

CREATE TABLE IF NOT EXISTS ZIPCITY_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,zip BIGINT NOT NULL
,city BIGINT NOT NULL
);

CREATE TRIGGER TRG_ZIPCITY_INSERT_AFT AFTER INSERT
ON ZIPCITY
FOR EACH ROW
INSERT INTO ZIPCITY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,zip
	,city
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.zip
	,NEW.city
);

delimiter |

CREATE TRIGGER TRG_ZIPCITY_UPDATE_BFR BEFORE UPDATE
ON ZIPCITY
FOR EACH ROW
BEGIN
UPDATE ZIPCITY_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ZIPCITY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,zip
	,city
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.zip
	,NEW.city
);
END;

|

CREATE TRIGGER TRG_ZIPCITY_DELETE_BFR BEFORE DELETE
ON ZIPCITY
FOR EACH ROW
BEGIN
UPDATE ZIPCITY_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ZIPCITY_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,zip
	,city
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.zip
	,OLD.city
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS ADDRESS (id BIGINT AUTO_INCREMENT PRIMARY KEY
,street VARCHAR(50) NOT NULL
,city BIGINT NOT NULL
,zip BIGINT NOT NULL
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS ADDRESS_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,street VARCHAR(50) NOT NULL
,city BIGINT NOT NULL
,zip BIGINT NOT NULL
,description VARCHAR(255)
);

CREATE TRIGGER TRG_ADDRESS_INSERT_AFT AFTER INSERT
ON ADDRESS
FOR EACH ROW
INSERT INTO ADDRESS_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,street
	,city
	,zip
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.street
	,NEW.city
	,NEW.zip
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_ADDRESS_UPDATE_BFR BEFORE UPDATE
ON ADDRESS
FOR EACH ROW
BEGIN
UPDATE ADDRESS_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ADDRESS_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,street
	,city
	,zip
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.street
	,NEW.city
	,NEW.zip
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_ADDRESS_DELETE_BFR BEFORE DELETE
ON ADDRESS
FOR EACH ROW
BEGIN
UPDATE ADDRESS_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ADDRESS_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,street
	,city
	,zip
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.street
	,OLD.city
	,OLD.zip
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS WAREHOUSE (id BIGINT AUTO_INCREMENT PRIMARY KEY
,code VARCHAR(20) NOT NULL
,name VARCHAR(20)
,description VARCHAR(255)
,street VARCHAR(50)
,zip VARCHAR(10)
,province VARCHAR(20)
,country VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS WAREHOUSE_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,name VARCHAR(20)
,description VARCHAR(255)
,street VARCHAR(50)
,zip VARCHAR(10)
,province VARCHAR(20)
,country VARCHAR(20)
);

CREATE TRIGGER TRG_WAREHOUSE_INSERT_AFT AFTER INSERT
ON WAREHOUSE
FOR EACH ROW
INSERT INTO WAREHOUSE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
	,street
	,zip
	,province
	,country
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.code
	,NEW.name
	,NEW.description
	,NEW.street
	,NEW.zip
	,NEW.province
	,NEW.country
);

delimiter |

CREATE TRIGGER TRG_WAREHOUSE_UPDATE_BFR BEFORE UPDATE
ON WAREHOUSE
FOR EACH ROW
BEGIN
UPDATE WAREHOUSE_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO WAREHOUSE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
	,street
	,zip
	,province
	,country
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.code
	,NEW.name
	,NEW.description
	,NEW.street
	,NEW.zip
	,NEW.province
	,NEW.country
);
END;

|

CREATE TRIGGER TRG_WAREHOUSE_DELETE_BFR BEFORE DELETE
ON WAREHOUSE
FOR EACH ROW
BEGIN
UPDATE WAREHOUSE_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO WAREHOUSE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
	,street
	,zip
	,province
	,country
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.code
	,OLD.name
	,OLD.description
	,OLD.street
	,OLD.zip
	,OLD.province
	,OLD.country
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS CONTRACTOR_KIND (id BIGINT AUTO_INCREMENT PRIMARY KEY
,code VARCHAR(20) NOT NULL DEFAULT 'UNKNOWN'
,name VARCHAR(50) NOT NULL DEFAULT 'UNKNOWN'
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS CONTRACTOR_KIND_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,code VARCHAR(20) NOT NULL DEFAULT 'UNKNOWN'
,name VARCHAR(50) NOT NULL DEFAULT 'UNKNOWN'
,description VARCHAR(255)
);

CREATE TRIGGER TRG_CONTRACTOR_KIND_INSERT_AFT AFTER INSERT
ON CONTRACTOR_KIND
FOR EACH ROW
INSERT INTO CONTRACTOR_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.code
	,NEW.name
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_CONTRACTOR_KIND_UPDATE_BFR BEFORE UPDATE
ON CONTRACTOR_KIND
FOR EACH ROW
BEGIN
UPDATE CONTRACTOR_KIND_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO CONTRACTOR_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.code
	,NEW.name
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_CONTRACTOR_KIND_DELETE_BFR BEFORE DELETE
ON CONTRACTOR_KIND
FOR EACH ROW
BEGIN
UPDATE CONTRACTOR_KIND_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO CONTRACTOR_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.code
	,OLD.name
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS ITEM_KIND (id BIGINT AUTO_INCREMENT PRIMARY KEY
,code VARCHAR(20) NOT NULL
,name VARCHAR(20)
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS ITEM_KIND_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,name VARCHAR(20)
,description VARCHAR(255)
);

CREATE TRIGGER TRG_ITEM_KIND_INSERT_AFT AFTER INSERT
ON ITEM_KIND
FOR EACH ROW
INSERT INTO ITEM_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.code
	,NEW.name
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_ITEM_KIND_UPDATE_BFR BEFORE UPDATE
ON ITEM_KIND
FOR EACH ROW
BEGIN
UPDATE ITEM_KIND_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ITEM_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.code
	,NEW.name
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_ITEM_KIND_DELETE_BFR BEFORE DELETE
ON ITEM_KIND
FOR EACH ROW
BEGIN
UPDATE ITEM_KIND_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ITEM_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.code
	,OLD.name
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS DOCUMENT_KIND (id BIGINT AUTO_INCREMENT PRIMARY KEY
,code VARCHAR(20) NOT NULL
,name VARCHAR(20)
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS DOCUMENT_KIND_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,name VARCHAR(20)
,description VARCHAR(255)
);

CREATE TRIGGER TRG_DOCUMENT_KIND_INSERT_AFT AFTER INSERT
ON DOCUMENT_KIND
FOR EACH ROW
INSERT INTO DOCUMENT_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.code
	,NEW.name
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_DOCUMENT_KIND_UPDATE_BFR BEFORE UPDATE
ON DOCUMENT_KIND
FOR EACH ROW
BEGIN
UPDATE DOCUMENT_KIND_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DOCUMENT_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.code
	,NEW.name
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_DOCUMENT_KIND_DELETE_BFR BEFORE DELETE
ON DOCUMENT_KIND
FOR EACH ROW
BEGIN
UPDATE DOCUMENT_KIND_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DOCUMENT_KIND_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.code
	,OLD.name
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS DOCUMENT (id BIGINT AUTO_INCREMENT PRIMARY KEY
,year INT NOT NULL
,kind BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,contractor BIGINT
,warehouse BIGINT NOT NULL
,date VARCHAR(10)
,vt_start DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
,vt_end DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
,description VARCHAR(100)
,CONSTRAINT UNIQUE(year,kind,code,contractor)
);

CREATE TABLE IF NOT EXISTS DOCUMENT_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,year INT NOT NULL
,kind BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,contractor BIGINT
,warehouse BIGINT NOT NULL
,date VARCHAR(10)
,vt_start DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
,vt_end DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
,description VARCHAR(100)
);

CREATE TRIGGER TRG_DOCUMENT_INSERT_AFT AFTER INSERT
ON DOCUMENT
FOR EACH ROW
INSERT INTO DOCUMENT_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,year
	,kind
	,code
	,contractor
	,warehouse
	,date
	,vt_start
	,vt_end
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.year
	,NEW.kind
	,NEW.code
	,NEW.contractor
	,NEW.warehouse
	,NEW.date
	,NEW.vt_start
	,NEW.vt_end
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_DOCUMENT_UPDATE_BFR BEFORE UPDATE
ON DOCUMENT
FOR EACH ROW
BEGIN
UPDATE DOCUMENT_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DOCUMENT_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,year
	,kind
	,code
	,contractor
	,warehouse
	,date
	,vt_start
	,vt_end
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.year
	,NEW.kind
	,NEW.code
	,NEW.contractor
	,NEW.warehouse
	,NEW.date
	,NEW.vt_start
	,NEW.vt_end
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_DOCUMENT_DELETE_BFR BEFORE DELETE
ON DOCUMENT
FOR EACH ROW
BEGIN
UPDATE DOCUMENT_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DOCUMENT_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,year
	,kind
	,code
	,contractor
	,warehouse
	,date
	,vt_start
	,vt_end
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.year
	,OLD.kind
	,OLD.code
	,OLD.contractor
	,OLD.warehouse
	,OLD.date
	,OLD.vt_start
	,OLD.vt_end
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS CONTRACTOR (id BIGINT AUTO_INCREMENT PRIMARY KEY
,kind VARCHAR(20) NOT NULL
,code VARCHAR(20)
,name VARCHAR(50)
,street VARCHAR(50)
,zip VARCHAR(10)
,province VARCHAR(20)
,country VARCHAR(20)
,mainphone VARCHAR(25)
,mainemail VARCHAR(255)
,mainwebsite VARCHAR(255)
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS CONTRACTOR_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,kind VARCHAR(20) NOT NULL
,code VARCHAR(20)
,name VARCHAR(50)
,street VARCHAR(50)
,zip VARCHAR(10)
,province VARCHAR(20)
,country VARCHAR(20)
,mainphone VARCHAR(25)
,mainemail VARCHAR(255)
,mainwebsite VARCHAR(255)
,description VARCHAR(255)
);

CREATE TRIGGER TRG_CONTRACTOR_INSERT_AFT AFTER INSERT
ON CONTRACTOR
FOR EACH ROW
INSERT INTO CONTRACTOR_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,kind
	,code
	,name
	,street
	,zip
	,province
	,country
	,mainphone
	,mainemail
	,mainwebsite
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.kind
	,NEW.code
	,NEW.name
	,NEW.street
	,NEW.zip
	,NEW.province
	,NEW.country
	,NEW.mainphone
	,NEW.mainemail
	,NEW.mainwebsite
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_CONTRACTOR_UPDATE_BFR BEFORE UPDATE
ON CONTRACTOR
FOR EACH ROW
BEGIN
UPDATE CONTRACTOR_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO CONTRACTOR_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,kind
	,code
	,name
	,street
	,zip
	,province
	,country
	,mainphone
	,mainemail
	,mainwebsite
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.kind
	,NEW.code
	,NEW.name
	,NEW.street
	,NEW.zip
	,NEW.province
	,NEW.country
	,NEW.mainphone
	,NEW.mainemail
	,NEW.mainwebsite
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_CONTRACTOR_DELETE_BFR BEFORE DELETE
ON CONTRACTOR
FOR EACH ROW
BEGIN
UPDATE CONTRACTOR_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO CONTRACTOR_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,kind
	,code
	,name
	,street
	,zip
	,province
	,country
	,mainphone
	,mainemail
	,mainwebsite
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.kind
	,OLD.code
	,OLD.name
	,OLD.street
	,OLD.zip
	,OLD.province
	,OLD.country
	,OLD.mainphone
	,OLD.mainemail
	,OLD.mainwebsite
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS ITEM (id BIGINT AUTO_INCREMENT PRIMARY KEY
,kind BIGINT NOT NULL
,code VARCHAR(20)
,name VARCHAR(50)
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS ITEM_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,kind BIGINT NOT NULL
,code VARCHAR(20) NOT NULL
,name VARCHAR(50)
,description VARCHAR(255)
);

CREATE TRIGGER TRG_ITEM_INSERT_AFT AFTER INSERT
ON ITEM
FOR EACH ROW
INSERT INTO ITEM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,kind
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.kind
	,NEW.code
	,NEW.name
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_ITEM_UPDATE_BFR BEFORE UPDATE
ON ITEM
FOR EACH ROW
BEGIN
UPDATE ITEM_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ITEM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,kind
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.kind
	,NEW.code
	,NEW.name
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_ITEM_DELETE_BFR BEFORE DELETE
ON ITEM
FOR EACH ROW
BEGIN
UPDATE ITEM_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ITEM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,kind
	,code
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.kind
	,OLD.code
	,OLD.name
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS DOC_ITEM (id BIGINT AUTO_INCREMENT PRIMARY KEY
,document BIGINT NOT NULL
,item BIGINT NOT NULL
,qty INTEGER NOT NULL DEFAULT 0
,CONSTRAINT UNIQUE(document, item)
);

CREATE TABLE IF NOT EXISTS DOC_ITEM_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,document BIGINT NOT NULL
,item BIGINT NOT NULL
,qty INTEGER NOT NULL DEFAULT 0
);

CREATE TRIGGER TRG_DOC_ITEM_INSERT_AFT AFTER INSERT
ON DOC_ITEM
FOR EACH ROW
INSERT INTO DOC_ITEM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,document
	,item
	,qty
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.document
	,NEW.item
	,NEW.qty
);

delimiter |

CREATE TRIGGER TRG_DOC_ITEM_UPDATE_BFR BEFORE UPDATE
ON DOC_ITEM
FOR EACH ROW
BEGIN
UPDATE DOC_ITEM_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DOC_ITEM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,document
	,item
	,qty
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.document
	,NEW.item
	,NEW.qty
);
END;

|

CREATE TRIGGER TRG_DOC_ITEM_DELETE_BFR BEFORE DELETE
ON DOC_ITEM
FOR EACH ROW
BEGIN
UPDATE DOC_ITEM_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DOC_ITEM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,document
	,item
	,qty
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.document
	,OLD.item
	,OLD.qty
);
END;

|

delimiter ;
