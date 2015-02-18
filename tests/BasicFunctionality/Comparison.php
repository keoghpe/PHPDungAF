<?php



class AdditionTest extends PHPUnit_Framework_TestCase
{
  private $af;
  public function setup(){
    $this->af = new DungAF([["a","b"], ["c","d"], ["e","f"]]);
    $this->sameAf = new DungAF([["a","b"], ["c","d"], ["e","f"]]);
  }

  public function testAddArgsAlreadyInAF()
  {
    $this->af->addArguments("a","b");
    $this->assertTrue($this->af->equals($this->sameAf));
  }

  public function testAddArgsNotAlreadyInAF()
  {
    $this->af->addArguments("g","h");
    $this->assertTrue($this->af->equals(new DungAF(["g","h"],[["a","b"], ["c","d"], ["e","f"]])));
  }

  public function testAddAttsAlreadyInAF()
  {
    $this->af->addAttacks(["a","b"],["c","d"]);
    $this->assertTrue($this->af->equals($this->sameAf));
  }

  public function testAddAttsNotAlreadyInAF()
  {
    $this->af->addAttacks(["g","h"], ["i","j"]);
    $this->assertTrue($this->af->equals(new DungAF([["a","b"], ["c","d"], ["e","f"], ["g","h"], ["i","j"]])));
  }

  /**
   * @expectedException Exception
   */
  public function testAddMalformedAtts()
  {
    $this->af->addAttacks(["g","h"],["i"]);
  }

  public function testCheckAttsReturned()
  {
    $this->assertTrue($this->af->addArguments("g"));
    $this->assertFalse($this->af->addArguments("g"));
    $this->assertTrue($this->af->addAttacks(["b","b"]));
    $this->assertFalse($this->af->addAttacks(["b","b"]));
    $this->assertTrue($this->af->ensureSubsumes(new DungAF([["c","c"]])));
    // $this->assertFalse($this->af->ensureSubsumes(new DungAF([["c","c"]])));
  }

}
