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
abstract class Php_AndreaBoccaccio_Model_ModelAbstract implements Php_AndreaBoccaccio_Model_ModelInterface {
	
	private $kind = '';
	private $dbTabname = '';
	private $vars = array();
	private $dbVarNames = array();
	private $changed = FALSE;
	
	private function setDbTabName($dbTabName) {
		$this->dbTabname = $dbTabName;
	}
	
	protected function getKind() {
		return $this->kind;
	}
	protected function setKind($kind) {
		$this->kind = $kind;
	}
	protected function getDbTabName() {
		return $this->dbTabname;
	}
	protected function addVar($name, $kind, $value, $dbName) {
		$tmpArray = array();
		if((!array_key_exists($name, $this->vars))
				&&(!array_key_exists($dbName, $this->dbVarNames))) {
			$tmpArray["kind"] = $kind;
			$tmpArray["value"] = $value;
			$tmpArray["dbName"] = $dbName;
			$this->vars[$name] = $tmpArray;
			$this->dbVarNames["$dbName"] = $name;
		}
	}
	protected function loadStructureFromXml() {
		$tmpArray = array();
		$settingsFac = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance();
		$settings = $settingsFac->getSettings('xml');
		$fileName = $settings->getSettingFromFullName('model.fileName');
		$xmlDoc = new DOMDocument();
		$xPath;
		$strXPathQuery = '';
		$nodes;
		$i = -1;
		$nFound = -1;
		
		$xmlDoc->load($fileName);
		$xPath = new DOMXPath($xmlDoc);
		$strXPathQuery = '//model/class[@id="' . $this->getKind() . '"]/dbtab';
		$nodes = $xPath->query($strXPathQuery);
		$nFound = $nodes->length;
		if($nFound == 1) {
			$tmpNode = $nodes->item(0);
			$this->setDbTabName($tmpNode->getAttribute('name'));
		}
		$strXPathQuery = '//model/class[@id="' . $this->getKind() . '"]/var';
		$nodes = $xPath->query($strXPathQuery);
		$nFound = $nodes->length;
		for ($i = 0; $i < $nFound; ++$i) {
			$tmpNode = $nodes->item($i);
			$this->addVar($tmpNode->getAttribute('name')
					,$tmpNode->getAttribute('kind')
					,$tmpNode->getAttribute('value')
					,$tmpNode->getAttribute('dbName'));
		}
		$this->changed = FALSE;
	}
	
