<?php

namespace Tests\Unit\Shared;

use PHPUnit\Framework\TestCase;
use Faker\Factory as Faker;

class BaseUnitTest extends TestCase
{
  public $faker;
  public function setUp(): void
  {
      parent::setUp();
      $this->faker = Faker::create();
  }

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_that_true_is_true()
  {
      $this->assertTrue(true);
  }
}
