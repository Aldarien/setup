<?php

namespace spec\App\Service;

use App\Service\Setup;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SetupSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Setup::class);
    }
    function it_finds_setup_directory()
    {
    	$directory = root() . '\\setup';
    	if (file_exists($directory)) {
    		$this->findDirectory()->shouldReturn($directory);
    	} else {
    		$this->shouldThrow('\DomainException')->during('findDirectory');
    	}
    }
    function it_finds_models()
    {
    	$this->findModels();
    }
    function it_gets_properties()
    {
    	$this->getProperties(new \Test());
    }
    function it_makes_migrations()
    {
    	$this->createMigrations();
    }
    function it_runs_migrations()
    {
    	
    }
    function it_finds_seeds()
    {
    	
    }
    function it_runs_seeds()
    {
    	
    }
}
