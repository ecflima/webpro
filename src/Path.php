<?php

namespace Ecfl\Webpro;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION | \Attribute::TARGET_CLASS)]
class Path {
	private $path;
    private $methods;

    public function __construct(string $path, string | array $method = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE']) {
        $this->path = $path;
        $this->methods = is_string($method) ? [$method] : $method;
    }

    public function getPath() {
        return $this->path;
    }

    public function getMethods() {
        return $this->methods;
    }

    public static function getFunctions() {        
        $r = [];
        foreach (get_defined_functions(true)["user"] as $f) {
            $rf = new \ReflectionFunction($f);
            $attrs = $rf->getAttributes();
            foreach ($attrs as $attr) {
                if ($attr->getName() == Path::class) {
                    $r[$f] = $attr;
                }
            }
        }
        return $r;
    }
}