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
	protected $tagName = '';

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
		$this->log('generating PHP UML API...');

		$outputPath = $this->source;

		$command = '';
		$command .= 'require_once( "' . $this->homePath . 'Libraries/PHP_UML/UML.php");';
		$command .= 'mkdir("' . $this->target . '");';
		$command .= '$renderer = new PHP_UML();';
		$command .= '$renderer->deploymentView = FALSE;';
		$command .= '$renderer->onlyApi = TRUE;';
		$command .= '$renderer->structureFromDocblocks = TRUE;';
		#$command .= '$renderer->completeAPI = FALSE;';
		$command .= '$renderer->setInput(array("' . $outputPath . '"));';
		$command .= '$renderer->parse("' . $this->tagName . '");';
		$command .= '$renderer->generateXMI(2.1, "utf-8");';
		$command .= '$renderer->export("html", "' . $this->target . '");';
		$commands[] = "php -r '" . $command . "'";

		$this->execute($commands);
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
     * Setter for tagName
	 *
     * @param string $tagName
     * @return void
     */
    public function setTagName($tagName){
        $this->tagName = $tagName;
    }
}

?>