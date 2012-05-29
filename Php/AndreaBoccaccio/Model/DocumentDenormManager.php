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
class Php_AndreaBoccaccio_Model_DocumentDenormManager {
	
	private $docDenormArray = array();
	
	public function getDocs($year = null
			,$kind = null
			,$code = null
			,$contractor_kind = null
			,$contractor_code = null
			,$contractor = null
			,$warehouse = null
			,$date = null
			,$vt_start = null
			,$vt_end = null
			,$description = null
			,$orderby = null
			) {
		$tmpDocDenorm;
		$tmpRow;
		$i = -1;
		$where = 0;
		$setting = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance()->getSettings('xml');
		$db = Php_AndreaBoccaccio_Db_DbFactory::getInstance()->getDb($setting->getSettingFromFullName('classes.db'));
		$strSQL = "SELECT * FROM DOCUMENT_DENORM";
		if(($year != null)
				||($kind != null)
				||($code != null)
				||($contractor_kind != null)
				||($contractor_code != null)
				||($contractor != null)
				||($warehouse != null)
				||($date != null)
				||($vt_start != null)
				||($vt_end != null)
				||($description != null)
				) {
			$strSQL .= " WHERE (";
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
			if($contractor_kind != null) {
				if(strlen($contractor_kind) > 0) {
					$strSQL .= "(contractor_kind LIKE '%";
					$strSQL .= $contractor_kind;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($contractor_code != null) {
				if(strlen($contractor_code) > 0) {
					$strSQL .= "(contractor_code LIKE '%";
					$strSQL .= $contractor_code;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($contractor != null) {
				if(strlen($contractor) > 0) {
					$strSQL .= "(contractor LIKE '%";
					$strSQL .= $contractor;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($warehouse != null) {
				if(strlen($warehouse) > 0) {
					$strSQL .= "(warehouse LIKE '%";
					$strSQL .= $warehouse;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($date != null) {
				if(strlen($date) > 0) {
					$strSQL .= "(date LIKE '%";
					$strSQL .= $date;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($vt_start != null) {
				if(strlen($vt_start) > 0) {
					$strSQL .= "(vt_start LIKE '%";
					$strSQL .= $vt_start;
					$strSQL .= "%')";
					++$where;
				}
			}
			if($where > 0) {
				$strSQL .= " AND ";
			}
			if($vt_end != null) {
				if(strlen($vt_end) > 0) {
					$strSQL .= "(vt_end LIKE '%";
					$strSQL .= $vt_end;
					$strSQL .= "%')";
					++$where;
				}
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
			$srtSQL .= ")";
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
					$tmpDocDenorm = new Php_AndreaBoccaccio_Model_DocumentDenorm();
					$tmpDocDenorm->init(intval($tmpRow["id"])
							,$tmpRow["year"]
							,$tmpRow["kind"]
							,$tmpRow["code"]
							,$tmpRow["contractor_kind"]
							,$tmpRow["contractor_code"]
							,$tmpRow["contractor"]
							,$tmpRow["warehouse"]
							,$tmpRow["date"]
							,$tmpRow["vt_start"]
							,$tmpRow["vt_end"]
							,$tmpRow["description"]
							);
					$this->docDenormArray[$i] = $tmpDocDenorm;
				}
			}
		}
		return $this->docDenormArray;
	}
	
	public function eraseDocumentDenorm($id) {
		$setting = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance()->getSettings('xml');
		$db = Php_AndreaBoccaccio_Db_DbFactory::getInstance()->getDb($setting->getSettingFromFullName('classes.db'));
		$strSQL = "DELETE FROM DOCUMENT_DENORM";
		$res = array();
		
		$strSQL .= " WHERE(id=" .$id .");";
		$res = $db->execQuery($strSQL);
	}
}