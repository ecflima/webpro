<?php

namespace Ecfl\Webpro;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::TARGET_CLASS)]
class Path {
	private $path;

    public function __construct($path) {
        $this->path = $path;
    }

    public static function getFunctions() {        
        $r = [];
        foreach (get_defined_functions(true) as $f) {
            $rf = new ReflectionFunction($f);
            $attrs = $rf->getAttributes()
            foreach ($attrs as $attr) {
                if ($attr->getName() == Path::class) {
                    $r[$f] = $attr;
                }
            }
        }
        return $r;
    }
}