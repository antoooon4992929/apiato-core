<?php

namespace Apiato\Core\Generator\Traits;

trait FormatterTrait
{
    public function prependOperationToName($operation, $class): string
    {
        $className = ($operation == 'list') ? ngettext($class) : $class;

        return $operation.$this->capitalize($className);
    }

    public function capitalize($word): string
    {
        return ucfirst($word);
    }

    protected function trimString($string): string
    {
        return trim($string);
    }
}
