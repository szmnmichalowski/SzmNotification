<?php

namespace SzmNotificationTest\Controller\Plugin;

use PHPUnit\Framework\TestCase;
use SzmNotification\Controller\Plugin\Notification;
use Zend\Session\ManagerInterface as Manager;
use Zend\Session\Container;

class NotificationTest extends TestCase
{
    /**
     * @var Notification
     */
    protected $notification;


    public function setUp()
    {
        $this->notification = new Notification();
    }

    public function testPluginExists()
    {
        $this->assertTrue(is_object($this->notification));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::setSessionManager
     * @covers SzmNotification\Controller\Plugin\Notification::getSessionManager
     */
    public function testSessionManager()
    {
        $this->assertInstanceOf(Manager::class, $this->notification->getSessionManager());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getContainer
     */
    public function testSessionContainer()
    {
        $this->assertInstanceOf(Container::class, $this->notification->getContainer());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getNotificationsFromContainer
     * @covers SzmNotification\Controller\Plugin\Notification::clearNotificationsFromContainer
     */
    public function testClearContainer()
    {
        $type = 'info';
        $text = 'foo bar';

        $this->notification->add($type, $text);

        $plugin = new Notification();
        $plugin->get($type);
        $container = $plugin->getContainer();
        $sessionNotifications = $container->{$type};

        $this->assertTrue(empty($sessionNotifications));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::has
     */
    public function testHasNotifications()
    {
        $type = 'info';
        $text = 'foo bar';

        $this->notification->add($type, $text);
        $plugin = new Notification();

        $this->assertTrue($plugin->has($type));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::get
     */
    public function testGetNotifications()
    {
        $type = 'info';
        $message = 'foo bar';

        $this->notification->add($type, $message);
        $plugin = new Notification();

        $this->assertEquals([$message], $plugin->get($type));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::__invoke
     */
    public function testInvokeMethod()
    {
        $type = 'info';
        $message = 'foo bar';

        $this->notification->__invoke($type, $message);
        $this->assertEquals([$message], $this->notification->getCurrent($type));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::add
     */
    public function testAddNotification()
    {
        $type = 'info';
        $message = 'foo bar';

        $this->notification->add($type, $message);
        $plugin = new Notification();

        $this->assertEquals([$message], $plugin->get($type));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::add
     */
    public function testMultipleNotifications()
    {
        $type = 'info';
        $message = 'foo bar1';
        $message2 = 'foo bar2';

        $this->notification->add($type, $message);
        $this->notification->add($type, $message2);

        $plugin = new Notification();

        $this->assertEquals([$message, $message2], $plugin->get($type));
    }
}

