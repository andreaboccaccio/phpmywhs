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
UPDATE DBVERSION SET version='002' WHERE (version='001');

CREATE TABLE IF NOT EXISTS CAUSE (id BIGINT AUTO_INCREMENT PRIMARY KEY
,in_out VARCHAR(1) NOT NULL
,name VARCHAR(50)
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS CAUSE_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,in_out VARCHAR(1) NOT NULL
,name VARCHAR(50)
,description VARCHAR(255)
);

CREATE TRIGGER TRG_CAUSE_INSERT_AFT AFTER INSERT
ON CAUSE
FOR EACH ROW
INSERT INTO CAUSE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,in_out
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.in_out
	,NEW.name
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_CAUSE_UPDATE_BFR BEFORE UPDATE
ON CAUSE
FOR EACH ROW
BEGIN
UPDATE CAUSE_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO CAUSE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,in_out
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.in_out
	,NEW.name
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_CAUSE_DELETE_BFR BEFORE DELETE
ON CAUSE
FOR EACH ROW
BEGIN
UPDATE CAUSE_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO CAUSE_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,in_out
	,name
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.in_out
	,OLD.name
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS ITEM_OUT (id BIGINT AUTO_INCREMENT PRIMARY KEY
,cause BIGINT NOT NULL
,kind VARCHAR(50)
,code VARCHAR(50)
,name VARCHAR(50)
,qty INT
,cost DECIMAL(12,2)
,price DECIMAL(12,2)
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS ITEM_OUT_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,cause BIGINT NOT NULL
,kind VARCHAR(50)
,code VARCHAR(50)
,name VARCHAR(50)
,qty INT
,cost DECIMAL(12,2)
,price DECIMAL(12,2)
,description VARCHAR(255)
);

CREATE TRIGGER TRG_ITEM_OUT_INSERT_AFT AFTER INSERT
ON ITEM_OUT
FOR EACH ROW
INSERT INTO ITEM_OUT_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,cause
	,kind
	,code
	,name
	,qty
	,cost
	,price
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.cause
	,NEW.kind
	,NEW.code
	,NEW.name
	,NEW.qty
	,NEW.cost
	,NEW.price
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_ITEM_OUT_UPDATE_BFR BEFORE UPDATE
ON ITEM_OUT
FOR EACH ROW
BEGIN
UPDATE ITEM_OUT_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ITEM_OUT_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,cause
	,kind
	,code
	,name
	,qty
	,cost
	,price
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.cause
	,NEW.kind
	,NEW.code
	,NEW.name
	,NEW.qty
	,NEW.cost
	,NEW.price
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_ITEM_OUT_DELETE_BFR BEFORE DELETE
ON ITEM_OUT
FOR EACH ROW
BEGIN
UPDATE ITEM_OUT_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ITEM_OUT_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,cause
	,kind
	,code
	,name
	,qty
	,cost
	,price
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.cause
	,OLD.kind
	,OLD.code
	,OLD.name
	,OLD.qty
	,OLD.cost
	,OLD.price
	,OLD.description
);
END;

|

delimiter ;
