<?php

namespace Woody\Util\WinApi\Types;

class Byte {
   public function getPackString(){
     return 'C';
   }
   
   public function getLength(){
     return 1;
   }
};

