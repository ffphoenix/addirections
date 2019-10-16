<?php

# Resolving "All Different Directions" problem
# https://open.kattis.com/problems/alldifferentdirections
# hahah test 

class TestCase
{
  protected $avgX = 0;
  protected $avgY = 0;
  protected $directions;
  
  /**
  * 
  * Init Case and computing the average destination 
  * @param array $directionsArray array of sting rules
  *
  */
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
  
  /**
  * Calculate maximum straight-line distance between each direction’s destination and the averaged destination.
  * @return float
  */
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
  
  public function printResult()
  {
    echo "TEST CASE RESULT : " . $this->avgX . ' - ' . $this->avgY . ' - ' . $this->findWorstDir();
    echo "\n";
  }
}

/**
 *
 * This class calculate final position
 */
class Direction
{
  public $posX;
  public $posY;
  protected $degrees = 0;
  
  const COMMAND_LIST = ['start', 'turn', 'walk'];
  
  /**
  * @param string $directionSting raw rules string
  * @throws Exception if command not find in COMMAND_LIST
  */
  public function __construct($directionSting)
  {
    // delete incorrect chars from string
    $directionSting = str_replace(array("\n"), '', $directionSting);
    
    $routes = explode(' ', $directionSting);
    for ($i = 0; $i < count($routes); $i = $i + 2)
    {
      if ($i == 0)
        $this->initStartPos($routes[$i], $routes[$i + 1]);
      else
      {
        if (in_array($routes[$i], self::COMMAND_LIST))
          $this->{$routes[$i]}((float)$routes[$i + 1]);
        else
          throw new Exception('Wrong command format, check input data!');
      }
    }
  }

  /**
  * Setup start posiotion
  * @param float $x position
  * @param float $y position
  */
  protected function initStartPos($x, $y)
  {
    $this->posX = (float) $x;
    $this->posY = (float) $y;
  }
  
  /**
  * 
  * Setup start degrees
  * @param float $a degrees
  *
  */
  protected function start($a)
  {
    $this->degrees = (float) $a;
  }

  /**
  * Change current angle of motion
  * @param float $a degrees
  */
  protected function turn($a)
  {
    $this->degrees += (float) $a;

    if ($this->degrees >= 360)
      $this->degrees -= 360;

    if ($this->degrees < 0)
      $this->degrees += 360;
  }
  
  /**
  * Calculate next walk position
  * @param float $x
  */
  protected function walk($x)
  {
    $this->posX += $x * cos(deg2rad($this->degrees));
    $this->posY += $x * sin(deg2rad($this->degrees));
  }
}

#
# Read file and init TastCases
#
try
{
  if (file_exists('sample.in'))
    throw new Exception ('Not find sample.in file');
  
  $input = file('sample.in');

  $isTestCase = false;
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
}
catch (Exception $e)
{
  echo $e->getMessage();
}