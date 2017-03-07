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

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::addInfo
     * @covers SzmNotification\Controller\Plugin\Notification::getInfo
     */
    public function testAddAndGetInfoNotifications()
    {
        $message = 'foo bar';
        $this->notification->addInfo($message);

        $plugin = new Notification();

        $this->assertEquals([$message], $plugin->getInfo());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::hasInfo
     */
    public function testHasInfoNotification()
    {
        $message = 'foo bar';
        $this->notification->addInfo($message);

        $plugin = new Notification();

        $this->assertTrue($plugin->hasInfo());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::addSuccess
     * @covers SzmNotification\Controller\Plugin\Notification::getSuccess
     */
    public function testAddAndGetSuccessNotifications()
    {
        $message = 'foo bar';
        $this->notification->addSuccess($message);

        $plugin = new Notification();

        $this->assertEquals([$message], $plugin->getSuccess());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::hasSuccess
     */
    public function testHasSuccessNotification()
    {
        $message = 'foo bar';
        $this->notification->addSuccess($message);

        $plugin = new Notification();

        $this->assertTrue($plugin->hasSuccess());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::addWarning
     * @covers SzmNotification\Controller\Plugin\Notification::getWarning
     */
    public function testAddAndGetWarningNotifications()
    {
        $message = 'foo bar';
        $this->notification->addWarning($message);

        $plugin = new Notification();

        $this->assertEquals([$message], $plugin->getWarning());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::hasWarning
     */
    public function testHasWarningNotification()
    {
        $message = 'foo bar';
        $this->notification->addWarning($message);

        $plugin = new Notification();

        $this->assertTrue($plugin->hasWarning());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::addError
     * @covers SzmNotification\Controller\Plugin\Notification::getError
     */
    public function testAddAndGetErrorNotifications()
    {
        $message = 'foo bar';
        $this->notification->addError($message);

        $plugin = new Notification();

        $this->assertEquals([$message], $plugin->getError());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::hasError
     */
    public function testHasErrorNotification()
    {
        $message = 'foo bar';
        $this->notification->addError($message);

        $plugin = new Notification();

        $this->assertTrue($plugin->hasError());
    }
}

