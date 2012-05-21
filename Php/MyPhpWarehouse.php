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
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
class Php_MyPhpWarehouse implements Php_AppInterface {
	
	public function myMain() {
		$settingsFact = Php_AndreaBoccaccio_Settings_SettingsFactory::getInstance();
		$settings = $settingsFact->getSettings('xml');
		$prova = $settings->getSettingFromFullName('prova');
		echo "<html>\n";
		echo "<body>\n";
		echo "<div>\n";
		echo "This is a quite incomplete custom application about a warehouse\n";
		echo "</div>\n";
		echo "<div>\n";
		echo "getSettingFromFullName('prova') gives";
		echo "<pre>\n";
		echo htmlentities($prova);
		echo "</pre>\n";
		echo "</div>\n";
		echo "</body>\n";
		echo "</html>\n";
	}
}