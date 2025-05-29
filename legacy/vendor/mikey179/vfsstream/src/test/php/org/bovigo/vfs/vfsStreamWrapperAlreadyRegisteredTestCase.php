<?php
/**
 * This file is part of vfsStream.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  org\bovigo\vfs
 */
namespace org\bovigo\vfs;
/**
 * Helper class for the test.
 */
class TestvfsStreamWrapper extends vfsStreamWrapper
{
    /**
     * unregisters vfsStreamWrapper
     */
    public static function unregister()
    {
        if (in_array(vfsStream::SCHEME, stream_get_wrappers()) === true) {
            stream_wrapper_unregister(vfsStream::SCHEME);
        }

        self::$registered = false;
    }
}
/**
 * Test for org\bovigo\vfs\vfsStreamWrapper.
 */
class vfsStreamWrapperAlreadyRegisteredTestCase extends \BC_PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp(): void
    {
        TestvfsStreamWrapper::unregister();
        $mock = $this->bc_getMock('org\\bovigo\\vfs\\vfsStreamWrapper');
        stream_wrapper_register(vfsStream::SCHEME, get_class($mock));
    }

    /**
     * clean up test environment
     */
    public function tearDown(): void
    {
        TestvfsStreamWrapper::unregister();
    }

    /**
     * registering the stream wrapper when another stream wrapper is already
     * registered for the vfs scheme should throw an exception
     *
     * @test
     */
    public function registerOverAnotherStreamWrapper()
    {
        $this->expectException(vfsStreamException::class);
        vfsStreamWrapper::register();
    }
}
