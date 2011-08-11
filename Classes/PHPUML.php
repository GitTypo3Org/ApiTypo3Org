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

class PHPUML extends BaseTask {

	/**
	 * should the documentation be re-generated
	 *
	 * @var boolean
	 */
	protected $force = FALSE;

	/**
	 * Prepare commands to generate API. At the moment, assumes Doxygen will be used to generate the API.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();

		foreach ($this->sources as $source) {

			if ($source['api'] == 'phpuml') {
				$tags = $this->getTags($source);

				foreach ($tags as $tag) {
					$commands = $this->generate($source, $tag);
					if (!empty($commands)) {

						$sourcePath = $this->sourcePath . $source['folderName'] . '/master/';

						$command = 'cd ' . $sourcePath . '; git checkout --quiet ' . $tag;
						array_unshift($commands, $command);
						$this->log('generating PHP UML documentation for ' . $source['name'] . ' ' . $this->getProjectNumber($tag));
						$this->execute($commands);
					}
				}
			}
		}
	}

	/**
	 * Get Tags name
	 *
	 * @param array $source the project data source
	 * @return array
	 */
	protected function getTags($source) {
		$tags = array('master');

		$command = 'cd ' . $this->sourcePath . $source['folderName'] . '/master ; git tag';
		exec($command, $result);
		foreach ($result as $tag) {
			if (preg_match('/([0-9]\.[0-9]\.[0-9])$/is', $tag, $match)) {
				$version = $match[0];
				$numberOfVersion = str_replace('.', '', $match[1]);
				$version = $match[0];

				// possible minium version
				if (!empty($source['minimumVersion'])) {
					if ($numberOfVersion >= $source['minimumVersion']) {
						$tags[] = $version;
					}
				} else {
					$tags[] = $version;
				}
			}
		}
		return $tags;
	}

	/**
	 * Generate Doxygen commands that will be executed later on.
	 *
	 * @param array $source the project data source
	 * @param string $tag the tag of the source
	 * @return array the commands to be executed
	 */
	protected function generate($source, $tag) {
		$commands = array();
		$template = new Template($this->homePath . 'Resources/Private/Templates/doxygen.php');

		$outputPath = $this->getOutputPath($source, $tag);

		if (!is_dir($outputPath) || $this->force) {

			$command = '';
			$command .= 'require_once( "' . $this->homePath . 'Libraries/PHP_UML/UML.php");';
			$command .= 'mkdir("' . $outputPath . '");';
			$command .= '$renderer = new PHP_UML();';
			$command .= '$renderer->deploymentView = FALSE;';
			$command .= '$renderer->onlyApi = TRUE;';
			$command .= '$renderer->structureFromDocblocks = TRUE;';
			#$command .= '$renderer->completeAPI = FALSE;';
			$command .= '$renderer->setInput(array("' . $this->sourcePath . $source['folderName'] . '/master"));';
			$command .= '$renderer->parse("' . $this->getProjectNumber($tag) . '");';
			$command .= '$renderer->generateXMI(2.1, "utf-8");';
			$command .= '$renderer->export("html", "' . $outputPath . '");';
			$commands[] = "php -r '" . $command . "'";
		}

		return $commands;
	}

	/**
	 * Returns the input path
	 *
	 * @param string $segment a segment path containing the number of version
	 * @return string
	 */
	protected function getProjectNumber($segment) {
		$searches[] = $this->sourcePath;
		$searches[] = 'TYPO3_';
		$searches[] = 'TYPO3';
		$searches[] = '-';
		$searches[] = '/';
		$searches[] = 'v4';

		$replaces[] = '';
		$replaces[] = '';
		$replaces[] = '';
		$replaces[] = '.';
		$replaces[] = ' ';
		$replaces[] = '';
		return str_replace($searches, $replaces, $segment);
	}

	/**
	 * Returns the output path
	 *
	 * @param array $source the project data source
	 * @param string $segment a segment path containing the number of version
	 * @return string
	 */
	protected function getOutputPath($source, $segment) {
		$searches[] = $this->sourcePath;
		$searches[] = 'TYPO3_';
		$searches[] = '-';
		$replaces[] = '';
		$replaces[] = '';
		$replaces[] = '.';
		return $this->apiPath . $source['folderName'] . '/' . str_replace($searches, $replaces, $segment);
	}

}

?>