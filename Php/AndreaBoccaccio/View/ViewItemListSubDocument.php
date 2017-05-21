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
class Php_AndreaBoccaccio_View_ViewItemListSubDocument extends Php_AndreaBoccaccio_View_ViewConsistentListAbstract {

	private static $instance = null;

	private function __clone() {

	}

	private function __construct() {
		$this->setKind('itemList');
	}

	public static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new Php_AndreaBoccaccio_View_ViewItemListSubDocument();
		}
		return self::$instance;
	}

	public function getMenu() {
		$ret = parent::getMenu();

		$ret .= "<div id=\"itemListMain\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=main\">Principale</a>";
		$ret .= "</div>\n";
		$ret .= "<div id=\"itemListDoc\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=doc&id=";
		$ret .= $_GET["docId"];
		$ret .= "\">Documento</a>";
		$ret .= "</div>\n";
		$ret .= "<div id=\"itemListItemNew\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=itemNew&docId=";
		$ret .= $_GET["docId"];
		$ret .= "\">Nuovo Articolo</a>";
		$ret .= "</div>\n";
		$ret .= "<div id=\"docItemNew\" class=\"menuentry\">\n";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?op=itemInWizard&docId=";
		$ret .= $_GET["docId"];
		$ret .= "\">Nuovo Articolo Guidato</a>";
		$ret .= "</div>\n";
		$ret .= "</div>\n";

		return $ret;
	}

	public function getBody() {
		$ret = '';
		$requestedPage = 0;
		$filter = array();
		$orderby = null;
		$myGP = array();
		$tmpRes = array();
		$itemDenormMan = new Php_AndreaBoccaccio_Model_ItemManager();
		$itemDenorms = array();
		$actualPage = -1;
		$rowsPerPage = -1;
		$totalRows = -1;
		$totalPages = -1;
		$i = -1;
		$max = count($itemDenorms);
		$myGetWhere = $this->getWhere();
		$getWherePrefix = '';
		$myGetOrder = $this->getOrder();
		$getOrderPrefix = '';
		
		if(strlen(trim($myGetWhere))>0) {
			$getWherePrefix = '&';
		}
		else {
			$getWherePrefix = '';
		}
		if(strlen(trim($myGetOrder))>0) {
			$getOrderPrefix = '&';
		}
		else {
			$getOrderPrefix = '';
		}
		
		if(isset($_GET["page"])) {
			if(strlen($_GET["page"])>0) {
				$requestedPage = intval($_GET["page"]);
			}
			else {
				$requestedPage = 0;
			}
		}
		else {
			$requestedPage = 0;
		}
		$myGP = array_merge($_GET,$_POST);
		
		if(isset($myGP["docId"])) {
			if(intval($myGP["docId"])>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $myGP["docId"])) {
					$filter["document"] = strval(intval($myGP["docId"]));
				}
			}
		}
		if(isset($myGP["wKind"])) {
			if(trim(strlen($myGP["wKind"]))>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $_POST["wKind"])) {
					$filter["kind"] = trim($myGP["wKind"]);
				}
			}
		}
		if(isset($myGP["wCode"])) {
			if(strlen(trim($myGP["wCode"]))>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $_POST["wCode"])) {
					$filter["code"] = trim($myGP["wCode"]);
				}
			}
		}
		if(isset($myGP["wName"])) {
			if(strlen(trim($myGP["wName"]))>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $_POST["wName"])) {
					$filter["name"] = trim($myGP["wName"]);
				}
			}
		}
		if(isset($myGP["wQty"])) {
			if(strlen(trim($myGP["wQty"]))>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $_POST["wQty"])) {
					$filter["qty"] = trim($myGP["wQty"]);
				}
			}
		}
		if(isset($myGP["wValue"])) {
			if(strlen(trim($myGP["wValue"]))>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $_POST["wValue"])) {
					$filter["value"] = trim($myGP["wValue"]);
				}
			}
		}
		if(isset($myGP["wCost"])) {
			if(strlen(trim($myGP["wCost"]))>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $_POST["wCost"])) {
					$filter["cost"] = trim($myGP["wCost"]);
				}
			}
		}
		if(isset($myGP["wPrice"])) {
			if(strlen(trim($myGP["wPrice"]))>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $_POST["wPrice"])) {
					$filter["price"] = trim($myGP["wPrice"]);
				}
			}
		}
		if(isset($myGP["wDescription"])) {
			if(strlen(trim($myGP["wDescription"]))>0) {
				if(preg_match("/^(?!.*(alter|create|drop|rename|truncate|call|delete|do|handler|insert|load|replace|select|update)).*$/i", $_POST["wDescription"])) {
					$filter["description"] = trim($myGP["wDescription"]);
				}
			}
		}
		if(isset($myGP["orderby"])) {
			if(strlen(trim($myGP["orderby"]))>0) {
				$orderby = trim($myGP["orderby"]);
			}
			else {
				$orderby = null;
			}
		}
		else {
			$orderby = null;
		}
		
		$tmpRes = $itemDenormMan->getModels($requestedPage,$filter,$orderby);
		$actualPage = $tmpRes["actualPage"];
		$rowsPerPage = $tmpRes["rowsPerPage"];
		$totalRows = $tmpRes["totalRows"];
		$totalPages = $tmpRes["totalPages"];
		$itemDenorms = $tmpRes["result"];
		$max = count($itemDenorms);
		
		$ret .= "<div id=\"body\">";
		$ret .= "<div id=\"listDocKinds\" class=\"list\">";
		$ret .= "<table id=\"tabDocKinds\" class=\"tab\">";
		$ret .= "<tr class=\"tab\">";
		$ret .= "<th class=\"tab\">";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
		$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=0" . $getWherePrefix . $myGetWhere;
		$ret .= "&orderby=" . $this->getNewOrder("kind") . "\"\">Categoria</a>";
		$ret .= "</th>";
		$ret .= "<th class=\"tab\">";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
		$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=0" . $getWherePrefix . $myGetWhere;
		$ret .= "&orderby=" . $this->getNewOrder("code") . "\"\">Codice</a>";
		$ret .= "</th>";
		$ret .= "<th class=\"tab\">";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
		$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=0" . $getWherePrefix . $myGetWhere;
		$ret .= "&orderby=" . $this->getNewOrder("name") . "\"\">Nome</a>";
		$ret .= "</th>";
		$ret .= "<th class=\"tab\">";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
		$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=0" . $getWherePrefix . $myGetWhere;
		$ret .= "&orderby=" . $this->getNewOrder("qty") . "\"\">Quantita'</a>";
		$ret .= "</th>";
		$ret .= "<th class=\"tab\">";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
		$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=0" . $getWherePrefix . $myGetWhere;
		$ret .= "&orderby=" . $this->getNewOrder("cost") . "\"\">Costo u.</a>";
		$ret .= "</th>";
		$ret .= "<th class=\"tab\">";
		$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
		$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=0" . $getWherePrefix . $myGetWhere;
		$ret .= "&orderby=" . $this->getNewOrder("price") . "\"\">Prezzo u.</a>";
		$ret .= "</th>";
		$ret .= "<th class=\"tab\">cancellazione</th>";
		$ret .= "</tr>";
		for($i = 0; $i < $max; ++$i) {
			$ret .= "<tr class=\"tab\">";
			$ret .= "<td class=\"tab\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=item&id=" . $itemDenorms[$i]->getVar('id');
			$ret .= "&docId=" .$itemDenorms[$i]->getVar('document');
			$ret .= "\">" . $itemDenorms[$i]->getVar('kind') ."</a>";
			$ret .= "</td>";
			$ret .= "<td class=\"tab\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=item&id=" . $itemDenorms[$i]->getVar('id');
			$ret .= "&docId=" .$itemDenorms[$i]->getVar('document');
			$ret .= "\">" . $itemDenorms[$i]->getVar('code') ."</a>";
			$ret .= "</td>";
			$ret .= "<td class=\"tab\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=item&id=" . $itemDenorms[$i]->getVar('id');
			$ret .= "&docId=" .$itemDenorms[$i]->getVar('document');
			$ret .= "\">" . $itemDenorms[$i]->getVar('name') ."</a>";
			$ret .= "</td>";
			$ret .= "<td class=\"tab\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=item&id=" . $itemDenorms[$i]->getVar('id');
			$ret .= "&docId=" .$itemDenorms[$i]->getVar('document');
			$ret .= "\">" . $itemDenorms[$i]->getVar('qty') ."</a>";
			$ret .= "</td>";
			$ret .= "<td class=\"tab\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=item&id=" . $itemDenorms[$i]->getVar('id');
			$ret .= "&docId=" .$itemDenorms[$i]->getVar('document');
			$ret .= "\">" . number_format($itemDenorms[$i]->getVar('cost'),2,',','') ."</a>";
			$ret .= "</td>";
			$ret .= "<td class=\"tab\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=item&id=" . $itemDenorms[$i]->getVar('id');
			$ret .= "&docId=" .$itemDenorms[$i]->getVar('document');
			$ret .= "\">" . number_format($itemDenorms[$i]->getVar('price'),2,',','') ."</a>";
			$ret .= "</td>";
			$ret .= "<td class=\"tab\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=item&id=" . $itemDenorms[$i]->getVar('id');
			$ret .= "&docId=" .$itemDenorms[$i]->getVar('document');
			$ret .= "&delete=maybe\">cancella</a>";
			$ret .= "</td>";
			$ret .= "</tr>";
		}
		$ret .= "</table>";
		$ret .= "</div>";
		if($totalPages > 1) {
			$ret .= "<div id=\"listItemPaging\" class=\"paging\">";
			$ret .= "<div id=\"listItemFirstPage\" class=\"firstPage\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=0" . $getWherePrefix . $myGetWhere;
			$ret .= $getOrderPrefix . $myGetOrder . "\"\">Pagina 1</a>";
			$ret .= "</div>";
			$ret .= "<div id=\"listItemPrevPage\" class=\"prevPage\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=" . strval(max((intval($actualPage)-1),0)) . $getWherePrefix . $myGetWhere;
			$ret .= $getOrderPrefix . $myGetOrder . "\"\">Pagina " . strval((max((intval($actualPage)-1),0))+1) . "</a>";
			$ret .= "</div>";
			$ret .= "<div id=\"listItemActualPage\" class=\"actualPage\">";
			$ret .= "Pagina ";
			$ret .= strval(intval($actualPage)+1) . " di " . strval(intval($totalPages));
			$ret .= "</div>";
			$ret .= "<div id=\"listItemNextPage\" class=\"nextPage\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) ."&page=" . strval(min((intval($actualPage)+1),(intval($totalPages)-1))) . $getWherePrefix . $myGetWhere;
			$ret .= $getOrderPrefix . $myGetOrder . "\"\">Pagina " . strval((min((intval($actualPage)+1),(intval($totalPages)-1)))+1) . "</a>";
			$ret .= "</div>";
			$ret .= "<div id=\"listItemLastPage\" class=\"lastPage\">";
			$ret .= "<a href=\"" . $_SERVER["PHP_SELF"];
			$ret .= "?op=itemList&docId=". strval(intval($myGP["docId"])) . "&page=" . strval((intval($totalPages)-1)) . $getWherePrefix . $myGetWhere;
			$ret .= $getOrderPrefix . $myGetOrder . "\"\">Pagina " . strval(intval($totalPages)) . "</a>";
			$ret .= "</div>";
			$ret .= "</div>";
			$ret .= "<br />";
		}
		$ret .= "<div id=\"listItemWhere\" class=\"where\">";
		$ret .= "<form method=\"post\" action=\"";
		$ret .= $_SERVER["PHP_SELF"];
		$ret .= "?op=itemList&docId=". $myGP["docId"] ."&page=0\"> ";
		$ret .= "<div class=\"label\">Categoria:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"wKind\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		$ret .= "<div class=\"label\">Codice:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"wCode\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		$ret .= "<div class=\"label\">Nome:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"wName\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		$ret .= "<div class=\"label\">Descrizione:</div>";
		$ret .= "<div class=\"input\">";
		$ret .= "<input type=\"text\" name=\"wDescription\" />";
		$ret .= "</div>";
		$ret .= "<br />";
		$ret .= "<div class=\"submit\">";
		$ret .= "<input type=\"submit\" value=\"Filtra\" />";
		$ret .= "</div>";
		$ret .= "</form>";
		$ret .= "</div>";
		$ret .= "</div>";

		return $ret;
	}
}