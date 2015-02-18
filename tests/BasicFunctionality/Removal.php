<?php



class RemovalTest extends PHPUnit_Framework_TestCase
{
  private $af;
  public function setup(){
    $this->af = new DungAF([["a","b"], ["c","d"], ["e","f"]]);
    $this->sameAf = new DungAF([["a","b"], ["c","d"], ["e","f"]]);
  }

  public function testRemoveArgsInAF()
  {
    $this->af->removeArguments("a","c");
    $this->assertTrue($this->af->equals(new DungAF(["b","d"],[["e","f"]])));
  }

  public function testRemoveArgsNotInAF()
  {
    $this->af->removeArguments("g","h");
    $this->assertTrue($this->af->equals($this->sameAf));
  }

  public function testRemoveAttsInAF()
  {
    $this->af->removeAttacks(["a","b"],["c","d"]);
    $this->assertTrue($this->af->equals(new DungAF(["a","b","c","d"],[["e","f"]])));
  }

  public function testRemoveAttsNotInAF()
  {
    $this->af->removeAttacks(["g","h"], ["i","j"]);
    $this->assertTrue($this->af->equals($this->sameAf));
  }

  /**
   * @expectedException Exception
   */
  public function testRemoveMalformedAtts()
  {
    $this->af->removeAttacks(["g","h"],["i"]);
  }

  public function testCheckAttsReturned()
  {
    $this->assertTrue($this->af->removeArguments("a"));
    $this->assertFalse($this->af->removeArguments("a"));
    $this->assertTrue($this->af->removeAttacks(["c","d"]));
    $this->assertFalse($this->af->removeAttacks(["c","d"]));
    //var_dump($this->af->arguments);
    // These should be ensureDisjointWith
    $this->assertTrue($this->af->ensureDisjointWith(new DungAF(["e","f"])));
    $this->assertFalse($this->af->ensureDisjointWith(new DungAF(["e","f"])));
    // $this->assertFalse($this->af->ensureSubsumes(new DungAF(["c","c"])));
  }

}
