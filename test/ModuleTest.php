<?php

namespace SzmNotificationTest;

use PHPUnit\Framework\TestCase;
use SzmNotification\Module;

class ModuleTest extends TestCase
{
    /**
     * @var Module
     */
    protected $module;

    public function setUp()
    {
        $this->module = new Module();
    }

    /**
     * @covers SzmNotification\Module::getConfig
     */
    public function testGetConfig()
    {
        $this->assertTrue(is_array($this->module->getConfig()));
    }
}