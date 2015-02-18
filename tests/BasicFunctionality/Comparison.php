<?php



class ComparisonTest extends PHPUnit_Framework_TestCase
{
  private $af;

  public function setup(){
    $this->af = new DungAF([["a","b"], ["c","d"], ["e","f"]]);
    $this->sameAf = new DungAF([["a","b"], ["c","d"], ["e","f"]]);
  }

  public function testEqualAFsAreEqual()
  {
    $this->assertTrue($this->af->equals($this->sameAf));
  }

  public function testUnequalAFsAreUnequalArgs()
  {
    $this->assertFalse($this->af->equals(new DungAF(["g"],[["a","b"], ["c","d"], ["e","f"]])));
  }

  public function testUnequalAFsAreUnequalAtts()
  {
    $this->assertFalse($this->af->equals(new DungAF([["a","b"], ["c","d"], ["f","e"]])));
  }

  public function testSubsumingAFsSubsumeArgs()
  {
    $anotherAF = new DungAF(["g"], [["a","b"], ["c","d"], ["e","f"]]);
    $this->assertTrue($anotherAF->subsumes($this->af));
    $this->assertFalse($this->af->subsumes($anotherAF));
  }

  public function testSubsumingAFsSubsumeAtts()
  {
    $anotherAF = new DungAF(["a","b"], [["c","d"], ["e","f"]]);

    $this->assertTrue($this->af->subsumes($anotherAF));
    $this->assertFalse($anotherAF->subsumes($this->af));
  }

  public function testIsDisjointWith()
  {
    $anotherAF = new DungAF(["g","h"], [["i","j"], ["k","l"]]);
    $this->assertTrue($this->af->isDisjointWith($anotherAF));
  }

  // //-------------------------------------
  //
  // testName = "disjoint AFs are disjoint";
  // af = new DungAF(Arrays.asList(new String[]{"a","b"}, new String[]{"c","d"}, new String[]{"e","f"}));
  // anotherAf = new DungAF(Arrays.asList("g","h"), Arrays.asList(new String[]{"i","j"}, new String[]{"k","l"}));
  // expected = af.isDisjointWith(anotherAf);
  // assert expected : ("Failed test \"" + testName + "\".");

}
