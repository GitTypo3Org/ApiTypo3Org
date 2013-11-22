<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Xavier Perseguers <xavier@causal.ch>
 *  All rights reserved
 *
 *  You can redistribute this script and/or modify it under the
 *  terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * This class is used to generate an index objects.inv compatible
 * with Intersphinx facilty from Sphinx from the XML API documentation
 * from Doxygen.
 *
 * @author Xavier Perseguers <xavier@causal.ch>
 *
 */
require_once('BaseTask.php');

class Intersphinx extends BaseTask {

	/** @var string */
	protected $directoryXml;

	/** @var string */
	protected $directoryOutput;

	/** @var string */
	protected $projectName;

	/** @var string */
	protected $projectVersion;

	/** @var array */
	protected $indexEntries;

	/**
	 * Generates the objects.inv index file.
	 *
	 * @return void
	 */
	public function main() {
		// Initialize task
		$this->initialize();
		$this->log('generating Intersphinx mapping...');

		$this->indexEntries = array();
		$this->addGenericIndexEntries();
		$inputFiles = $this->findXmlFiles();

		foreach ($inputFiles as $file) {
			//$this->debug('Processing ' . $file);
			$this->processXmlFile($file);
		}

		$this->writeIndexFile();
	}

	/**
	 * Finds XML files starting with $prefix.
	 *
	 * @param string $prefix
	 * @return string[]
	 */
	protected function findXmlFiles($prefix = 'class_') {
		$xmlFiles = array();
		$files = glob($this->directoryXml . '*.xml');
		foreach ($files as $file) {
			if (substr($file, strlen($this->directoryXml), strlen($prefix)) === $prefix) {
				$xmlFiles[] = $file;
			}
		}
		return $xmlFiles;
	}

	/**
	 * Adds a few generic index entries.
	 *
	 * @return void
	 */
	protected function addGenericIndexEntries() {
		$entries = array();

		// Intersphinx
		$entries[] = array('modindex',       'annotated.html',      'Classes');
		$entries[] = array('genindex',       'classes.html',        'Class Index');

		// Doxygen
		$entries[] = array('namespaces',     'namespaces.html',     'Namespaces');
		$entries[] = array('hierarchy',      'hierarchy.html',      'Class Hierarchy');
		$entries[] = array('functions',      'functions.html',      'Class Members');
		$entries[] = array('functions-func', 'functions_func.html', 'Functions');
		$entries[] = array('variables',      'functions_vars.html', 'Variables');
		$entries[] = array('deprecated',     'deprecated.html',     'Deprecated List');
		$entries[] = array('todo',           'todo.html',           'Todo List');
		$entries[] = array('test',           'test.html',           'Test List');
		$entries[] = array('pages',          'pages.html',          'Related Pages');
		$entries[] = array('examples',       'examples.html',       'Examples');

		// TYPO3 Documentation Team
		$entries[] = array('start',          'index.html',          $this->projectName);

		foreach ($entries as $entry) {
			if (file_exists($this->directoryOutput . $entry[1])) {
				$this->createIndexEntry($entry[0], $entry[1] . '#', $entry[2]);
			}
		}
	}

	/**
	 * Processes an XML file and generate objects.inv index entries.
	 *
	 * @param string $xmlFile
	 * @return void
	 */
	protected function processXmlFile($xmlFile) {
		$doc = new DOMDocument;
		@$doc->Load($xmlFile);
		$xpath = new DOMXPath($doc);

		$file = $this->xpathValue($xpath, '//doxygen/compounddef/@id');
		$classInternalName = $this->xpathValue($xpath, '//doxygen/compounddef/compoundname');
		$className = str_replace('::', '\\', $classInternalName);
		$labelClassName = strpos($className, '\\') !== FALSE ? '\\' . $className : $className;

		$classAnchor = strtolower($className);
		$this->createIndexEntry($classAnchor, $file . '.html#', $labelClassName);

		$ids = $this->xpathValues($xpath, '//doxygen/compounddef//memberdef[@kind=\'function\']/@id');
		foreach ($ids as $id) {
			# Beware there's a "1" (for colon) at the beginning of the anchor: <file>_1<anchor-hash>
			$anchorHash = substr($id, strlen($file) + 2);
			$method = $this->xpathValue($xpath, '//doxygen/compounddef//memberdef[@id=\'' . $id . '\']/name');
			if (!empty($method)) {
				// Pseudo-anchor for the method
				$methodAnchor = $classAnchor . '::' . strtolower($method);
				$this->createIndexEntry($methodAnchor, $file . '.html#' . $anchorHash, $labelClassName . '::' . $method . '()');
			}
		}

		unset($xpath);
		unset($doc);
	}

