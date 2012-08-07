<?php

namespace Woody\Util\WinApi;

class WinApi {
  
  public static function call($callTarget, array $arguments) {
    $parts        = explode('::', $callTarget);
    $libraryName  = $parts[0];
    $functionName = $parts[1];
    
    $library  = wb_load_library($libraryName);
    $function = wb_get_function_address($functionName, $library);

    // automatically pack struct-classes
    $originalArguments = array();
    foreach($arguments as $index => $argument) {
      if($argument instanceof \BatteryMark\Model\SystemPowerStatus) {
        $originalArguments[$index]  = $argument;
        $arguments[$index]          = $argument->pack();
      }
    }
    
    wb_call_function($function, $arguments);
    
    // automatically unpack struct-classes
    foreach($originalArguments as $index => $argument) {
      if($argument instanceof \BatteryMark\Model\SystemPowerStatus) {
        $argument->unpack($arguments[$index]);
      }
    }
  }
}