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
	 *
	 * @var string
	 */
	protected $repository = '';
	
	/**
	 *
	 * @var string
	 */
	protected $target = '';
	
	/**
	 *
	 * @var string
	 */
	protected $tagName = '';
	
	/**
	 *
	 * @var string
	 */
	protected $submoduleDirectory = '';
	
	/**
	 * Generates commands to download different sources related to TYPO3
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();
		$this->log('downloading latest source...');
		
		if (!is_dir($this->target)) {
			$commands[] = 'git clone --recursive --quiet ' . $this->repository . ' ' . $this->target;
		}

		// needs to switch to the master before it can make a pull...
		if ($this->submoduleDirectory) {
			$commands[] = 'rm -rf ' . $this->submoduleDirectory;
			$commands[] = 'cd ' . $this->target . '; git reset --hard --quiet';
			$commands[] = 'cd ' . $this->target . '; git checkout master --quiet';
			$commands[] = 'cd ' . $this->target . '; git submodule update --quiet';
		}

		$commands[] = 'cd ' . $this->target . '; git pull --quiet';
		$commands[] = 'cd ' . $this->target . '; git fetch --tags --quiet';
		if ($this->submoduleDirectory) {
			$commands[] = 'rm -rf ' . $this->submoduleDirectory;
			$commands[] = 'cd ' . $this->target . '; git reset --hard --quiet';
		}
		$commands[] = 'cd ' . $this->target . '; git checkout ' . $this->tagName . ' --quiet';
		$commands[] = 'cd ' . $this->target . '; git submodule update --quiet';
		
		// execute commands
		$this->execute($commands);
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
		}

		$commands[] = 'cd ' . $sourcePath . '; git checkout master --quiet';
		$commands[] = 'cd ' . $sourcePath . '; git pull --quiet';
		$commands[] = 'cd ' . $sourcePath . '; git fetch --tags --quiet';
		$commands[] = 'cd ' . $sourcePath . '; git submodule update --quiet';

		return $commands;
	}
	
	// -------------------------------
    // Set properties from XML
    // -------------------------------

    /**
     * Setter for repository
	 *
     * @param string $repository
     * @return void
     */
    public function setRepository($repository){
        $this->repository = $repository;
	}

    /**
     * Setter for target
	 *
     * @param string $target
     * @return void
     */
    public function setTarget($target){
        $this->target = $target;
    }
	
    /**
     * Setter for tagName
	 *
     * @param string $tagName
     * @return void
     */
    public function setTagName($tagName){
        $this->tagName = $tagName;
    }
	
    /**
     * Setter for submoduleDirectory
	 *
     * @param string $submoduleDirectory
     * @return void
     */
    public function setSubmoduleDirectory($submoduleDirectory){
        $this->submoduleDirectory = $submoduleDirectory;
    }
}

?>