	/**
	 * Writes the objects.inv index file.
	 *
	 * @return void
	 */
	protected function writeIndexFile() {
		$handle = fopen($this->directoryOutput . 'objects.inv', 'wb');
		//$handle2 = fopen($this->directoryOutput . 'objects.uncompressed.inv', 'wb');
		
		$headers = array();
		$headers[] = '# Sphinx inventory version 2';
		$headers[] = '# Project: ' . $this->projectName;
		$headers[] = '# Version: ' . $this->projectVersion;
		$headers[] = '# The remainder of this file is compressed using zlib.';
		
		fwrite($handle, implode("\n", $headers) . "\n");
		//fwrite($handle2, implode("\n", $headers). "\n");

		$entries = implode("\n", $this->indexEntries) . "\n";
		fwrite($handle, gzcompress($entries));
		//fwrite($handle2, $entries);
		
		fclose($handle);
		//fclose($handle2);
	}

	/**
	 * Returns a single value from an XPATH query.
	 *
	 * @param DOMXPath $xpath
	 * @param string $query
	 * @return string|NULL
	 */
	protected function xpathValue(DOMXPath $xpath, $query) {
		$values = $this->xpathValues($xpath, $query);
		return count($values) > 0 ? $values[0] : NULL;
	}

	/**
	 * Returns values from an XPath query.
	 *
	 * @param DOMXPath $xpath
	 * @param string $query
	 * @return string[]
	 */
	protected function xpathValues(DOMXPath $xpath, $query) {
		$values = array();
		$entries = $xpath->query($query);
		foreach ($entries as $entry) {
			$values[] = $entry->nodeValue;
		}
		return $values;
	}

	/**
	 * Creates an index entry.
	 *
	 * @param string $anchor
	 * @param string $target
	 * @param $label
	 * @return string
	 */
	protected function createIndexEntry($anchor, $target, $label) {
		//$this->debug('New index entry "' . $anchor . '": ' . $label);
		$indexEntry = $anchor . ' std:label -1 ' . $target . ' ' . $label;
		$this->indexEntries[] = $indexEntry;
	}

	/**
	 * Returns the source directory containing XML files from Doxygen.
	 *
	 * @return string
	 */
	public function getDirectoryXml() {
		return $this->directoryXml;
	}

	/**
	 * Sets the source directory containing XML files from Doxygen.
	 *
	 * @param string $directoryXml
	 * @return $this
	 */
	public function setDirectoryXml($directoryXml) {
		$this->directoryXml = rtrim($directoryXml, '/') . '/';
		return $this;
	}

	/**
	 * Returns the output directory (normally containing HTML files).
	 *
	 * @return string
	 */
	public function getDirectoryOutput() {
		return $this->directoryOutput;
	}

	/**
	 * Sets the output directory (normally containing HTML files).
	 *
	 * @param string $directoryOutput
	 * @return $this
	 */
	public function setDirectoryOutput($directoryOutput) {
		$this->directoryOutput = rtrim($directoryOutput, '/') . '/';
		return $this;
	}

	/**
	 * Returns the project name.
	 *
	 * @return string
	 */
	public function getProjectName() {
		return $this->projectName;
	}

	/**
	 * Sets the project name.
	 *
	 * @param string $projectName
	 * @return $this
	 */
	public function setProjectName($projectName) {
		$this->projectName = $projectName;
		return $this;
	}

	/**
	 * Returns the project version.
	 *
	 * @return string
	 */
	public function getProjectVersion() {
		return $this->projectVersion;
	}

	/**
	 * Sets the project version.
	 *
	 * @param string $projectVersion
	 * @return $this
	 */
	public function setProjectVersion($projectVersion) {
		$this->projectVersion = $projectVersion;
		return $this;
	}

}

?>
