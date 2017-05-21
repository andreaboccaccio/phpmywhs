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

CREATE TABLE IF NOT EXISTS DBVERSION (id BIGINT AUTO_INCREMENT PRIMARY KEY
,version VARCHAR(4) NOT NULL
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS DBVERSION_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,version VARCHAR(4) NOT NULL
,description VARCHAR(255)
);

CREATE TRIGGER TRG_DBVERSION_INSERT_AFT AFTER INSERT
ON DBVERSION
FOR EACH ROW
INSERT INTO DBVERSION_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,version
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.version
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_DBVERSION_UPDATE_BFR BEFORE UPDATE
ON DBVERSION
FOR EACH ROW
BEGIN
UPDATE DBVERSION_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DBVERSION_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,version
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.version
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_DBVERSION_DELETE_BFR BEFORE DELETE
ON DBVERSION
FOR EACH ROW
BEGIN
UPDATE DBVERSION_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DBVERSION_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,version
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.version
	,OLD.description
);
END;

|

delimiter ;

INSERT INTO DBVERSION (version) VALUES ('001');

DROP TRIGGER IF EXISTS TRG_ITEM_DENORM_INSERT_AFT;
DROP TRIGGER IF EXISTS TRG_ITEM_DENORM_UPDATE_BFR;
DROP TRIGGER IF EXISTS TRG_ITEM_DENORM_DELETE_BFR;

ALTER TABLE ITEM_DENORM MODIFY COLUMN code VARCHAR(50);
ALTER TABLE ITEM_DENORM_LOG MODIFY COLUMN code VARCHAR(50);

ALTER TABLE ITEM_DENORM ADD COLUMN cost DECIMAL(12,2) AFTER value;
ALTER TABLE ITEM_DENORM ADD COLUMN price DECIMAL(12,2) AFTER cost;
ALTER TABLE ITEM_DENORM_LOG ADD COLUMN cost DECIMAL(12,2) AFTER value;
ALTER TABLE ITEM_DENORM_LOG ADD COLUMN price DECIMAL(12,2) AFTER cost;

CREATE TRIGGER TRG_ITEM_DENORM_INSERT_AFT AFTER INSERT
ON ITEM_DENORM
FOR EACH ROW
INSERT INTO ITEM_DENORM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,document
	,kind
	,code
	,name
	,qty
	,value
	,cost
	,price
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.document
	,NEW.kind
	,NEW.code
	,NEW.name
	,NEW.qty
	,NEW.value
	,NEW.cost
	,NEW.price
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_ITEM_DENORM_UPDATE_BFR BEFORE UPDATE
ON ITEM_DENORM
FOR EACH ROW
BEGIN
UPDATE ITEM_DENORM_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ITEM_DENORM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,document
	,kind
	,code
	,name
	,qty
	,value
	,cost
	,price
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.document
	,NEW.kind
	,NEW.code
	,NEW.name
	,NEW.qty
	,NEW.value
	,NEW.cost
	,NEW.price
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_ITEM_DENORM_DELETE_BFR BEFORE DELETE
ON ITEM_DENORM
FOR EACH ROW
BEGIN
UPDATE ITEM_DENORM_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO ITEM_DENORM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,document
	,kind
	,code
	,name
	,qty
	,value
	,cost
	,price
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.document
	,OLD.kind
	,OLD.code
	,OLD.name
	,OLD.qty
	,OLD.value
	,OLD.cost
	,OLD.price
	,OLD.description
);
END;

|

delimiter ;
