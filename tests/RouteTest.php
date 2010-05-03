<?php
require_once 'PHPUnit/Framework.php';
require_once 'Daisy.php';
 
class RouteTest extends PHPUnit_Framework_TestCase
{
  protected $route;
  
  protected function setUp(){
    $this->route = new DaisyRoute();
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
      array('pattern' => '/^$/', 'wildcard' => null),
      array('pattern' => '/^hello$/', 'wildcard' => null)
    ));
  }
  
  public function testBuildShouldSetPartsWithConditionsGiven()
  {
    $this->route->build('GET', '/say/:what/to/:id', function(){}, array(':id' => '[0-9]+'));
    $this->assertEquals($this->route->getParts(), array(
      array('pattern' => '/^$/', 'wildcard' => null),
      array('pattern' => '/^say$/', 'wildcard' => null),
      array('pattern' => null, 'wildcard' => ':what'),
      array('pattern' => '/^to$/', 'wildcard' => null),
      array('pattern' => '/^[0-9]+$/', 'wildcard' => ':id')
    ));
  }
  
  public function testBuildShouldSetCallback()
  {
    $this->route->build('GET', '/hello', function(){ 
      return "I'm callback"; 
    });
    $this->assertEquals($this->route->fireCallback(), "I'm callback");
  }
  
  public function testMatchPatternShouldBeTrue()
  {
    $this->route->build('GET', '/hello', function(){});
    $this->assertEquals($this->route->match('GET', '/hello'), true);
  }
  
  public function testMatchPatternShouldBeFalseWhenPatternDoesNotMatch()
  {
    $this->route->build('GET', '/hello', function(){});
    $this->assertEquals($this->route->match('GET', '/wrong'), false);
  }
  
  public function testMatchPatternShouldBeFalseWhenMethodDoesNotMatch()
  {
    $this->route->build('GET', '/hello', function(){});
    $this->assertEquals($this->route->match('POST', '/hello'), false);
  }
  
  public function testMatchPatternShouldBeTrueWhenConditionsMet()
  {
    $this->route->build('GET', '/hello/:id', function(){}, array(':id' => '[0-9]+'));
    $this->assertEquals($this->route->match('GET', '/hello/21'), true);
  }
  
  public function testMatchPatternShouldBeFalseWhenConditionsNotMet()
  {
    $this->route->build('GET', '/hello/:id', function(){}, array(':id' => '[0-9]+'));
    $this->assertEquals($this->route->match('GET', '/hello/21a43'), false);
  }
}
?>
