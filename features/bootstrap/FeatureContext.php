<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use App\Service\Setup;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
	protected $setup;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    	$this->setup = new Setup();
    }

    /**
     * @Given the setup directory
     */
    public function theSetupDirectory()
    {
    	$directory = $this->setup->findDirectory();
        //throw new PendingException();
    }

    /**
     * @When no setup files are detected
     */
    public function noSetupFilesAreDetected()
    {
        throw new PendingException();
    }

    /**
     * @Then do nothing
     */
    public function doNothing()
    {
        throw new PendingException();
    }

    /**
     * @When one or more files are detected
     */
    public function oneOrMoreFilesAreDetected()
    {
        throw new PendingException();
    }

    /**
     * @When the data is correct
     */
    public function theDataIsCorrect()
    {
        throw new PendingException();
    }

    /**
     * @Then read them
     */
    public function readThem()
    {
        throw new PendingException();
    }

    /**
     * @When the data is not correct
     */
    public function theDataIsNotCorrect()
    {
        throw new PendingException();
    }

    /**
     * @Then throw Exception
     */
    public function throwException()
    {
        throw new PendingException();
    }
}
