<?php

namespace Woody\Utils\Autoload;

/**
 * This exception is thrown when the autoloader is unable to locate a source file that that holds the definition of a class or interface
 *
 */
class SourceFileNotFoundException extends \RuntimeException
{
    /**
     * THis method acts as the constructor of the class.
     *
     * @param string $name the name of the class/interface
     */
    public function __construct($name)
    {
        $this->message = 'No source file holding the class/interface definition of the class/interface "'.$name.'" were found!';
    }
}

?>