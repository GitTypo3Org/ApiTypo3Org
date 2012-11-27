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

class Docset extends BaseTask {

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
	protected $current = '';

	/**
	 *
	 * @var string
	 */
	protected $version = '';


	/**
	 * Prepare commands to generate Docset.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();
		$this->log('generating Docset file...');

		$commands = array();
		$commands[] = 'echo 123';
		$commands[] = 'cd ' . $this->apiPath . $this->version . '/html';

		$commands[] = 'make';
		$commands[] = 'tar -czf Typo3.tgz org.doxygen.Project.docset';
		$commands[] = 'cp Typo3.tgz ' . $this->archivePath . 'Typo3.docset.tgz' ;
		$commands[] = 'mv Typo3.tgz ' . $this->wwwPath;


$content = <<<EOF
<entry>
    <version>TYPO3 4.7</version>
    <url>http://api.typo3.org/docsets/Typo3.tgz</url>
</entry>
EOF;
		# @todo
		#file_put_contents($content, $this->wwwPath . 'feeds/Typo3.xml');

		$this->execute($commands);
	}

	/**
	 * Returns the exlude paths
	 *
	 * @param array $source the project data source
	 * @return string
	 */
	protected function getExcludePath($source) {

		$excludePatterns[] = $source . '/typo3/contrib';
		$excludePatterns[] = $source . '/typo3/sysext/adodb';
		return implode(" \\ \n", $excludePatterns);
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
    public function setSource($source){
        $this->source = $source;
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
     * Setter for current
	 *
     * @param string $current
     * @return void
     */
    public function setCurrent($current){
        $this->current = $current;
    }

    /**
     * Setter for version
	 *
     * @param string $version
     * @return void
     */
    public function setVersion($version){
        $this->version = $version;
    }

}

?>