	public function getVar($name) {
		$ret = null;
	
		if(array_key_exists($name, $this->vars)) {
			$ret = $this->vars[$name]["value"];
		}
		else {
			$ret = null;
		}
		return $ret;
	}
	public function setVar($name,$value) {
		if(array_key_exists($name, $this->vars)) {
			switch ($this->vars[$name]["kind"]) {
				case "int":
					if($this->vars[$name]["value"] != intval($value)) {
						$this->vars[$name]["value"] = intval($value);
						$this->changed = TRUE;
					}
					break;
				case "float":
					if($this->vars[$name]["value"] != floatval($value)) {
						$this->vars[$name]["value"] = floatval($value);
						$this->changed = TRUE;
					}
					break;
				default:
					if($this->vars[$name]["value"] != $value) {
						$this->vars[$name]["value"] = $value;
						$this->changed = TRUE;
					}
			}
		}
	}
	public function init($initArray) {
		foreach ($initArray as $name => $value) {
			$this->setVar($name, $value);
		}
	}
	public function loadFromDbById($id) {
		$ret = -1;
		$setting;
		$db;
		$res = array();
		$tmpRow = array();
		$strSQL = '';
		$i = 0;
		$tmpArray = array();
	
		if($id > 0) {
			$setting = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance()->getSettings('xml');
			$db = Php_AndreaBoccaccio_Db_DbFactory::getInstance()->getDb($setting->getSettingFromFullName('classes.db'));
			$strSQL = "SELECT ";
			foreach ($this->dbVarNames as $dbname => $value) {
				if($i>0) {
					$strSQL .= ",";
				}
				$strSQL .= $dbname;
				++$i;
			}
			$strSQL .= " FROM ";
			$strSQL .= $this->getDbTabName();
			$strSQL .= " WHERE (id=";
			$strSQL .= $id;
			$strSQL .= ");";
			$res = $db->execQuery($strSQL);
			if($res["success"] == TRUE) {
				$ret = $res["numrows"];
				if($ret == 1) {
					$tmpRow = $res["result"][0];
					foreach ($res["fields"] as $dbname) {
						$tmpArray[$this->dbVarNames[$dbname]] = $tmpRow[$dbname];
					}
					$this->init($tmpArray);
				}
			}
		}
		return $ret;
	}
	public function saveToDb() {
		$ret = -1;
		$setting;
		$db;
		$newObj = 0;
		$tmpArray = array();
		$max = -1;
		$i = -1;
		$prec = 0;
	
		if($this->changed) {
			$setting = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance()->getSettings('xml');
			$db = Php_AndreaBoccaccio_Db_DbFactory::getInstance()->getDb($setting->getSettingFromFullName('classes.db'));
			if($this->getVar("id") > 0) {
				$strSQL = "SELECT * FROM ";
				$strSQL .= $this->getDbTabName();
				$strSQL .= " WHERE ((id=";
				$strSQL .= $this->getVar("id");
				$strSQL .= ");";
				$res = $db->execQuery($strSQL);
				if($res["success"] == TRUE) {
					$ret = $res["numrows"];
					if($ret == 1) {
						$newObj = 0;
					}
					else {
						$newObj = 1;
					}
				}
			}
			else {
				$newObj = 1;
			}
			$i = 0;
			foreach ($this->dbVarNames as $dbname => $name) {
				if(strcmp($dbname, "id") != 0) {
					$tmpArray[$i] = array();
					$tmpArray[$i]["dbname"] = $dbname;
					$tmpArray[$i]["kind"] = $this->vars[$name]["kind"];
					$tmpArray[$i]["value"] = $this->getVar($name);
					++$i;
				}
			}
			$max = count($tmpArray);
			if($newObj) {
				$prec = 0;
				$strSQL = "INSERT INTO ";
				$strSQL .= $this->getDbTabName();
				$strSQL .= " (";
				for($i = 0; $i < $max;++$i) {
					if($i > 0) {
						$strSQL .= ",";
					}
					$strSQL .= $tmpArray[$i]["dbname"];
				}
				$strSQL .= ") ";
				$strSQL .= "VALUES (";
				for($i = 0; $i < $max;++$i) {
					if($i > 0) {
						if($prec > 0) {
							$strSQL .= "'";
						}
						$strSQL .= ",";
					}
					switch ($tmpArray[$i]["kind"]) {
						case "int":
						case "float":
							$prec = 0;
							break;
						default:
							$strSQL .= "'";
							$prec = 1;
					}
					$strSQL .= $tmpArray[$i]["value"];
				}
				if($prec > 0) {
					$strSQL .= "'";
				}
				$strSQL .= ");";
				$res = $db->execQuery($strSQL);
				if($res["success"] == TRUE) {
					$ret = $res["numrows"];
				}
			}
			else {
				$prec = 0;
				$strSQL = "UPDATE ";
				$strSQL .= $this->getDbTabName();
				$strSQL .= " ";
				$strSQL .= "SET ";
				for($i = 0; $i < $max;++$i) {
					if($i > 0) {
						if($prec > 0) {
							$strSQL .= "'";
						}
						$strSQL .= ", ";
					}
					$strSQL .= $tmpArray[$i]["dbname"];
					$strSQL .= "=";
					switch ($tmpArray[$i]["kind"]) {
						case "int":
						case "float":
							$prec = 0;
							break;
						default:
							$strSQL .= "'";
							$prec = 1;
					}
					$strSQL .= $tmpArray[$i]["value"];
				}
				if($prec > 0) {
					$strSQL .= "'";
				}
				$strSQL .= " WHERE (id =";
				$strSQL .= $this->getVar("id");
				$strSQL .= ");";
				$res = $db->execQuery($strSQL);
				if($res["success"] == TRUE) {
					$ret = $res["numrows"];
				}
			}
		}
	
		return $ret;
	}
}