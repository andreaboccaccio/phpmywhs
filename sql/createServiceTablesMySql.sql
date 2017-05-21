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
CREATE TABLE IF NOT EXISTS USER (id BIGINT AUTO_INCREMENT PRIMARY KEY
,name VARCHAR(20) NOT NULL
,pwd VARCHAR(512) NOT NULL
,level VARCHAR(3) NOT NULL
,description VARCHAR(255)
,CONSTRAINT UNIQUE(name)
);

CREATE TABLE IF NOT EXISTS USER_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,name VARCHAR(20) NOT NULL
,pwd VARCHAR(512) NOT NULL
,level VARCHAR(3) NOT NULL
,description VARCHAR(255)
);

CREATE TRIGGER TRG_USER_INSERT_AFT AFTER INSERT
ON USER
FOR EACH ROW
INSERT INTO USER_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,name
	,pwd
	,level
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.name
	,NEW.pwd
	,NEW.level
	,NEW.description
);

delimiter |

CREATE TRIGGER TRG_USER_UPDATE_BFR BEFORE UPDATE
ON USER
FOR EACH ROW
BEGIN
UPDATE USER_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO USER_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,name
	,pwd
	,level
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.name
	,NEW.pwd
	,NEW.level
	,NEW.description
);
END;

|

CREATE TRIGGER TRG_USER_DELETE_BFR BEFORE DELETE
ON USER
FOR EACH ROW
BEGIN
UPDATE USER_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO USER_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,name
	,pwd
	,level
	,description
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.name
	,OLD.pwd
	,OLD.level
	,OLD.description
);
END;

|

delimiter ;

CREATE TABLE IF NOT EXISTS SESSION (id BIGINT AUTO_INCREMENT PRIMARY KEY
,code VARCHAR(512) NOT NULL
,utcvt_start DATETIME NOT NULL
,utcvt_end DATETIME NOT NULL
,user BIGINT NOT NULL
,CONSTRAINT UNIQUE(code)
);

CREATE TABLE IF NOT EXISTS SESSION_LOG (id BIGINT AUTO_INCREMENT PRIMARY KEY
,utctt_start DATETIME NOT NULL
,utctt_end DATETIME NOT NULL
,opcode VARCHAR(3) NOT NULL DEFAULT 'UNK'
,idorig BIGINT NOT NULL
,code VARCHAR(512) NOT NULL
,utcvt_start DATETIME NOT NULL
,utcvt_end DATETIME NOT NULL
,user BIGINT NOT NULL
);

CREATE TRIGGER TRG_SESSION_INSERT_AFT AFTER INSERT
ON SESSION
FOR EACH ROW
INSERT INTO SESSION_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,utcvt_start
	,utcvt_end
	,user
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'INS'
	,NEW.id
	,NEW.code
	,NEW.utcvt_start
	,NEW.utcvt_end
	,NEW.user
);

delimiter |

CREATE TRIGGER TRG_SESSION_UPDATE_BFR BEFORE UPDATE
ON SESSION
FOR EACH ROW
BEGIN
UPDATE SESSION_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO SESSION_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,utcvt_start
	,utcvt_end
	,user
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'UPD'
	,NEW.id
	,NEW.code
	,NEW.utcvt_start
	,NEW.utcvt_end
	,NEW.user
);
END;

|

CREATE TRIGGER TRG_SESSION_DELETE_BFR BEFORE DELETE
ON SESSION
FOR EACH ROW
BEGIN
UPDATE SESSION_LOG SET utctt_end = UTC_TIMESTAMP()
WHERE
(
	(OLD.id = idorig)
	AND
	(utctt_end = utctt_start)
);
INSERT INTO SESSION_LOG (
	utctt_start
	,utctt_end
	,opcode
	,idorig
	,code
	,utcvt_start
	,utcvt_end
	,user
) VALUES (
	UTC_TIMESTAMP()
	,UTC_TIMESTAMP()
	,'DEL'
	,OLD.id
	,OLD.code
	,OLD.utcvt_start
	,OLD.utcvt_end
	,OLD.user
);
END;

|

delimiter ;
