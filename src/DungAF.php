<?php


class DungAF
{

  public $arguments;
  public $attacks;

  // arrays with elements of the form: "a string" => ["an", "array", "of", "strings"]
  private $argsToAttackers;
  private $argsToTargets;
  // "a string" => [["multiple", "arrays"], ["of", "strings"]]
  private $argsToDefenceSets;

  function __construct($arguments=[], $attacks=[]){

    $this->attacks = [];
    //if attacks are passed as the first argument
    if(is_array($arguments) && is_array($arguments[0])){
      $this->attacks = $arguments;
      $arguments = $attacks;
      $attacks = $this->attacks;
    }

    $this->arguments = $arguments;


    if($attacks){
      $this->attacks = $attacks;
      $this->add_attacks_nodes($attacks);
    }

    $this->argsToAttackers = [];
    $this->argsToTargets = [];
    $this->argsToDefenceSets = [];


    foreach($this->arguments as $arg){
      $this->argsToAttackers[$arg] = [];
      $this->argsToTargets[$arg] = [];
    }

    foreach($this->attacks as $attack){
      array_push($this->argsToAttackers[$attack[1]],$attack[0]);
      array_push($this->argsToTargets[$attack[0]],$attack[1]);
    }
  }

  public function getAttacks(){
    return $this->attacks;
  }

  public function equals($AF){
    return $this->arguments_equal($AF) && $this->attacks_equal($AF);
  }

  public function addArguments(){
    $result = true;
    foreach(func_get_args() as $arg){
      if(!in_array($arg, $this->arguments)){
        array_push($this->arguments, $arg);
      } else{
        $result = false;
      }
    }
    return $result;
  }

  public function addAttacks(){
    $result = true;
    foreach(func_get_args() as $arg){
      if(!in_array($arg, $this->attacks)){
        array_push($this->attacks, $arg);

        $this->add_attacks_nodes([$arg]);
      } else{
        $result = false;
      }
    }
    return $result;
  }

  // takes arguments of the form "a", "b", "c"
  public function removeArguments(){
    $result = true;
    //echo  "CALLLLLEEEDDD";
    //var_dump(func_get_args());
    foreach(func_get_args() as $arg){

      if(($key = array_search($arg, $this->arguments)) !== false){
        unset($this->arguments[$key]);
        $this->remove_node_attacks($arg);
      } else{
        $result = false;
      }
    }
    return $result;
  }

  public function removeAttacks(){
    $result = true;

    foreach(func_get_args() as $arg){

      if(count($arg) !== 2){
        throw new Exception("Malformed Arguments");
      }

      if(($key = array_search($arg, $this->attacks)) !== false){
        unset($this->attacks[$key]);
      } else{
        $result = false;
      }
    }

    return $result;
  }

  public function ensureSubsumes($anotherAF){
    $result = true;

    foreach($anotherAF->arguments as $argument){
      $result = $this->addArguments($argument);
    }

    foreach($anotherAF->attacks as $attack){
      $result = $this->addAttacks($attack);
    }

    if($this->addArguments($anotherAF->arguments) || $this->addAttacks($anotherAF->attacks)) {
      $this->removeSemanticInfo();
      return true;
    } else {
      return false;
    }
  }

  public function ensureDisjointWith($anotherAF)
  {
    $result = true;

    foreach($anotherAF->arguments as $argument){
      $result = $this->removeArguments($argument);
    }

    if ($result) {
      $this->removeSemanticInfo();
      return true;
    } else {
      return false;
    }
  }

  public function removeSemanticInfo(){
    return true;
  }


  // This subsumes anotherAF if another AF is a subgraph of this
  public function subsumes($anotherAF){

    if(DungAF::containsAll($this->arguments, $anotherAF->arguments) &&
      DungAF::containsAll($this->attacks, $anotherAF->attacks)) {
        return true;
      } else {
        return false;
      }
  }

  // A contains all B
  public static function containsAll($arrayA, $arrayB){

    return count(array_intersect($arrayB, $arrayA)) === count($arrayB)
    && count($arrayB) <= count($arrayA);
  }

  public function isDisjointWith($anotherAF){
    return DungAF::areDisjoint($this->arguments, $anotherAF->arguments);
  }

  public static function areDisjoint($CollectionA, $CollectionB){

    foreach($CollectionA as $element_of_a){
      if(in_array($element_of_a, $CollectionB)){
        return false;
      }
    }
    return true;
  }

