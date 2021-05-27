<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Plant2Code\Language;


use Plant2Code\Language\Java\Argument;
use Plant2Code\Language\Java\Method;
use Plant2Code\Language\Java\JavaClass;
use Plant2Code\Language\Java\JavaNamespace;
use Plant2Code\Language\Java\Property;

class Java extends ComponentBuilder
{
    protected $extension = '.java';

    protected $nsSeparator = '.';

    public function createClass()
    {
        return new JavaClass();
    }

    public function createProperty(string $name = null, string $type = null, string $visibility = null)
    {
        return new Property($name, $type, $visibility);
    }

    public function createMethod(string $name, string $visibility = null, array $args = [], string $type = null)
    {
        return new Method($name, $visibility, $args, $type);
    }

    public function createMethodArgument(string $name = null, string $type = null)
    {
        return new Argument($name, $type);
    }

    /**
     * @param string      $pumlNamespace
     * @param string|null $rootNS
     *
     * @return AbstractNamespace|void
     */
    public function createNamespace(string $pumlNamespace, string $rootNS = null)
    {
        return new JavaNamespace($pumlNamespace, $rootNS);
    }


}