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
class Php_AndreaBoccaccio_Model_ItemDenormManager {
	
	private $itemDenormArray = array();
	
	public function getItems($document = null
			,$kind = null
			,$code = null
			,$name = null
			,$qty = null
			,$value = null
			,$description = null
			,$orderby = null
			) {
		$tmpItemDenorm;
		$tmpRow;
		$i = -1;
		$where = -1;
		$setting = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance()->getSettings('xml');
		$db = Php_AndreaBoccaccio_Db_DbFactory::getInstance()->getDb($setting->getSettingFromFullName('classes.db'));
		$strSQL = "SELECT * FROM ITEM_DENORM";
		if(($document != null)
				||($kind != null)
				||($code != null)
				||($name != null)
				||($qty != null)
				||($value != null)
				||($description != null)
				) {
			$strSQL .= " WHERE (";
			if($document != null) {
				if(intval($document) > 0)
				$strSQL .= "(document = ";
				$strSQL .= intval($document);
				$strSQL .= ")";
				++$where;
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($kind != null) {
				if(strlen($kind) > 0) {
					$strSQL .= "(kind LIKE '%";
					$strSQL .= $kind;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($code != null) {
				if(strlen($code) > 0) {
					$strSQL .= "(code LIKE '%";
					$strSQL .= $code;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($name != null) {
				if(strlen($name) > 0) {
					$strSQL .= "(name LIKE '%";
					$strSQL .= $name;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($qty != null) {
				if(intval($qty) != 0) {
					$strSQL .= "(qty = ";
					$strSQL .= intval($qty);
					$strSQL .= ")";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($value != null) {
				$strSQL .= "(value = ";
				$strSQL .= intval($qty);
				$strSQL .= ")";
				++$where;
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($description != null) {
				if(strlen($description) > 0) {
					$strSQL .= "(description LIKE '%";
					$strSQL .= $description;
					$strSQL .= "%')";
					++$where;
				}
			}
			$strSQL .= ")";
		}
		if($orderby != null) {
			$strSQL .= " ORDER BY ";
			$strSQL .= $orderby;
		}
		$strSQL .= ";";
		$res = $db->execQuery($strSQL);
		if($res["success"] == TRUE) {
			if($res["numrows"] > 0) {
				for ($i = 0; $i < $res["numrows"]; ++$i) {
					$tmpRow = $res["result"][$i];
					$tmpItemDenorm = new Php_AndreaBoccaccio_Model_ItemDenorm();
					$tmpItemDenorm->init(intval($tmpRow["id"])
							,$tmpRow["document"]
							,$tmpRow["kind"]
							,$tmpRow["code"]
							,$tmpRow["name"]
							,intval($tmpRow["qty"])
							,intval($tmpRow["value"])
							,$tmpRow["description"]
							);
					$this->itemDenormArray[$i] = $tmpItemDenorm;
				}
			}
		}
		return $this->itemDenormArray;
	}
	
	public function eraseItemDenorm($id) {
		$setting = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance()->getSettings('xml');
		$db = Php_AndreaBoccaccio_Db_DbFactory::getInstance()->getDb($setting->getSettingFromFullName('classes.db'));
		$strSQL = "DELETE FROM ITEM_DENORM";
		$res = array();
		
		$strSQL .= " WHERE(id=" .$id .");";
		$res = $db->execQuery($strSQL);
	}
}