  public function hasAsConflictFreeSet($arrayOfArguments) {

    foreach($arrayOfArguments as $argument){

      if(!in_array($argument, $this->arguments)
      || !DungAF::areDisjoint($this->getAttackersOf($argument),
      $arrayOfArguments)){

        return false;
      }
    }

    return true;
  }

  public function containsNoConflictAmong($arrayOfArguments) {
    foreach($arrayOfArguments as $argument){
      if(!DungAF::areDisjoint($this->getAttackersOf($argument),
      $arrayOfArguments)){

        return false;
      }
    }

    return true;
  }

  public function getAttackersOf($argument){
    $listOfAttackers = [];

    foreach($this->attacks as $attack){
      if($attack[1] == $argument){
        array_push($listOfAttackers, $attack[0]);
      }
    }

    return $listOfAttackers;
  }

  /**
  *   takes an array of args and then other args
  *   as seperate arguments.
  **/

  public function collectionIsInConflictWithAnyOf($collection=[]){

    $functionArgs = func_get_args();

    if(!is_array($functionArgs[0])){
      throw new Exception("The first argument should be an array");
    } else {
      $collection = $functionArgs[0];
    }

    foreach($functionArgs as $key => $argument){

      if($key === 0){
        continue;
      }

      if(!DungAF::areDisjoint($collection,$this->getAttackersOf($argument))){
        return true;
      }
    }
    return false;
  }

  public function hasUnionOfAsConflictFreeSet($collection=[]){
    $union = [];

    foreach($collection as $row){
      $union = array_merge($union, $row);
    }

    return DungAF::containsAll($this->arguments,$union) && $this->hasAsConflictFreeSet($union);
  }

  public function containsNoConflictAmongUnionOf($collection=[]){
    $union = [];

    foreach($collection as $row){
      $union = array_merge($union, $row);
    }

    return $this->containsNoConflictAmong($union);
  }

  private function arguments_equal($AF){

    foreach($this->arguments as $argument){
      if(!in_array($argument, $AF->arguments)){
        return false;
      }
    }

    foreach($AF->arguments as $argument){
      if(!in_array($argument, $this->arguments)){
        return false;
      }
    }
    return true;
  }

  public function getArgsAcceptedBy($arrayOfArgs){

    $targets = [];
    $acceptableArgs = [];

    foreach($arrayOfArgs as $nextArg){
      if(array_key_exists($nextArg, $this->argsToAttackers)){
        $targets = array_merge($targets, $this->getTargetsOf($nextArg));
      }
    }

    foreach($this->arguments as $nextArg){

      if(array_key_exists($nextArg, $this->argsToAttackers) && DungAF::containsAll($targets, $this->argsToAttackers[$nextArg])){
        array_push($acceptableArgs, $nextArg);
      }
    }
    return $acceptableArgs;
  }

  public function getTargetsOf($arg){
    return in_array($arg, $this->arguments) ? $this->argsToTargets[$arg] : [];
  }

  public function argsAccept(){

    $functionArgs = func_get_args();

    if(!is_array($functionArgs[0])){
      throw new Exception("The first argument should be an array");
    } else {
      $targetsOfArgSet = $functionArgs[0];
    }

    foreach($targetsOfArgSet as $key => $arg){
      $targetsOfArgSet = array_merge($targetsOfArgSet, $this->getAttackersOf($nextArg));
    }

    foreach($functionArgs as $key => $nextArg){
      if($key === 0){
        continue;
      }

      foreach($this->getAttackersOf($nextArg) as $nextAttacker){
        if(!in_array($targetsOfArgSet, $nextAttacker)){
          return false;
        }
      }
    }

    return true;
  }


  private function attacks_equal($AF){

    foreach($this->attacks as $attack){
      if(!in_array($attack, $AF->attacks)){
        return false;
      }
    }

    foreach($AF->attacks as $attack){
      if(!in_array($attack, $this->attacks)){
        return false;
      }
    }
    return true;
  }

  private function add_attacks_nodes($attacks){
    foreach($attacks as $attack){
      if($this->arguments !== null){
        if(count($attack) !== 2){
          //echo var_dump($attack);
          throw new Exception("Invalid attack supplied");
        }

        if(!in_array($attack[0], $this->arguments)){
          array_push($this->arguments, $attack[0]);
        }
        if(!in_array($attack[1], $this->arguments)){
          array_push($this->arguments, $attack[1]);
        }
      }
    }
  }

  private function remove_node_attacks($argument){

    foreach($this->attacks as $key => $attack){
      if($argument === $attack[0] || $argument === $attack[1]){
        unset($this->attacks[$key]);
      }
    }
  }
}
