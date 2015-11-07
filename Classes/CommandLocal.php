<?php

/**
 * This class is used to get the status of remote source code
 */
require_once('BaseTask.php');

class CommandLocal extends BaseTask {

	/**
	 * @var string
	 */
	protected $command = '';

	/**
	 * Main entry point.
	 *
	 * @return void
	 */
	public function main() {

		// Initialize task
		$this->initialize();

		// commands that will retrieve the status of the remote working copy
		$command = $this->command;

		$results = $this->execute($command);
		if (!empty($results)) {
			$this->log($results);
		}
	}

	// -------------------------------
	// Set properties from XML
	// -------------------------------

	/**
	 * Set the remote path on the server
	 *
	 * @param string $value
	 * @return void
	 */
	public function setCommand($value) {
		$this->command = $value;
	}

}