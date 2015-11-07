<?php

/**
 * This class is used to get the status of remote source code
 */
require_once('BaseTask.php');

class Rsync extends BaseTask
{

    /**
     * @var string
     */
    protected $credentials = '';
    /**
     * @var string
     */
    protected $directory = '';

    /**
     * Main entry point.
     *
     * @return void
     */
    public function main()
    {

        // Initialize task
        $this->initialize();

        // Makes sure it is possible to connecto to the server
        if (!file_exists($this->localDirectory) &&
            !($this->properties['dryRun'] === 'true' || $this->properties['dryRun'] === TRUE)
        ) {
            throw new Exception("Exception thrown #1300533385:\n\n local directory does not exist : \"" . $this->localDirectory . "\"\n\n", 1300533385);
        }

        // commands that will retrieve the status of the remote working copy
        $command = "rsync -a " . $this->credentials . ':' . $this->remoteDirectory . ' ' . $this->localDirectory;

        $results = $this->execute($command);
        if (!empty($results)) {
            $this->log($results);
        }
    }

    // -------------------------------
    // Set properties from XML
    // -------------------------------

    /**
     * Set the credentials information
     *
     * @param string $value
     * @return void
     */
    public function setCredentials($value)
    {
        $this->credentials = $value;
    }

    /**
     * Set the local directory
     *
     * @param string $value
     * @return void
     */
    public function setRemoteDirectory($value)
    {
        $this->remoteDirectory = $value;
    }

    /**
     * Set the remote path on the server
     *
     * @param string $value
     * @return void
     */
    public function setLocalDirectory($value)
    {
        $this->localDirectory = $value;
    }

}