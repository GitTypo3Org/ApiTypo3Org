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

class Deploy extends BaseTask {
	
	/**
	 *
	 * @var boolean
	 */
	protected $current = FALSE;
	
	/**
	 *
	 * @var string
	 */
	protected $version = '';
	
	/**
	 *
	 * @var string
	 */
	protected $deployPath = '';
	
	/**
	 *
	 * @var string
	 */
	protected $deployPathName = '';

	/**
	 * Prepare commands to generate API. Assumes Doxygen will be used to generate the API for now.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();
		$this->log('deploying documentation...');

		// Update the ZIP repository
		$commands[] = 'echo "Options +Indexes -FollowSymLinks -Includes" > ' . $this->wwwPath . 'archives/.htaccess';
		
		$archiveFile = $this->archivePath . $this->version . '.zip';
		$commands[] = 'rsync -a ' . $archiveFile . ' ' . $this->wwwPath . 'archives';
		
		
		$apiDirectory = $this->apiPath . $this->version;
		$commands[] = 'rsync -a --delete ' . $apiDirectory . '/ ' . $this->wwwPath . $this->deployPath . '/' . $this->deployPathName;

		if ($this->current) {
			$commands[] = 'rsync -a --delete ' . $apiDirectory . '/ ' . $this->wwwPath . $this->deployPath . '/current';
		}

		$this->execute($commands);
	}

	// -------------------------------
	// Set properties from XML
	// -------------------------------

	/**
	 * Setter for version
	 *
	 * @param string $version
	 * @return void
	 */
	public function setVersion($version) {
		$this->version = $version;
	}

	/**
	 * Setter for deployPath
	 *
	 * @param string $deployPath
	 * @return void
	 */
	public function setDeployPath($deployPath) {
		$this->deployPath = $deployPath;
	}
	
	/**
	 * Setter for deployPathName
	 *
	 * @param string $deployPathName
	 * @return void
	 */
	public function setDeployPathName($deployPathName) {
		$this->deployPathName = $deployPathName;
	}
	
	/**
	 * Setter for current
	 *
	 * @param string $current
	 * @return void
	 */
	public function setCurrent($current) {
		$this->current = $current;
	}

}

?>