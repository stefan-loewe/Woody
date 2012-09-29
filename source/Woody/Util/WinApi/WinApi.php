<?php

namespace Woody\Util\WinApi;

class WinApi {
  /**
   * This method allows making calls to function of the Windows API.
   *
   * @param string $callTarget the functin to be called, given in the format <LibraryName>::<functionName>,
   * e.g. Kernel32::GetSystemPowerStatus
   * @param array $arguments the set of arguments to pass to the called Windows API function
   */
  public static function call($callTarget, array $arguments) {
    $parts        = explode('::', $callTarget);
    $libraryName  = $parts[0];
    $functionName = $parts[1];

    $library  = wb_load_library($libraryName);
    $function = wb_get_function_address($functionName, $library);

    // automatically pack objects of type Structure
    $originalArguments = array();
    foreach($arguments as $index => $argument) {
      if($argument instanceof Structure) {
        $originalArguments[$index]  = $argument;
        $arguments[$index]          = $argument->pack();
      }
    }

    wb_call_function($function, $arguments);

    // automatically unpack objects of type Structure
    foreach($originalArguments as $index => $argument) {
      if($argument instanceof Structure) {
        $argument->unpack($arguments[$index]);
      }
    }
  }
}