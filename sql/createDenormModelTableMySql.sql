--
-- phpmywhs - An open source warehouse management software.
-- Copyright (C)2012 Andrea Boccaccio
-- contact email: andrea@andreaboccaccio.com
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
CREATE TABLE IF NOT EXISTS DOCUMENT_DENORM (id BIGINT AUTO_INCREMENT PRIMARY KEY
,year VARCHAR(4) NOT NULL
,kind VARCHAR(50) NOT NULL
,code VARCHAR(20)
,contractor_kind VARCHAR(50)
,contractor_code VARCHAR(25)
,contractor VARCHAR(50)
,warehouse VARCHAR(50)
,date VARCHAR(10)
,vt_start DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
,vt_end DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
,description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS DOCUMENT_DENORM_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,year VARCHAR(4) NOT NULL
,kind VARCHAR(50) NOT NULL
,code VARCHAR(20)
,contractor_kind VARCHAR(50)
,contractor_code VARCHAR(25)
,contractor VARCHAR(50)
,warehouse VARCHAR(50)
,date VARCHAR(10)
,vt_start DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
,vt_end DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
,description VARCHAR(255)
);

CREATE TRIGGER TRG_DOCUMENT_DENORM_INSERT_AFT AFTER INSERT
ON DOCUMENT_DENORM
FOR EACH ROW
INSERT INTO DOCUMENT_DENORM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,year
	,kind
	,code
	,contractor_kind
	,contractor_code
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
	,NEW.contractor_kind
	,NEW.contractor_code
	,NEW.contractor
	,NEW.warehouse
	,NEW.date
	,NEW.vt_start
	,NEW.vt_end
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_DOCUMENT_DENORM_UPDATE_BFR BEFORE UPDATE
ON DOCUMENT_DENORM
FOR EACH ROW
BEGIN
UPDATE DOCUMENT_DENORM_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DOCUMENT_DENORM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,year
	,kind
	,code
	,contractor_kind
	,contractor_code
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
	,NEW.contractor_kind
	,NEW.contractor_code
	,NEW.contractor
	,NEW.warehouse
	,NEW.date
	,NEW.vt_start
	,NEW.vt_end
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_DOCUMENT_DENORM_DELETE_BFR BEFORE DELETE
ON DOCUMENT_DENORM
FOR EACH ROW
BEGIN
UPDATE DOCUMENT_DENORM_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO DOCUMENT_DENORM_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,year
	,kind
	,code
	,contractor_kind
	,contractor_code
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
	,OLD.contractor_kind
	,OLD.contractor_code
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