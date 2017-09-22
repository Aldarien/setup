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
    function it_loads_models()
    {
    	$this->getModelList()->shouldReturnType('array');
    	$this->getSpecifications($model_name)->shoudlReturnType('array');
    	$this->createMigration($specifications)->shouldReturnType('string');
    }
    function it_migrates()
    {
    	
    }
}
