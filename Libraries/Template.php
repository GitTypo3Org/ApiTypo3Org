<?php
/***************************************************************
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
***************************************************************/

/**
 * This class is a very basic template engine for generating Doxygen Configuration
 * 
 * Source : http://www.massassi.com/php/articles/template_engines/
 *
 * $Id: Template.php 2202 2010-08-30 17:25:34Z fabien_u $
 */

class Template {
	private $vars = array(); /// Holds all the template variables

	/**
	 * Constructor
	 *
	 * @param $file string the file name you want to load
	 */
	function __construct($file = null){
		$this->file = $file;
	}

	/**
	 * Set a template variable.
	 */
	function set($name, $value){
		$this->vars[$name] = is_object($value) ? $value->fetch() : $value . "\n";
	}

	/**
	 * Open, parse, and return the template file.
	 *
	 * @param $file string the template file name
	 */
	function fetch($file = null){
		if(! $file) {
			$file = $this->file;
		}
		
		extract($this->vars); // Extract the vars to local namespace
		ob_start(); // Start output buffering
		include ($file); // Include the file
		$contents = ob_get_contents(); // Get the contents of the buffer
		ob_end_clean(); // End buffering and discard
		return $contents; // Return the contents
	}
}
?>