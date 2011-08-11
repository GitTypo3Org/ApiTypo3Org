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
abstract class BaseTask extends Task {

	/**
	 * @var array
	 */
	protected $sources = array();
	/**
	 * @var string
	 */
	protected $homePath;
	/**
	 * @var boolean
	 */
	protected $dryRun = FALSE;
	/**
	 * @var boolean
	 */
	protected $force = FALSE;
	/**
	 * @var boolean
	 */
	protected $verbose = FALSE;

	/**
	 * Initialize project
	 *
	 * @return void
	 */
	protected function checkEnvironment() {

		// Makes sure it is possible to run the deploy script
		if (!file_exists($this->wwwPath)) {
			throw new Exception("Exception thrown #1307799630:\n\n directory does not exist : \"" . $this->wwwPath . "\"\n\n", 1307799630);
		}
		if (!file_exists($this->homePath)) {
			throw new Exception("Exception thrown #1307800219:\n\n directory does not exist : \"" . $this->homePath . "\"\n\n", 1307800219);
		}
		if (!file_exists($this->sourcePath)) {
			throw new Exception("Exception thrown #1307800368:\n\n directory does not exist : \"" . $this->sourcePath . "\"\n\n", 1307800368);
		}
		if (!file_exists($this->buildPath)) {
			throw new Exception("Exception thrown #1307799623:\n\n directory does not exist : \"" . $this->buildPath . "\"\n\n", 1307799623);
		}
		if (!file_exists($this->temporaryPath)) {
			throw new Exception("Exception thrown #1307800240:\n\n directory does not exist : \"" . $this->temporaryPath . "\"\n\n", 1307800240);
		}
		if (!file_exists($this->archivePath)) {
			throw new Exception("Exception thrown #1307799650:\n\n directory does not exist : \"" . $this->archivePath . "\"\n\n", 1307799650);
		}
		if (!file_exists($this->apiPath)) {
			throw new Exception("Exception thrown #1307800264:\n\n directory does not exist : \"" . $this->apiPath . "\"\n\n", 1307800264);
		}
	}

	/**
	 * Initialize project
	 *
	 * @return void
	 */
	public function initialize() {

		// Get project properties
		$this->properties = $this->project->getProperties();

		// Define a some paths
		$this->wwwPath = $this->properties['path.www'];
		$this->homePath = $this->properties['path.home'];
		$this->buildPath = $this->properties['path.build'];
		$this->sourcePath = $this->properties['path.source'];
		$this->temporaryPath = $this->buildPath . 'Temporary/';
		$this->apiPath = $this->buildPath . 'Output/Api/';
		$this->archivePath = $this->buildPath . 'Output/Zip/';

		// Set boolean
		if ($this->properties['dryRun'] === 'true' || $this->properties['dryRun'] === TRUE) {
			$this->dryRun = TRUE;
		}

		if ($this->properties['force'] === 'true' || $this->properties['force'] === TRUE) {
			$this->force = TRUE;
		}

		$this->doxygenCommand = $this->properties['command.doyxgen'];



		if (!file_exists($this->homePath)) {
			throw new Exception("Exception thrown #1307637157:\n\n home directory does not exist : \"" . $this->homePath . "\"\n\n", 1307637157);
		}

		// Define sources
		$this->sources = array(
			array(
				'name' => 'FLOW3',
				'label' => 'FLOW3',
				'folderName' => 'FLOW3',
				'repository' => 'git://git.typo3.org/FLOW3/Packages/FLOW3.git',
				'api' => 'phpuml',
				'minimumVersion' => '',
			),
			array(
				'name' => 'TYPO3',
				'label' => 'TYPO3 v4',
				'folderName' => 'TYPO3v4',
				'repository' => 'git://git.typo3.org/TYPO3v4/Core.git',
				'api' => 'doxygen',
				'minimumVersion' => '453',
			),
			array(
				'name' => 'Extbase',
				'label' => 'Extbase',
				'folderName' => 'Extbase',
				'repository' => 'git://git.typo3.org/TYPO3v4/CoreProjects/MVC/extbase.git',
				'api' => 'phpuml',
				'minimumVersion' => '120',
			),
			array(
				'name' => 'Fluid',
				'label' => 'Fluid',
				'folderName' => 'Fluid',
				'repository' => 'git://git.typo3.org/TYPO3v4/CoreProjects/MVC/fluid.git',
				'api' => 'phpuml',
				'minimumVersion' => '120',
			),
		);

		$this->checkEnvironment();
	}

	/**
	 * This method is used to generate the commands to be executed in the handler
	 *
	 * @return void
	 */
//	abstract public function perform();

	/**
	 * This method is used to execute the commands
	 *
	 * @param mixed $commands
	 * @return array
	 */
	public function execute($commands) {

		// dryRun will output the message
		if (is_string($commands)) {
			$commands = array($commands);
		}

		$result = array();
		foreach ($commands as $command) {
			if ($this->dryRun) {
				$this->log($command);
			} elseif ($this->verbose) {
				system($command, $result);
			} else {
				exec($command, $result);
			}
		}
		return $result;
	}

	/**
	 * This method is used to log.
	 *
	 * @return void
	 */
	public function log($message = '') {
		if (is_array($message)) {
			foreach ($message as $line) {
				$this->project->log('     [' . $this->taskName . '] ' . trim($line));
			}
		} else {
			$this->project->log('     [' . $this->taskName . '] ' . trim($message));
		}
	}

	/**
	 * This method is used to debug.
	 *
	 * @return void
	 */
	public function debug($message = '') {
		$this->project->log('      []');
		if (is_array($message) || is_object($message)) {
			print_r($message);
		} elseif (is_bool($message)) {
			var_dump($message);
		} else {
			print $message . chr(10);
		}
	}

}

?>