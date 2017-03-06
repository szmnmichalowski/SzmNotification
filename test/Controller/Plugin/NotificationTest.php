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
     * @covers SzmNotification\Controller\Plugin\Noty::setSessionManager
     * @covers SzmNotification\Controller\Plugin\Noty::getSessionManager
     */
    public function testSessionManager()
    {
        $this->assertInstanceOf(Manager::class, $this->notification->getSessionManager());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Noty::getContainer
     */
    public function testSessionContainer()
    {
        $this->assertInstanceOf(Container::class, $this->notification->getContainer());
    }
}

