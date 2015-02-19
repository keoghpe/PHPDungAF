<?php



class ConflictFuncTest extends PHPUnit_Framework_TestCase
{
  private $af;
  public function setup(){
    $this->af = new DungAF([["a","b"], ["c","d"], ["e","f"]]);
    $this->sameAf = new DungAF([["a","b"], ["c","d"], ["e","f"]]);
  }

  public function testEmptySetIsConflictFree()
  {
    $this->assertTrue($this->af->hasAsConflictFreeSet([]));
    $this->assertTrue($this->af->containsNoConflictAmong([]));
  }

  public function testArgSetDisjointWithThisAF()
  {
    $this->assertFalse($this->af->hasAsConflictFreeSet(["g","h"]));
    $this->assertTrue($this->af->containsNoConflictAmong(["g","h"]));
  }

  public function testConflictFreeSubsetOfArgs()
  {
    $this->assertTrue($this->af->hasAsConflictFreeSet(["a","c","e"]));
    $this->assertTrue($this->af->containsNoConflictAmong(["a","c","e"]));
  }

  public function testNonConflictFreeSubsetOfArgs()
  {
    $this->assertFalse($this->af->hasAsConflictFreeSet(["a","b","d"]));
    $this->assertFalse($this->af->containsNoConflictAmong(["a","b","d"]));
  }

  public function testCollectionIsInConflict_ArgCollectionAndArgsAreInAFButNotInConflict()
  {
    $this->assertFalse($this->af->collectionIsInConflictWithAnyOf(["a","c"],"e","f"));
  }

  public function testCollectionIsInConflict_ArgCollectionAndArgsAreInAFAndInConflict()
  {
    $this->assertTrue($this->af->collectionIsInConflictWithAnyOf(["a","b"],"b"));
  }

  public function testUnionConflict_ArgCollectionAndArgsAreInAFButNotInConflict()
  {
    $tempStrSet0 = ["a","c"];
    $tempStrSet1 = ["e"];

    $this->assertTrue($this->af->hasUnionOfAsConflictFreeSet([$tempStrSet0, $tempStrSet1]));
    $this->assertTrue($this->af->containsNoConflictAmongUnionOf([$tempStrSet0, $tempStrSet1]));
  }

  public function testUnionConflict_ArgCollectionAndArgsAreInAFAndInConflict()
  {
    $tempStrSet0 = ["a","c"];
    $tempStrSet1 = ["b"];

    $this->assertFalse($this->af->hasUnionOfAsConflictFreeSet([$tempStrSet0, $tempStrSet1]));
    $this->assertFalse($this->af->containsNoConflictAmongUnionOf([$tempStrSet0, $tempStrSet1]));
  }

  public function testUnionConflict_ArgCollectionAndArgsAreNotAllInAFAndNotInConflict()
  {
    $tempStrSet0 = ["a","g"];
    $tempStrSet1 = ["e"];

    $this->assertFalse($this->af->hasUnionOfAsConflictFreeSet([$tempStrSet0, $tempStrSet1]));
    $this->assertTrue($this->af->containsNoConflictAmongUnionOf([$tempStrSet0, $tempStrSet1]));
  }

}
