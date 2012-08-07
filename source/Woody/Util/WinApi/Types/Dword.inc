<?php

namespace Woody\Util\WinApi\Types;

class Dword {
   public function getPackString(){
     return 'L';
   }
   
   public function getLength(){
     return 4;
   }
}