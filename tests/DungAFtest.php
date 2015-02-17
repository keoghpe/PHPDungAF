<?php

include("DungAF.php");

class DungAFTest extends PHPUnit_Framework_TestCase
{
  public function setup()
  {
    $this->af = new DungAF([
      ["a","b"],
      ["b","a"],
      ["a","c"],
      ["b","c"],
      ["c","d"],
      ["d","e"],
      ["e","f"]
      ]);
  }

  public function testGetAdmissibleArgs()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testGetAdmissibleSets()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testGetCompleteExts()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testGetGroundedExt()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testGetPreferredExts()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testGetPreferedScepticalExt()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testGetSemiStableExts()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testGetStableArgs()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testGetStableExts()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testEagerExt()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

  public function testIdealExt()
  {
      // Assert
      $this->assertEquals(-1, $this->af->getAmount());
  }

}
