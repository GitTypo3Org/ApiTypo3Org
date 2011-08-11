<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Fabien Udriot <fabien.udriot@ecodev.ch>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * This class is used to download sources
 * 
 * @author Fabien Udriot <fabien.udriot@ecodev.ch>
 *
 * $Id: Download.php 2392 2011-02-15 16:28:16Z fab1en $
 */
require_once('BaseTask.php');

class Download extends BaseTask {

	/**
	 * Generates commands to download different sources related to TYPO3
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();

		foreach ($this->sources as $source) {
			$this->log("downloading latest source of " . $source['label'] . "...");
			$commands = $this->getCommands($source);
			$this->execute($commands);
		}
	}

	/**
	 * Returns the command to be executed to download sources of TYPO3 v4
	 *
	 * @return array
	 */
	protected function getCommands($datasource) {
		$commands = array();

		// commands for trunk
		$sourcePath = $this->sourcePath . $datasource['folderName'] . '/master/';
		if (!is_dir($sourcePath)) {
			$commands[] = 'git clone --recursive --quiet ' . $datasource['repository'] . ' ' . $sourcePath;
			$commands[] = 'git submodule update --init';
		}

		$commands[] = 'cd ' . $sourcePath . '; git checkout master --quiet';
		$commands[] = 'cd ' . $sourcePath . '; git pull --quiet';
		$commands[] = 'cd ' . $sourcePath . '; git fetch --tags --quiet';

		return $commands;
	}

}

?>