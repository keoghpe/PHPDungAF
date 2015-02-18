<?php


class DungAF
{

  public $arguments;
  public $attacks;

  function __construct($arguments=[], $attacks=[]){

    //if attacks are passed as the first argument
    if(is_array($arguments) && is_array($arguments[0])){
      $this->attacks = $arguments;
      $arguments = $attacks;
      $attacks = $this->attacks;
    }

    if($arguments){
      $this->arguments = $arguments;
    } else {
      $this->arguments = [];
    }

    if($attacks){
      $this->attacks = $attacks;
      $this->add_attacks_nodes($attacks);
    }
    //
    // echo "Attacks:";
    // var_dump($this->attacks);
    // echo "Arguments:";
    //var_dump($this->arguments);
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

        // if(!is_array($arg)){
        //   $arg = [$arg];
        // }
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
