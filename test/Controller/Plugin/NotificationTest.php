<?php

namespace SzmNotificationTest\Controller\Plugin;

use PHPUnit\Framework\TestCase;
use SzmNotification\Controller\Plugin\Notification;

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
}

