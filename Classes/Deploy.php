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
	 * Prepare commands to generate API. Assumes Doxygen will be used to generate the API for now.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();

		$this->log('deploying documentation...');

		// Update the ZIP repository
		$commands[] = 'rsync -a ' . $this->buildPath . 'Temporary/master.conf ' . $this->wwwPath . 'sample.conf';
		$commands[] = 'rsync -a --delete ' . $this->archivePath . ' ' . $this->wwwPath . 'archives';
		$commands[] = 'echo "Options +Indexes -FollowSymLinks -Includes" > ' . $this->wwwPath . 'archives/.htaccess';

		// Update the API repository
		$projects = glob($this->apiPath . '*');

		foreach ($projects as $project) {
			// Reset variable
			$projectName = '';
			$versions = glob($project . '/*');

			// extract the master version which is the lastest version in the folder
			$trunkVersion = array_pop($versions);
			preg_match('/([\w]+)\/master$/is', $trunkVersion, $matches);

			if (!empty($matches[1])) {
				$projectName = strtolower($matches[1]);
				$commands[] = 'rsync -a --delete ' . $trunkVersion . '/ ' . $this->wwwPath . $projectName . '/master';
			}

			$deployedVersions = array();
			$versionNumber = '';
			foreach (array_reverse($versions) as $version) {
				preg_match('/[\w]+\/([0-9]+)\.([0-9]+)\.([0-9]+)$/is', $version, $matches);
				$versionNumberToTest = $matches[1] . $matches[2];
				if (!$versionNumber || $versionNumber != $versionNumberToTest) {
					$deployedVersions[$versionNumberToTest] = $version;
					$versionNumber = $versionNumberToTest;
				}
			}

			$isFirst = TRUE;
			foreach ($deployedVersions as $versionNumber => $version) {
				if ($isFirst) {

					$commands[] = 'rsync -a --delete ' . $version . '/ ' . $this->wwwPath . $projectName . '/current';
					$isFirst = FALSE;
				}
				$commands[] = 'rsync -a --delete ' . $version . '/ ' . $this->wwwPath . $projectName . '/' . $versionNumber;
			}
		}

		$this->execute($commands);
	}

	/**
	 * Returns the zip file
	 *
	 * @param string $segment a segment path containing the number of version
	 * @return string
	 */
	protected function getZipPath($segment) {
		$searches[] = $this->outputPath;
		$searches[] = 'TYPO3_';
		$searches[] = '-';
		$searches[] = '/';

		$replaces[] = '';
		$replaces[] = '';
		$replaces[] = '.';
		$replaces[] = '-';
		return $this->archivePath . str_replace($searches, $replaces, $segment) . '.zip';
	}

}

?>