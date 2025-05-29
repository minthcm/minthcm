<?php

require __DIR__ . "/../../vendor/autoload.php";

if (!class_exists("PHPUnit_Framework_TestCase"))
{
    class_alias('\PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}

if (!class_exists("PHPUnit_Framework_Error"))
{
    class_alias('PHPUnit\Framework\Error\Warning', 'PHPUnit_Framework_Error');
}

/**
 * A modified version of PHPUnit's TestCase to rid ourselves of deprecation
 * warnings since we're using two different versions of PHPUnit in this branch
 * (PHPUnit 4 and 5).
 */
class BC_PHPUnit_Framework_TestCase extends \PHPUnit\Framework\TestCase {

    use \Yoast\PHPUnitPolyfills\Polyfills\AssertionRenames;

    public function bc_expectException($exception)
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException($exception);
        } elseif (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($exception);
        }
    }

    // A BC hack to get handle the deprecation of this method in PHPUnit
    public function bc_getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $cloneArguments = false, $callOriginalMethods = false, $proxyTarget = null)
    {
        if (method_exists($this, "getMockBuilder")) {
            return $this
                ->getMockBuilder($originalClassName)
                ->setMethods($methods)
                ->getMock()
            ;
        }

        return parent::getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload, $cloneArguments, $callOriginalMethods, $proxyTarget);
    }
}
