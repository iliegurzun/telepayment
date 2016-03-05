<?php
namespace AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class AbstractTest
 * @package AppBundle\Tests
 */
abstract class AbstractTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Setup function - creates the container and boots the kernel
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->client = static::createClient();
        $this->container = static::$kernel->getContainer();
    }
}
