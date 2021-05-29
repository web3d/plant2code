<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Plant2Code\Language;


/**
 * Class AbstractClassProperty
 *
 * @package Plant2Code\Language
 *
 * @property string $name
 * @property string $type
 * @property string $visibility
 *
 */
abstract class AbstractClassProperty extends AbstractLanguage
{

    /**
     * @var array
     */
    protected static $fillable = ['type', 'name', 'visibility'];

    /**
     * AbstractClassProperty constructor.
     *
     * @param string|null $name
     * @param string|null $type
     * @param string|null $visibility
     */
    public function __construct(string $name = null, string $type = null, string $visibility = null)
    {
        $this->name = $name;
        $this->type = $type;
        if (!$visibility) $visibility = $this->defaultVisibility;
        $this->visibility = $visibility;
    }

    /**
     * 从type的部分解析出扩展数据
     * @return array
     */
    protected function splitExtent()
    {
        // 解析扩展数据
        $tmp = explode(' ', $this->type, 2);
        $this->type = trim($tmp[0]);

        $extended = (count($tmp) > 1) ? trim($tmp[1]) : '';
        if (stripos('[', $extended) !== false) {
            $extended = [
                'label' => $extended,
            ];
        } else {
            $extended = str_replace(' ', '', trim($extended, '[]'));
            parse_str($extended, $extended);
        }
        
        return $extended;
    }
}