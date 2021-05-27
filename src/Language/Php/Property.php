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

            if ($this->type) {
                // 解析扩展数据
                $tmp = explode(' ', $this->type, 2);
                $this->type = $tmp[0];

                $extended = (count($tmp) > 1) ? trim($tmp[1]) : '';
                // 将标签名补充到类型注释中
                $label = '';
                if (stripos('[', $extended) !== false) {
                    $label .= $extended;
                } else {
                    $extended = trim($extended, '[]');
                    parse_str($extended, $extended);

                    $label .= $extended['label'];
                    // TODO 实现其它扩展，比如Java或者PHP8新增的"注解"特性
                }
                $this->type .= (strlen($label) ? ' ' : '') . $label;
            }

            $field = '/**' . PHP_EOL;
            $field .= ' * @var ' . $this->type . PHP_EOL;
            $field .= ' */' . PHP_EOL;
            $field .= $this->visibility . ' $' . $this->name . ';';
        }

        return $field;
    }
}