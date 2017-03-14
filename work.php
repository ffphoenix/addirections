<?php 

$input = file('sample.in');

$isTestCase = false;
$askPeopleNum = 0;
$testCases = [];
$testCaseNum = 0;
foreach ($input as $row)
{
  
  if (!$isTestCase)
  {
    $askPeopleNum = (int) $row; 
    if ($askPeopleNum == 0)
      break;
    else
      $isTestCase = true;
    
    $testCaseNum++;
    $testCases[$testCaseNum] = [];
    continue;
  }
  else 
  {
    $testCases[$testCaseNum][] = $row;
    $askPeopleNum--;
    if ($askPeopleNum == 0)
    {
      $isTestCase = false;
    }
  }
}
print_r($testCases);