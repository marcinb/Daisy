<?php
require_once 'PHPUnit/Framework.php';
require_once 'Daisy.php';
 
class RouteTest extends PHPUnit_Framework_TestCase
{
  protected $route;
  
  public function testBuildShouldSetMethod()
  {
    $route = new DaisyRoute('GET', '/hello', function(){});
    $this->assertEquals($route->getMethod(), 'GET');
  }
  
  public function testBuildShouldSetPartsWithoutConditionsGiven()
  {
    $route = new DaisyRoute('GET', '/hello', function(){});
    $this->assertEquals($route->getParts(), array(
      array('pattern' => '/^$/', 'wildcard' => null),
      array('pattern' => '/^hello$/', 'wildcard' => null)
    ));
  }
  
  public function testBuildShouldSetPartsWithConditionsGiven()
  {
    $route = new DaisyRoute('GET', '/say/:what/to/:id', function(){}, array(':id' => '[0-9]+'));
    $this->assertEquals($route->getParts(), array(
      array('pattern' => '/^$/', 'wildcard' => null),
      array('pattern' => '/^say$/', 'wildcard' => null),
      array('pattern' => null, 'wildcard' => ':what'),
      array('pattern' => '/^to$/', 'wildcard' => null),
      array('pattern' => '/^[0-9]+$/', 'wildcard' => ':id')
    ));
  }
  
  public function testBuildShouldSetCallback()
  {
    $route = new DaisyRoute('GET', '/hello', function(){ 
      return "I'm callback"; 
    });
    $this->assertEquals($route->fireCallback(), "I'm callback");
  }
  
  public function testMatchesShouldBeTrue()
  {
    $route = new DaisyRoute('GET', '/hello', function(){});
    $this->assertEquals($route->matches('GET', '/hello'), true);
  }
  
  public function testMatchesShouldBeFalseWhenMethodDoesNotMatch()
  {
    $route = new DaisyRoute('GET', '/hello', function(){});
    $this->assertEquals($route->matches('POST', '/hello'), false);
  }
  
  public function testMatchesShouldBeFalseWhenPartsCountDifferent()
  {
    $route = new DaisyRoute('GET', '/first/second', function(){});
    $this->assertEquals($route->matches('POST', '/first'), false);
    $route->build('GET', '/first', function(){});
    $this->assertEquals($route->matches('POST', '/first/second'), false);
  }
  
  public function testMatchesShouldBeFalseWhenPatternDoesNotMatch()
  {
    $route = new DaisyRoute('GET', '/hello', function(){});
    $this->assertEquals($route->matches('GET', '/wrong'), false);
  }
  
  public function testMatchesShouldBeTrueWhenConditionsMet()
  {
    $route = new DaisyRoute('GET', '/hello/:id', function(){}, array(':id' => '[0-9]+'));
    $this->assertEquals($route->matches('GET', '/hello/21'), true);
  }
  
  public function testMatchesShouldBeFalseWhenConditionsNotMet()
  {
    $route = new DaisyRoute('GET', '/hello/:id', function(){}, array(':id' => '[0-9]+'));
    $this->assertEquals($route->matches('GET', '/hello/21a43'), false);
  }
  
  public function testShouldGetParams()
  {
    $route = new DaisyRoute('GET', '/hello/:id/:name', function(){}, array(':id' => '[0-9]+'));
    $route->matches('GET', '/hello/21/Tom');
    $params = $route->getParams();
    $this->assertEquals($params[':id'], '21');
    $this->assertEquals($params[':name'], 'Tom');
  }
}
?>
