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
 * along with phpmywhs.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
class Php_MyPhpWarehouse implements Php_AppInterface {
	
	public function myMain() {
		$testSQL = '';
		$settingsFact = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance();
		$dbFact = Php_AndreaBoccaccio_Db_DbFactory::getInstance();
		$settings = $settingsFact->getSettings('xml');
		$db = $dbFact->getDb($settings->getSettingFromFullName('classes.db'));
		$test = array();
		echo "<html>\n";
		echo "<body>\n";
		echo "<div>\n";
		echo "This is a quite incomplete custom application about a warehouse\n";
		echo "</div>\n";
		$testSQL = "DROP TABLE IF EXISTS INVENTORY_ITEMS;";
		$test = $db->execQuery($testSQL);
		echo "<div>\n";
		echo "$testSQL gives";
		echo "<pre>\n";
		echo htmlentities(var_dump($test));
		echo "</pre>\n";
		echo "</div>\n";
		$testSQL = "CREATE TABLE IF NOT EXISTS INVENTORY_ITEMS (";
		$testSQL .= "id BIGINT AUTO_INCREMENT PRIMARY KEY";
		$testSQL .= " ,code VARCHAR(20)";
		$testSQL .= " ,name VARCHAR(25)";
		$testSQL .= " ,description VARCHAR(50)";
		$testSQL .= ");";
		$test = $db->execQuery($testSQL);
		echo "<div>\n";
		echo "$testSQL gives";
		echo "<pre>\n";
		echo htmlentities(var_dump($test));
		echo "</pre>\n";
		echo "</div>\n";
		$testSQL = "INSERT INTO INVENTORY_ITEMS (code,name,description) VALUES (";
		$testSQL .= " '123456A01b01'";
		$testSQL .= " ,'test'";
		$testSQL .= " ,'test01'";
		$testSQL .= ");";
		$test = $db->execQuery($testSQL);
		echo "<div>\n";
		echo "$testSQL gives";
		echo "<pre>\n";
		echo htmlentities(var_dump($test));
		echo "</pre>\n";
		echo "</div>\n";
		$testSQL = "INSERT INTO INVENTORY_ITEMS (code,name,description) VALUES (";
		$testSQL .= " '123456A02b02'";
		$testSQL .= " ,'testb'";
		$testSQL .= " ,'test02'";
		$testSQL .= ");";
		$test = $db->execQuery($testSQL);
		echo "<div>\n";
		echo "$testSQL gives";
		echo "<pre>\n";
		echo htmlentities(var_dump($test));
		echo "</pre>\n";
		echo "</div>\n";
		$testSQL = "SELECT * FROM INVENTORY_ITEMS;";
		$test = $db->execQuery($testSQL);
		echo "<div>\n";
		echo "$testSQL gives";
		echo "<pre>\n";
		echo htmlentities(var_dump($test));
		echo "</pre>\n";
		echo "</div>\n";
		echo "<div>";
		echo "<p>";
		echo "<a href=\"https://github.com/andreaboccaccio/phpmywhs\">";
		echo "here";
		echo "</a>"; 
		echo "you can find the source code";
		echo "</p>";
		echo "</div>";
		echo "</body>\n";
		echo "</html>\n";
	}
}