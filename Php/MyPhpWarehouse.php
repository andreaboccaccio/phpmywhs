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
		$securityLevel = 100;
		$sessionCode = '';
		$newSessionCode = '';
		$usr = '';
		$pwd = '';
		$loggedIn = 0;
		$settingsFact = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance();
		$loginFact = Php_AndreaBoccaccio_Login_LoginFactory::getInstance();
		$dbFact = Php_AndreaBoccaccio_Db_DbFactory::getInstance();
		$settings = $settingsFact->getSettings('xml');
		$db = $dbFact->getDb($settings->getSettingFromFullName('classes.db'));
		$login = $loginFact->getLogin($settings->getSettingFromFullName('classes.login'));
		$mytime = time();
		if(isset($_COOKIE["phpmywhssession"])) {
			$sessionCode = $_COOKIE["phpmywhssession"];
			$sessionCode = $db->sanitize($sessionCode);
			$newSessionCode = $login->getNewSessionCode(null,null,$sessionCode);
		}
		else if((isset($_GET["doLogin"]))&&(isset($_POST["usr"]))&&(isset($_POST["pwd"]))) {
			if((!is_null($_GET["doLogin"]))&&(!is_null($_POST["usr"]))&&(!is_null($_POST["pwd"]))) {
				if((strncmp($_GET["doLogin"],'yes',strlen('yes'))==0)&&(strlen($_POST["usr"])>0)&&(strlen($_POST["pwd"])>0)) {
					$usr = $db->sanitize($_POST["usr"]);
					$pwd = $db->sanitize($_POST["pwd"]);
					$newSessionCode = $login->getNewSessionCode($usr,$pwd,null);
				}
			}
		}
		if($login->getUserLevel($newSessionCode)>=$securityLevel)
		{
			setcookie("phpmywhssession",$newSessionCode,time()+intval($settings->getSettingFromFullName('session.persistence'))+120*60);
			$loggedIn = 1;
		}
		else {
			setcookie("phpmywhssession",'',time()-(60*60*24*30));
			$loggedIn = 0;
		}
		
		echo "<html>\n";
		echo "<body>\n";
		echo "<div id=\"banner\">\n";
		echo "phpmywhs: a quite incomplete custom application about a warehouse\n";
		echo "</div>\n";
		if($loggedIn) {
			echo "<div id=\"menu\">\n";
			echo "<div id=\"logout\">\n";
			echo "Esci";
			echo "</div>\n";
		}
		echo "<div id=\"body\">";
		if($loggedIn) {
			echo "Welcome";
			echo "<p>" . $mytime ."</p>";
			echo "<p>" . strftime('%Y%m%d %H%M%S',$mytime) ."</p>";
			echo "<p>" . gmstrftime('%Y%m%d %H%M%S',$mytime) ."</p>";
		}
		else {
			echo "<form method=\"post\" action=\"";
			echo $_SERVER["PHP_SELF"];
			echo "?doLogin=yes\"> ";
			echo "<p>";
			echo "User:";
			echo "<input type=\"text\" name=\"usr\" />";
			echo "</p>";
			echo "<p>";
			echo "Password:";
			echo "<input type=\"password\" name=\"pwd\" />";
			echo "</p>";
			echo "<input type=\"submit\" value=\"Login\" />";
			echo "</form>";
		}
		echo "</div>";
		echo "<div id=\"footer\">";
		echo "<p>";
		echo "<a href=\"https://github.com/andreaboccaccio/phpmywhs\">";
		echo "here";
		echo "</a>"; 
		echo " you can find the source code";
		echo "</p>";
		echo "</div>";
		echo "</body>\n";
		echo "</html>\n";
	}
}