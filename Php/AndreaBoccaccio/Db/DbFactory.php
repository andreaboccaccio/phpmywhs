<?php
/*
 * phpmywhs - An open source warehouse management software.
 * Copyright (C)2012 Andrea Boccaccio
 * contact email: andrea@andreaboccaccio.com
 * 
 * This file is part of phpmywhs.
 * 
 * phpmywhs is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * phpmywhs is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with phpmywhs. If not, see <http://www.gnu.org/licenses/>.
 * 
 */
class Php_AndreaBoccaccio_Db_DbFactory {
	
	private $dbArray;
	
	private $dbDefault;
	
	private static $instance = null;
	
	private function __clone() {
	
	}
	
	private function __construct() {
		$this->init();
	}
	
	public static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new Php_AndreaBoccaccio_Db_DbFactory();
		}
		return self::$instance;
	}
	
	private function getClasses() {
		$retArray = array(
				'Php_AndreaBoccaccio_Db_DbVoid',
				'Php_AndreaBoccaccio_Db_DbVerySimpleMySql'
		);
		return $retArray;
	}
	
	private function init() {
		foreach ($this->getClasses() as $tmpStrDb) {
			$tmpDb = $tmpStrDb::getInstance();
			$this->dbArray[$tmpDb->getKind()] = $tmpDb;
		}
		$this->dbDefault = Php_AndreaBoccaccio_Settings_SettingsNull::getInstance();
	}
	
	public function getDb($kind) {
		$ret = null;
		if(array_key_exists($kind, $this->dbArray)) {
			$ret = $this->dbArray[$kind];
		}
		else {
			$ret = $this->dbDefault;
		}
		return $ret;
	}
}