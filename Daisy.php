<?php
  class DaisyRoute {
    private $_method;
    private $_callback;
    private $_parts;
    private $_params;
    
    public function __construct($method, $pattern, Closure $callback, Array $conditions = null){
      $this->_parts = array();
      $this->_params = array();
      $this->build($method, $pattern, $callback, $conditions);
    }
    
    public function getMethod(){
      return $this->_method;
    }
    
    public function getParts(){
      return $this->_parts;
    }
    
    public function getParams(){
      return $this->_params;
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
      //   0 => array('pattern' => '/^$/', 'wildcard' => null)
      //   1 => array('pattern' => '/^hello$/', 'wildcard' => null)
      //   2 => array('pattern' => '/^[0-9]+$/', 'wildcard' => ':id') )
      foreach(explode('/', $pattern) as $part){
        $route = array();
        $wildcard = null;
        
        // wildcard detected
        if(preg_match('/^:\w+/', $part)) {
          $wildcard = $part;
          $pattern = array_key_exists($part, $conditions) ? '/^' . $conditions[$part] . '$/' : null;
        }
        else {
          $pattern = '/^' . $part . '$/';
        }
        array_push($this->_parts, array('pattern' => $pattern, 'wildcard' => $wildcard));
      }
    }
    
    public function matches($method, $pattern) {
      $pattern_parts = explode('/', $pattern);
      
      if($this->getMethod() != $method) { return false; }
      if(count($this->getParts()) != count($pattern_parts)) { return false; }
      
      foreach($this->getParts() as $index => $part){
        if($part['pattern'] && !preg_match($part['pattern'], $pattern_parts[$index])){
          return false;
        }
        if($part['wildcard']){
          $this->_params[$part['wildcard']] = $pattern_parts[$index];
        }
      }
      return true;
    }
  }
  
  class Daisy {
    
  }
?>
