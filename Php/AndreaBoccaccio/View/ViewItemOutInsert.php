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
class Php_AndreaBoccaccio_View_ViewItemOutInsert extends Php_AndreaBoccaccio_View_ViewConsistentAbstract {

	private static $instance = null;

	private function __clone() {

	}

	private function __construct() {
		$this->setKind('itemOutNew');
	}

	public static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new Php_AndreaBoccaccio_View_ViewItemOutInsert();
		}
		return self::$instance;
	}

	public function getMenu() {
		$ret = parent::getMenu();

		$ret .= "<div id=\"itemMain\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=main\">Principale</a>";
		$ret .= "</div>\n";
		$ret .= "<div id=\"itemItemOutList\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=itemOutList";
		$ret .= "\">Lista Scarichi</a>";
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
		$itemDenorm = new Php_AndreaBoccaccio_Model_ItemOut();
		$itemDenormManager = new Php_AndreaBoccaccio_Model_ItemOutManager();
		$causeMan = new Php_AndreaBoccaccio_Model_CauseManager();
		$causes = array();
		$initArray = array();
		$koBitArray = 0x0;
		$itemId = -1;
		$eraser = 0;
		$filterCauses = array('in_out' => 'O');
		$tmpArr = array();

		if(isset($_GET["id"])) {
			if(!is_null($_GET["id"])) {
				$itemId = intval($db->sanitize($_GET["id"]));
				if($itemId>0) {
					$itemDenorm->loadFromDbById($itemId);
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
				if(strncmp($_GET["toDo"],'save',strlen('save'))==0) {
					if(isset($_POST["cause"])) {
						if(preg_match("/^\d+$/", $_POST["cause"])) {
							$koBitArray = $koBitArray & 0x7ffffffe;
							$initArray["cause"] = $db->sanitize($_POST["cause"]);
						}
						else {
							$koBitArray = $koBitArray | 0x1;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x1;
					}
					if(isset($_POST["kind"])) {
						if(preg_match("/^[a-zA-Z -]{2,50}$/", $_POST["kind"])) {
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
						if(preg_match("/^\w{1,50}$/", $_POST["code"])) {
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
					if(isset($_POST["name"])) {
						if(preg_match("/^[a-zA-Z0-9 -]{0,50}$/", $_POST["name"])) {
							$koBitArray = $koBitArray & 0x7ffffff7;
							$initArray["name"] = $db->sanitize($_POST["name"]);
						}
						else {
							$koBitArray = $koBitArray | 0x8;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x8;
					}
					if(isset($_POST["qty"])) {
						if(preg_match("/^\d+$/", $_POST["qty"])) {
							$koBitArray = $koBitArray & 0x7fffffef;
							$initArray["qty"] = $db->sanitize($_POST["qty"]);
						}
						else {
							$koBitArray = $koBitArray | 0x10;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x10;
					}
					if(isset($_POST["cost"])) {
						if(preg_match("/^(\d+,\d{2}|.{0})$/", $_POST["cost"])) {
							$koBitArray = $koBitArray & 0x7fffffdf;
							$initArray["cost"] = $db->sanitize(str_replace(",", ".", $_POST["cost"]));
						}
						else {
							$koBitArray = $koBitArray | 0x20;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x20;
					}
					if(isset($_POST["price"])) {
						if(preg_match("/^(\d+,\d{2}|.{0})$/", $_POST["price"])) {
							$koBitArray = $koBitArray & 0x7fffffbf;
							$initArray["price"] = $db->sanitize(str_replace(",", ".", $_POST["price"]));
						}
						else {
							$koBitArray = $koBitArray | 0x40;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x40;
					}
					if(isset($_POST["description"])) {
						if(preg_match("/^[a-zA-Z0-9 \-_:]{0,255}$/", $_POST["description"])) {
							$koBitArray = $koBitArray & 0x7fffff7f;
							$initArray["description"] = $db->sanitize($_POST["description"]);
						}
						else {
							$koBitArray = $koBitArray | 0x80;
						}
					}
					else {
						$koBitArray = $koBitArray | 0x80;
					}
					if($koBitArray == 0x0) {
						$itemDenorm->init($initArray);
						$itemDenorm->saveToDb();
					}
				}
				else if(strncmp($_GET["toDo"],'erase',strlen('erase'))==0)
				{
					$itemDenormManager->eraseModel($db->sanitize($_POST["itemDenormId"]));
				}
			}
		}

		$ret .= "<div id=\"body\">";
		$ret .= "<form method=\"post\" action=\"";
		$ret .= $_SERVER["PHP_SELF"];
		$ret .= "?op=itemOutNew&toDo=save\"> ";
		if(($koBitArray & 0x1) == 0x1) {
			$ret .= "<div class=\"error\">Causale errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Causale:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<select name=\"cause\">";
		$tmpArr = $causeMan->getModels(null,$filterCauses,'name');
		$causes = $tmpArr["result"];
		foreach ($causes as $gotCause) {
			$ret .= "<option";
			if(isset($_GET["cause"])) {
				if(!is_null($_GET["cause"])) {
					if(strlen($_GET["cause"])>0) {
						if(intval($_GET["cause"]) == $gotCause->getVar('id')) {
							$ret .= " selected=\"selected\"";
						}
					}
				}
			}
			$ret .= " value=\"". $gotCause->getVar('id');
			$ret .= "\">". $gotCause->getVar('name') ."</option>";
		}
		$ret .= "</select>";
		$ret .= "</div><br />";
		if(($koBitArray & 0x2) == 0x2) {
			$ret .= "<div class=\"error\">Categoria errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Categoria:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"kind\"";
		if(isset($_GET["kind"])) {
			if(!is_null($_GET["kind"])) {
				if(strlen($_GET["kind"])>0) {
					$ret .= " value=\"" . $_GET["kind"] . "\"";
				}
			}
		}
		if($koBitArray != 0x0) {
			$ret .= " value=\"" . $_POST["kind"] . "\"";
		}
		
		$ret .= " />";
		$ret .= "</div><br />";
		if(($koBitArray & 0x4) == 0x4) {
			$ret .= "<div class=\"error\">Codice errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Codice:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"code\"";
		if(isset($_GET["code"])) {
			if(!is_null($_GET["code"])) {
				if(strlen($_GET["code"])>0) {
					$ret .= " value=\"" . $_GET["code"] . "\"";
				}
			}
		}
		if($koBitArray != 0x0) {
			$ret .= " value=\"" . $_POST["code"] . "\"";
		}
		$ret .= " />";
		$ret .= "</div><br />";
		if(($koBitArray & 0x8) == 0x8) {
			$ret .= "<div class=\"error\">Nome errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Nome:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"name\"";
		if(isset($_GET["name"])) {
			if(!is_null($_GET["name"])) {
				if(strlen($_GET["name"])>0) {
					$ret .= " value=\"" . $_GET["name"] . "\"";
				}
			}
		}
		if($koBitArray != 0x0) {
			$ret .= " value=\"" . $_POST["name"] . "\"";
		}
		$ret .= " />";
		$ret .= "</div><br />";
		if(($koBitArray & 0x10) == 0x10) {
			$ret .= "<div class=\"error\">Quantita' errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Quantita':</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"qty\"";
		if($koBitArray != 0x0) {
			$ret .= " value=\"" . $_POST["qty"] . "\"";
		} else if(isset($_GET["qty"])) {
			if(!is_null($_GET["qty"])) {
				if(strlen($_GET["qty"])>0) {
					$ret .= " value=\"" . $_GET["qty"] . "\"";
				}
			}
		}
		$ret .= " />";
		$ret .= "</div><br />";
		if(($koBitArray & 0x20) == 0x20) {
			$ret .= "<div class=\"error\">Costo errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Costo:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"cost\"";
		if($koBitArray != 0x0) {
			$ret .= " value=\"" . $_POST["cost"] . "\"";
		} else if(isset($_GET["cost"])) {
			if(!is_null($_GET["cost"])) {
				if(strlen($_GET["cost"])>0) {
					$ret .= " value=\"" . $_GET["cost"] . "\"";
				}
			}
		}
		$ret .= " />";
		$ret .= "</div><br />";
		if(($koBitArray & 0x40) == 0x40) {
			$ret .= "<div class=\"error\">Prezzo errato</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Prezzo:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"price\"";
		if($koBitArray != 0x0) {
			$ret .= " value=\"" . $_POST["price"] . "\"";
		} else if(isset($_GET["price"])) {
			if(!is_null($_GET["price"])) {
				if(strlen($_GET["price"])>0) {
					$ret .= " value=\"" . $_GET["price"] . "\"";
				}
			}
		}
		$ret .= " />";
		$ret .= "</div><br />";
		if(($koBitArray & 0x80) == 0x80) {
			$ret .= "<div class=\"error\">Descrizione errata</div>";
			$ret .= "<br />";
		}
		$ret .= "<div class=\"label\">Descrizione:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"description\"";
		if($koBitArray != 0x0) {
			$ret .= " value=\"" . $_POST["description"] . "\"";
		} else if(isset($_GET["description"])) {
			if(!is_null($_GET["description"])) {
				if(strlen($_GET["description"])>0) {
					$ret .= " value=\"" . $_GET["description"] . "\"";
				}
			}
		}
		$ret .= " />";
		$ret .= "</div><br />";
		$ret .= "<div class=\"submit\">";
		$ret .= "<input type=\"submit\" value=\"Salva\" />";
		$ret .= "</div>";
		$ret .= "</form>";
		$ret .= "</div>";

		return $ret;
	}
}