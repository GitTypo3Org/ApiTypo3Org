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
	 * Prepare commands to generate API. At the moment, assumes Doxygen will be used to generate the API.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();

		foreach ($this->sources as $source) {

			$apiFolder = $this->apiPath . $source['folderName'] . '/';

			if (is_dir($apiFolder)) {

				$versions = glob($apiFolder . '*');
				foreach ($versions as $version) {
					$zipFile = $this->getZipPath($version);
					if (!is_file($zipFile) || $this->force) {
						$this->log("compressing documentation of " . $source['label'] . " " . $this->getVersion($source, $version) . "...");
						$command = 'rm -f ' . $zipFile . '; cd ' . $this->apiPath . ';zip -rq ' . $zipFile . ' ' . $this->getVersionToZip($version);
						$this->execute($command);
					}
				}
			}
		}

		// Sometimes ghost files are created -> removes them
		$commands = array();
		$commands[] = 'mv ' . $this->archivePath . '*.zip ' . $this->temporaryPath;
		$commands[] = 'rm -f ' . $this->archivePath . '*';
		$commands[] = 'mv ' . $this->temporaryPath . '*.zip ' . $this->archivePath;
		$this->execute($commands);
	}

	/**
	 * Returns the zip file
	 *
	 * @param string $segment a segment path containing the number of version
	 * @return string
	 */
	protected function getZipPath($segment) {
		$searches[] = $this->apiPath;
		$searches[] = 'TYPO3_';
		$searches[] = '-';
		$searches[] = '/';

		$replaces[] = '';
		$replaces[] = '';
		$replaces[] = '.';
		$replaces[] = '-';
		return $this->archivePath . str_replace($searches, $replaces, $segment) . '.zip';
	}

	/**
	 * Returns the zip file
	 *
	 * @param string $segment a segment path containing the number of version
	 * @return string
	 */
	protected function getVersionToZip($segment) {
		$searches[] = $this->apiPath;

		$replaces[] = '';
		return str_replace($searches, $replaces, $segment);
	}

	/**
	 * Returns the zip file
	 *
	 * @param array $source
	 * @param string $segment a segment path containing the number of version
	 * @return string
	 */
	protected function getVersion($source, $segment) {
		$searches[] = $this->apiPath;
		$searches[] = $source['folderName'];
		$searches[] = '/';

		$replaces[] = '';
		$replaces[] = '';
		$replaces[] = '';
		return str_replace($searches, $replaces, $segment);
	}

}

?>