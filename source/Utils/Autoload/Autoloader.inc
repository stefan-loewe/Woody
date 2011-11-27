<?php

namespace Woody\Utils\Autoload;

/**
* exceptions thrown during autoload need to be linked statically
*/
require_once 'SourceFileNotFoundException.class.inc';

class Autoloader
{
    /**
     *
     *
     * @var string
     */
    private $baseDirectory = null;

    public function __construct($baseDirectory)
    {
        $this->baseDirectory = str_replace('\\', '/', $baseDirectory);
    }

    public function autoload($className)
    {
        // only handle classes/interfaces that are in the woody package
        if(strpos($className, 'Woody\\') === 0)
        {
            if(file_exists($filename = $this->getFileName($className)))
                require_once $filename;

            else
                throw new SourceFileNotFoundException($className);
        }
    }

    private function getFileName($className)
    {
        //var_dump('org: '.$className);
        $className = str_replace('Woody\\', '', $className);
        //var_dump('new: '.$className);
        $fileName  = $this->baseDirectory.str_replace('\\', '/', $className).'.class.inc';
        //var_dump('file: '.$fileName);

        return $fileName;
    }
}

?>
