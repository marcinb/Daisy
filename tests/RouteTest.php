<?php
require_once 'PHPUnit/Framework.php';
require_once 'Daisy.php';
 
class RouteTest extends PHPUnit_Framework_TestCase
{
  protected $route;
  
  protected function setUp(){
    $this->route = new Route();
  }
  
  public function testBuildShouldSetMethod()
  {
    $this->route->build('GET', '/hello', function(){});
    $this->assertEquals($this->route->getMethod(), 'GET');
  }
  
  public function testBuildShouldSetPartsWithoutConditionsGiven()
  {
    $this->route->build('GET', '/hello', function(){});
    $this->assertEquals($this->route->getParts(), array(
      array('pattern' => 'hello', 'condition' => null)
    ));
  }
  
  public function testBuildShouldSetPartsWithConditionsGiven()
  {
    $this->route->build('GET', '/say/:what/to/:id', function(){}, array(':id' => '[0-9]+'));
    $this->assertEquals($this->route->getParts(), array(
      array('pattern' => 'say', 'condition' => null),
      array('pattern' => ':what', 'condition' => null),
      array('pattern' => 'to', 'condition' => null),
      array('pattern' => ':id', 'condition' => '[0-9]+')
    ));
  }
  
  public function testBuildShouldSetCallback()
  {
    $this->route->build('GET', '/hello', function(){ 
      return "I'm callback"; 
    });
    $this->assertEquals($this->route->fireCallback(), "I'm callback");
  }
}
?>