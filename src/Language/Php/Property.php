<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Plant2Code\Language\Php;


use Plant2Code\Language\AbstractClassProperty;

/**
 * Class PhpClassField
 *
 * @package Plant2Php\PhpClass
 */
class Property extends AbstractClassProperty
{
    protected $defaultVisibility = 'protected';

    /**
     * Property constructor.
     *
     * @param string|null $name
     * @param string|null $type
     * @param string|null $visibility
     */
    public function __construct(string $name = null, string $type = null, string $visibility = null)
    {
        parent::__construct($name, $type, $visibility);
    }


    /**
     * @return string
     */
    public function __toString()
    {
        $field = '';

        if ($this->visibility and $this->name) {
            $label = '';
            if ($this->type) {
                // 解析扩展数据
                $extended = $this->splitExtent();
                $label .= $extended['label'];
                // 将标签名补充到类型注释中
                $this->type .= (strlen($label) ? ' ' : '') . $label;
                // TODO 实现其它扩展，比如Java或者PHP8新增的"注解"特性
            }

            $field = '/**' . PHP_EOL;
            $field .= ' * @var ' . $this->type . PHP_EOL;
            $field .= ' */' . PHP_EOL;
            $field .= $this->visibility . ' $' . $this->name . ';';
        }

        return $field;
    }
}