<?php

/**
 * This class is used to get the status of remote source code
 */
require_once('BaseTask.php');

class CommandRemote extends BaseTask
{

    /**
     * @var string
     */
    protected $credentials = '';
    /**
     * @var string
     */
    protected $command = '';

    /**
     * Main entry point.
     *
     * @return void
     */
    public function main()
    {

        // Initialize task
        $this->initialize();

        // Makes sure it is possible to connect to the server
        if ($this->credentials == '') {
            throw new Exception("Exception thrown #1300533385: credentials is empty can not connect to the server\n", 1300533385);
        }

        // commands that will retrieve the status of the remote working copy
        $command = 'ssh ' . $this->credentials . " '" . $this->command . "'";


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
    public function setCommand($value)
    {
        $this->command = $value;
    }

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

}