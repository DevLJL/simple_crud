<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public $faker;

    public function __construct()
    {
      parent::__construct();
      $this->faker = Factory::create('pt_BR');
    }

    protected function setUp(): void
    {
        parent::setUp();    
        Config::set('database.default', 'mysql');
    }
}
