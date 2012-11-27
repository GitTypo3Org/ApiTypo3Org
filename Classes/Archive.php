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
 */
require_once('BaseTask.php');

class Archive extends BaseTask {

	/**
	 *
	 * @var string
	 */
	protected $source = '';

	/**
	 *
	 * @var string
	 */
	protected $target = '';

	/**
	 *
	 * @var string
	 */
	protected $version = '';

	/**
	 * Prepare commands to generate API. At the moment, assumes Doxygen will be used to generate the API.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();
		$this->log('Creating archive...');

		$archiveFile = $this->target . $this->version . '.zip';
		$sourceDirectory = $this->source . $this->version;
		$command = 'rm -f ' . $archiveFile . '; cd ' . $this->apiPath . ';zip -rq ' . $archiveFile . ' ' . $this->version;
		$this->execute($command);

		// Sometimes ghost files are created -> removes them
		#$commands = array();
		#$commands[] = 'mv ' . $this->archivePath . '*.zip ' . $this->temporaryPath;
		#$commands[] = 'rm -f ' . $this->archivePath . '*';
		#$commands[] = 'mv ' . $this->temporaryPath . '*.zip ' . $this->archivePath;
		#$this->execute($commands);
	}

	// -------------------------------
	// Set properties from XML
	// -------------------------------

	/**
	 * Setter for source
	 *
	 * @param string $source
	 * @return void
	 */
	public function setSource($source) {
		$this->source = $source;
	}

	/**
	 * Setter for target
	 *
	 * @param string $target
	 * @return void
	 */
	public function setTarget($target) {
		$this->target = $target;
	}

	/**
	 * Setter for version
	 *
	 * @param string $version
	 * @return void
	 */
	public function setVersion($version) {
		$this->version = $version;
	}

}

?>