<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Plant2Code\Language\Java;


use Plant2Code\Language\AbstractNamespace;

/**
 * Class JavaNamespace
 *
 * @package Plant2Code\Language\Java
 */
class JavaNamespace extends AbstractNamespace
{

    protected static $delimiter = '.';

    /**
     * @return string
     */
    public function __toString()
    {
        $ns = $this->rootNs ? $this->rootNs . self::$delimiter . $this->name : $this->name;

        return "package {$ns};";
    }
}