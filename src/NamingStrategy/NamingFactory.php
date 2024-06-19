<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\NamingStrategy;

class NamingFactory
{
    static public function getStrategy(string $strategyName)
    {
        $strategyName = trim($strategyName);
        if (empty($strategyName)) {
            throw new NamingException('Naming Strategy must be not empty.');
        }

        $class = __NAMESPACE__ . '\\' . $strategyName;
        if (!class_exists($class)) {
            throw new NamingException('Strategy "' . $strategyName . '" not exist.');
        }

        return new $class;
    }
}
