<?php

include("./src/DungAF.php");

class ConstructorsTest extends PHPUnit_Framework_TestCase
{

  public function testConstructWithNonEmptySetOfArgsAlone()
  {
    $af = new DungAF([["a","b"], ["c","d"], ["e","f"]]);

    $this->assertTrue($af->equals(new DungAF(["a","b","c","d","e","f"], [["a","b"],["c","d"], ["e","f"]])));
  }

  public function testConstructWithIncompleteSetOfArgumentsAndNonEmptyAttacks()
  {
    $af = new DungAF(["g","h"],[["a","b"], ["c","d"], ["e","f"]]);
    $this->assertTrue($af->equals(new DungAF(["a","b","c","d","e","f","g","h"], [["a","b"],["c","d"], ["e","f"]])));
  }

  /**
   * @expectedException Exception
   */
  public function testConstructWithMalformedAttacks()
  {
    $af = new DungAF([["a","b"], ["c","d","e"]]);
  }

}
