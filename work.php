<?php
$input = file('sample.in');

$isTestCase   = false;
$askPeopleNum = 0;

foreach ($input as $row)
{
  if (!$isTestCase)
  {
    $askPeopleNum = (int) $row;
    if ($askPeopleNum == 0)
      break;
    else
      $isTestCase = true;

    $testCaseArray = [];
    continue;
  }
  else
  {
    $testCaseArray[] = $row;
    $askPeopleNum--;
    if ($askPeopleNum == 0)
    {
      $testCase = new TestCase($testCaseArray);
      $testCase->printResult();
      $isTestCase = false;
    }
  }
}


class TestCase
{
  protected $avgX = 0;
  protected $avgY = 0;
  protected $directions;

  public function __construct($directionsArray = array())
  {
    $this->directions = [];
    
    foreach ($directionsArray as $directionRule)
    {
      $direction = new Direction($directionRule);
      $this->avgX += $direction->posX;
      $this->avgY += $direction->posY;

      $this->directions[] = $direction;
    }
    
    $count = count($this->directions);
    $this->avgX = $this->avgX / $count;
    $this->avgY = $this->avgY / $count;
  }
  
  public function printResult()
  {
    echo "\n===============\n";
    echo "TEST CASE RESULT : " . $this->avgX . ' - ' . $this->avgY . ' - ' . $this->findWorstDir();
    echo "\n";
  }

  protected function findWorstDir()
  {
    $worstDirection = 0;
    foreach ($this->directions as $direction)
    {
      $x = abs($direction->posX - $this->avgX);
      $y = abs($direction->posY - $this->avgY);
      $dir = sqrt($x * $x + $y * $y);
      
      if ($dir > $worstDirection)
        $worstDirection = $dir;
    }
    return $worstDirection;
  }  
}

class Direction
{
  public $posX;
  public $posY;
  protected $degrees = 0;

  public function __construct($directionSting)
  {
    $directionSting = str_replace("\n", '', $directionSting);
    $routes = explode(' ', $directionSting);
    for ($i = 0; $i < count($routes); $i = $i + 2)
    {
      if ($i == 0)
      {
        $this->posX = $routes[$i];
        $this->posY = $routes[$i+1];
      }
      else
      {
        // need check this !
        $this->{$routes[$i]}($routes[$i+1]);
      }
    }
  }
  
  protected function start($a)
  {
    $this->degrees = (float)$a;
  }

  protected function turn($a)
  {
    $this->degrees += (float)$a;
    
    if ($this->degrees >= 360)
      $this->degrees -= 360;
    
    if ($this->degrees < 0)
      $this->degrees += 360;
  }

  protected function walk($x)
  {
    $this->posX += $x * cos(deg2rad($this->degrees));
    $this->posY += $x * sin(deg2rad($this->degrees));
  }
  
}