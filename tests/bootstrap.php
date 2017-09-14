<?php
    // backward compatibility
    if (!class_exists('\PHPUnit\Framework\TestCase', true)) {
        class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
    } elseif (!class_exists('\PHPUnit_Framework_TestCase', true)) {
        class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
    }


    require_once __DIR__ . '/../src/AbstractDom.php';
    require_once __DIR__ . '/../src/AttributesTrait.php';
    require_once __DIR__ . '/../src/Element.php';
    require_once __DIR__ . '/../src/Component.php';
    require_once __DIR__ . '/../src/Utils.php';
    require_once __DIR__ . '/../src/ClassList.php';
    require_once __DIR__ . '/../src/Style.php';
    require_once __DIR__ . '/../src/DomWalker.php';
    require_once __DIR__ . '/../src/Resolver.php';
    require_once __DIR__ . '/../src/Scope.php';
    require_once __DIR__ . '/../src/Invoke.php';
    require_once __DIR__ . '/../src/Url.php';
