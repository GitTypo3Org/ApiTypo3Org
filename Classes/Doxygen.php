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
require_once(__DIR__ . '/../Libraries/Template.php');

class Doxygen extends BaseTask {

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
	 * Prepare commands to generate API. At the moment, assumes Doxygen will be used to generate the API.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();
		$this->log('generating Doxygen API...');

		$commands = array();
		$template = new Template($this->homePath . 'Resources/Private/Templates/doxygen.php');

		// computes path
		$template->set('outputPath', $this->target);
		$template->set('inputPath', $this->source);
		$template->set('excludePath', $this->getExcludePath($this->source));
		$template->set('projectNumber', $this->tagName);
		$template->set('projectLogo', $this->homePath . 'Resources/Public/Images/logo.png');

		// computes configuration file name
		$doxygenFile = $this->temporaryPath . $this->tagName . '.conf';

		$content = $template->fetch();
		file_put_contents($doxygenFile, $content);

		$commands[] = $this->doxygenCommand . ' ' . $doxygenFile;
		$commands[] = 'cp ' . $this->homePath . 'Resources/Private/Templates/redirect.php ' . $this->target . '/index.php';

		$this->execute($commands);
	}

	/**
	 * Returns the exlude paths
	 *
	 * @param array $source the project data source
	 * @return string
	 */
	protected function getExcludePath($source) {

		// Add more excluded paths for TYPO3 6.0
		if (preg_match('/typo3-cms-6/', $source)) {
			$excludePatterns[] = $source . '/t3lib';
			$excludePatterns[] = $source . '/typo3/sysext/core/Migrations/Code/LegacyClassesForIde.php';
			$excludePatterns[] = $source . '/typo3/sysext/extbase/Tests';
			$excludePatterns[] = $source . '/typo3/sysext/cms';
			$excludePatterns[] = $source . '/typo3/sysext/cshmanual';
			$excludePatterns[] = $source . '/typo3/sysext/core/Tests';
			$excludePatterns[] = $source . '/typo3/sysext/form/Tests';

			$excludePatterns[] = $source . '/typo3/class.browse_links.php';
			$excludePatterns[] = $source . '/typo3/class.db_list.inc';
			$excludePatterns[] = $source . '/typo3/class.db_list_extra.inc';
			$excludePatterns[] = $source . '/typo3/class.file_list.inc';
			$excludePatterns[] = $source . '/typo3/class.filelistfoldertree.php';
			$excludePatterns[] = $source . '/typo3/class.show_rechis.inc';
			$excludePatterns[] = $source . '/typo3/class.webpagetree.php';
			$excludePatterns[] = $source . '/typo3/show_item.php';
			$excludePatterns[] = $source . '/typo3/move_el.php';
			$excludePatterns[] = $source . '/typo3/wizard_tsconfig.php';
			$excludePatterns[] = $source . '/typo3/db_new.php';
			$excludePatterns[] = $source . '/typo3/sysext/rtehtmlarea/class.tx_rtehtmlareaapi.php';
		}

		$excludePatterns[] = $source . '/typo3/contrib';
		$excludePatterns[] = $source . '/typo3/sysext/openid';
		$excludePatterns[] = $source . '/typo3/sysext/adodb';
		$excludePatterns[] = $source . '/typo3/sysext/fluid';
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