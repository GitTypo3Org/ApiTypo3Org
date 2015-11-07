<?php

/**
 * This class is used to download sources
 */
require_once('BaseTask.php');

class Download extends BaseTask
{

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
    public function main()
    {

        // Initialize task
        $this->initialize();
        $this->log('downloading latest source...');

        if (!is_dir($this->target)) {
            $commands[] = 'git clone --quiet ' . $this->repository . ' ' . $this->target;
        } else {
            $commands[] = 'cd ' . $this->target . '; git checkout master --quiet';
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

    // -------------------------------
    // Set properties from XML
    // -------------------------------

    /**
     * Setter for repository
     *
     * @param string $repository
     * @return void
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Setter for target
     *
     * @param string $target
     * @return void
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Setter for tagName
     *
     * @param string $tagName
     * @return void
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * Setter for submoduleDirectory
     *
     * @param string $submoduleDirectory
     * @return void
     */
    public function setSubmoduleDirectory($submoduleDirectory)
    {
        $this->submoduleDirectory = $submoduleDirectory;
    }
}
