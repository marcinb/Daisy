<?php
  class Route {
    private $_method;
    private $_callback;
    private $_parts;
    private $_params;
    
    public function __construct(){
      $this->_parts = array();
    }
    
    public function getMethod(){
      return $this->_method;
    }
    
    public function getParts(){
      return $this->_parts;
    }
    
    public function fireCallback(){
      return call_user_func($this->_callback);
    }
    
    public function build($method, $pattern, Closure $callback, Array $conditions = null){
      $this->_method = $method;
      $this->_callback = $callback;
      
      if($conditions == null){
        $conditions = array();
      }
      
      // split routes into parts, include conditions for wildcards
      // eg. $pattern = '/hello/:id' with $conditions = array(':id' => '[0-9]+')
      // produces: 
      // $parts = array(
      //   0 => array('pattern' => 'hello', 'condition' => null)
      //   1 => array('pattern' => ':id', 'condition' => '[0-9]+') )
      foreach(explode('/', $pattern) as $part){
        if($part != ''){
          $condition = array_key_exists($part, $conditions) ? $conditions[$part] : null;
          array_push($this->_parts, array('pattern' => $part, 'condition' => $condition));
        }
      }
    }
    
    public function matchesAgainst($path){
      
    }
  }
?>
