<?php
/*
 * phpmywhs - An open source warehouse management software.
 * Copyright (C)2012 Andrea Boccaccio
 * contact email: andrea@andreaboccaccio.it
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
class Php_AndreaBoccaccio_View_ViewDocument extends Php_AndreaBoccaccio_View_ViewConsistentAbstract {

	private static $instance = null;

	private function __clone() {

	}

	private function __construct() {
		$this->setKind('doc');
	}

	public static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new Php_AndreaBoccaccio_View_ViewDocument();
		}
		return self::$instance;
	}

	public function getMenu() {
		$ret = parent::getMenu();

		$ret .= "<div id=\"docMain\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=main\">Principale</a>";
		$ret .= "</div>\n";
		$ret .= "<div id=\"docDocList\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=docList\">Lista Documenti</a>";
		$ret .= "</div>\n";
		$ret .= "<div id=\"docItemList\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=itemList&docId=";
		$ret .= $_GET["id"];
		$ret .= "\">Articoli</a>";
		$ret .= "</div>\n";
		$ret .= "<div id=\"docItemNew\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=itemNew&docId=";
		$ret .= $_GET["id"];
		$ret .= "\">Nuovo Articolo</a>";
		$ret .= "</div>\n";
		$ret .= "<div id=\"docItemNew\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=itemInWizard&docId=";
		$ret .= $_GET["id"];
		$ret .= "\">Nuovo Articolo Guidato</a>";
		$ret .= "</div>\n";
		$ret .= "</div>\n";

		return $ret;
	}

	public function getBody() {
		$ret = '';
		$settingsFact = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance();
		$dbFact = Php_AndreaBoccaccio_Db_DbFactory::getInstance();
		$settings = $settingsFact->getSettings('xml');
		$db = $dbFact->getDb($settings->getSettingFromFullName('classes.db'));
		$docDenorm = new Php_AndreaBoccaccio_Model_Document();
		$docDenormManager = new Php_AndreaBoccaccio_Model_DocumentManager();
		$initArray = array();
		$koBitArray = 0x0;
		$docId = -1;
		$eraser = 0;

		if(isset($_GET["id"])) {
			if(!is_null($_GET["id"])) {
				$docId = intval($db->sanitize($_GET["id"]));
				if($docId>0) {
					$docDenorm->loadFromDbById($docId);
				}
			}
		}
		
		if(isset($_GET["delete"])) {
			if(!is_null($_GET["delete"])) {
				if(strncmp($_GET["delete"],'maybe',strlen('maybe'))==0) {
					$eraser = 1;
				}
			}
		}
		
		if(isset($_GET["toDo"])) {
			if(!is_null($_GET["toDo"])) {
				if(strncmp($_GET["toDo"],'modify',strlen('modify'))==0) {
					if(isset($_POST["year"])) {
						if(preg_match("/^\d{4}$/", $_POST["year"])) {
							$koBitArray = $koBitArray & 0x7ffffffe;
							$initArray["year"] = $db->sanitize($_POST["year"]);
						}
						else {
							$koBitArray = $koBitArray | 0x1;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x1;
					}
					if(isset($_POST["kind"])) {
						if(preg_match("/^[a-zA-Z]{2,50}$/", $_POST["kind"])) {
							$koBitArray = $koBitArray & 0x7ffffffd;
							$initArray["kind"] = $db->sanitize($_POST["kind"]);
						}
						else {
							$koBitArray = $koBitArray | 0x2;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x2;
					}
					if(isset($_POST["code"])) {
						if(preg_match("/^\w{1,20}$/", $_POST["code"])) {
							$koBitArray = $koBitArray & 0x7ffffffb;
							$initArray["code"] = $db->sanitize($_POST["code"]);
						}
						else {
							$koBitArray = $koBitArray | 0x4;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x4;
					}
					if(isset($_POST["contractor_kind"])) {
						if(preg_match("/^[a-zA-Z0-9 ]{2,50}$/", $_POST["contractor_kind"])) {
							$koBitArray = $koBitArray & 0x7ffffff7;
							$initArray["contractor_kind"] = $db->sanitize($_POST["contractor_kind"]);
						}
						else {
							$koBitArray = $koBitArray | 0x8;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x8;
					}
					if(isset($_POST["contractor_code"])) {
						if(preg_match("/^\w{1,25}$/", $_POST["contractor_code"])) {
							$koBitArray = $koBitArray & 0x7fffffef;
							$initArray["contractor_code"] = $db->sanitize($_POST["contractor_code"]);
						}
						else {
							$koBitArray = $koBitArray | 0x10;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x10;
					}
					if($koBitArray == 0x0) {
						$docDenorm->init($initArray);
						$docDenorm->saveToDb();
					}
					if(isset($_POST["contractor"])) {
						if(preg_match("/^[a-zA-Z0-9 \-_]{1,25}$/", $_POST["contractor"])) {
							$koBitArray = $koBitArray & 0x7fffffdf;
							$initArray["contractor"] = $db->sanitize($_POST["contractor"]);
						}
						else {
							$koBitArray = $koBitArray | 0x20;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x20;
					}
					if(isset($_POST["warehouse"])) {
						if(preg_match("/^\w{1,50}$/", $_POST["warehouse"])) {
							$koBitArray = $koBitArray & 0x7fffffbf;
							$initArray["warehouse"] = $db->sanitize($_POST["warehouse"]);
						}
						else {
							$koBitArray = $koBitArray | 0x40;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x40;
					}
					if(isset($_POST["date"])) {
						if(preg_match("/^\d{2}.\d{2}.\d{4}$/", $_POST["date"])) {
							$koBitArray = $koBitArray & 0x7fffff7f;
							$initArray["date"] = $db->sanitize($_POST["date"]);
						}
						else {
							$koBitArray = $koBitArray | 0x80;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x80;
					}
					if(isset($_POST["vt_start"])) {
						if(preg_match("/^[a-zA-Z0-9 \-_:]{8,20}$/", $_POST["vt_start"])) {
							$koBitArray = $koBitArray & 0x7ffffeff;
							$initArray["vt_start"] = $db->sanitize($_POST["vt_start"]);
						}
						else {
							$koBitArray = $koBitArray | 0x100;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x100;
					}
					if(isset($_POST["vt_end"])) {
						if(preg_match("/^[a-zA-Z0-9 \-_:]{8,20}$/", $_POST["vt_end"])) {
							$koBitArray = $koBitArray & 0x7ffffdff;
							$initArray["vt_end"] = $db->sanitize($_POST["vt_end"]);
						}
						else {
							$koBitArray = $koBitArray | 0x200;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x200;
					}
					if(isset($_POST["description"])) {
						if(preg_match("/^[a-zA-Z0-9 \-_:]{0,255}$/", $_POST["description"])) {
							$koBitArray = $koBitArray & 0x7ffffbff;
							$initArray["description"] = $db->sanitize($_POST["description"]);
						}
						else {
							$koBitArray = $koBitArray | 0x400;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x400;
					}
					if($koBitArray == 0x0) {
						$docDenorm->init($initArray);
						$docDenorm->saveToDb();
					}
				}
				else if(strncmp($_GET["toDo"],'erase',strlen('erase'))==0)
				{
					$docDenorm = new Php_AndreaBoccaccio_Model_Document();
					$docDenormManager->eraseModel($db->sanitize($_POST["docDenormId"]));
				}
			}
		}

		$ret .= "<div id=\"body\">";
		if($eraser) {
			$ret .= "<div >Sicuro di voler cancellare:</div>";
		}
		$ret .= "<form method=\"post\" action=\"";
		$ret .= $_SERVER["PHP_SELF"];
		if($eraser) {
			$ret .= "?op=doc&toDo=erase&id=" . $docDenorm->getVar("id") . "\"> ";
		}
		else {
			$ret .= "?op=doc&toDo=modify&id=" . $docDenorm->getVar("id") . "\"> ";
			$ret .= "<div>";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=docNew&year=" . $docDenorm->getVar("year");
			$ret .= "&kind=" . $docDenorm->getVar('kind');
			$ret .= "&code=" . $docDenorm->getVar('code');
			$ret .= "&contractor_kind=" . $docDenorm->getVar('contractor_kind');
			$ret .= "&contractor_code=" . $docDenorm->getVar('contractor_code');
			$ret .= "&contractor=" . $docDenorm->getVar('contractor');
			$ret .= "&warehouse=" . $docDenorm->getVar('warehouse');
			$ret .= "&description=" . $docDenorm->getVar('description');
			$ret .= "\">Copia come nuovo";
			$ret .= "</a>";
			$ret .= "</div>";
		}
		$ret .= "<input type=\"hidden\" name=\"docDenormId\" value=\"" . $docDenorm->getVar("id") . "\" />";
		if(($koBitArray & 0x1) == 0x1) {
			$ret .= "<div class=\"error\">Anno errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Anno:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"year\" value=\"" . $docDenorm->getVar("year") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x2) == 0x2) {
			$ret .= "<div class=\"error\">Tipo Documento errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Tipo Documento:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"kind\" value=\"" . $docDenorm->getVar("kind") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x4) == 0x4) {
			$ret .= "<div class=\"error\">Numero/Codice errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Numero/Codice:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"code\" value=\"" . $docDenorm->getVar("code") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x80) == 0x80) {
			$ret .= "<div class=\"error\">Data documento errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Data documento:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"date\" value=\"" . $docDenorm->getVar("date") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x8) == 0x8) {
			$ret .= "<div class=\"error\">Tipo Contraente errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Tipo Contraente:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"contractor_kind\" value=\"" . $docDenorm->getVar("contractor_kind") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x10) == 0x10) {
			$ret .= "<div class=\"error\">P.IVA/CF Contraente errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">P.IVA/CF Contraente:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"contractor_code\" value=\"" . $docDenorm->getVar("contractor_code") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x20) == 0x20) {
			$ret .= "<div class=\"error\">Contraente errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Contraente:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"contractor\" value=\"" . $docDenorm->getVar("contractor") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x40) == 0x40) {
			$ret .= "<div class=\"error\">Magazzino errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Magazzino:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"warehouse\" value=\"" . $docDenorm->getVar("warehouse") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x100) == 0x100) {
			$ret .= "<div class=\"error\">Inizio Validita' errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Inizio Validita':</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"vt_start\" value=\"" . $docDenorm->getVar("vt_start") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x200) == 0x200) {
			$ret .= "<div class=\"error\">Fine Validita' errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Fine Validita':</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"vt_end\" value=\"" . $docDenorm->getVar("vt_end") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		if(($koBitArray & 0x400) == 0x400) {
			$ret .= "<div class=\"error\">Descrizione errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Descrizione:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"description\" value=\"" . $docDenorm->getVar("description") . "\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		$ret .= "<div class=\"submit\">";
		if($eraser) {
			$ret .= "<input type=\"submit\" value=\"Si, sono sicuro, cancella!\" />";
		}
		else {
			$ret .= "<input type=\"submit\" value=\"Modifica\" />";
		}
		$ret .= "</div>";
		$ret .= "<br />";
		$ret .= "</form>";
		$ret .= "</div>";

		return $ret;
	}
}