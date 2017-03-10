<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

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
     * @covers SzmNotification\Controller\Plugin\Notification::__invoke
     */
    public function testInvokeWithNoParams()
    {
        $this->assertInstanceOf(Notification::class, $this->notification->__invoke());
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

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::__call
     */
    public function testCustomNotificationAdderAndGetter()
    {
        $message = 'foo bar';
        $adder = 'addCustom';
        $getter = 'getCurrentCustom';

        $this->notification->$adder($message);

        $this->assertEquals([$message], $this->notification->$getter());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::__call
     */
    public function testInvalidCustomNotificationSetter()
    {
        $message = 'foo bar';
        $adder = 'invalidAdder';

        $this->assertEquals(null, $this->notification->$adder($message));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::__call
     */
    public function testInvalidCustomNotificationGetter()
    {
        $message = 'foo bar';
        $adder = 'addCustom';
        $getter = 'invalidGetter';

        $this->assertEquals($this->notification, $this->notification->$adder($message));
        $this->assertEquals(null, $this->notification->$getter());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::__call
     */
    public function testCustomNotificationFromPreviousRequest()
    {
        $message = 'foo bar';
        $adder = 'addCustom';
        $getter = 'getCustom';
        $this->notification->$adder($message);

        $plugin = new Notification();
        $this->assertEquals([$message], $plugin->$getter());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::__call
     */
    public function testCustomAdderWithoutText()
    {
        $adder = 'addCustom';

        $this->expectException(\InvalidArgumentException::class);
        $this->notification->$adder();
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::__call
     */
    public function testCustomHasMethod()
    {
        $message = 'foo bar';
        $adder = 'addCustom';
        $has = 'hasCustom';

        $this->notification->$adder($message);
        $plugin = new Notification();

        $this->assertTrue($plugin->$has());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::__call
     */
    public function testCurrentCustomHasMethod()
    {
        $message = 'foo bar';
        $adder = 'addCustom';
        $has = 'hasCurrentCustom';

        $this->notification->$adder($message);

        $this->assertTrue($this->notification->$has());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getCurrentInfo
     */
    public function testGetCurrentInfo()
    {
        $message = 'foo bar';
        $this->notification->addInfo($message);

        $this->assertEquals([$message], $this->notification->getCurrentInfo());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::hasCurrentInfo
     */
    public function testHasCurrentInfo()
    {
        $message = 'foo bar';
        $this->notification->addInfo($message);

        $this->assertTrue($this->notification->hasCurrentInfo());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getCurrentSuccess
     */
    public function testGetCurrentSuccess()
    {
        $message = 'foo bar';
        $this->notification->addSuccess($message);

        $this->assertEquals([$message], $this->notification->getCurrentSuccess());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::hasCurrentSuccess
     */
    public function testHasCurrentSuccess()
    {
        $message = 'foo bar';
        $this->notification->addSuccess($message);

        $this->assertTrue($this->notification->hasCurrentSuccess());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getCurrentWarning
     */
    public function testGetCurrentWarning()
    {
        $message = 'foo bar';
        $this->notification->addWarning($message);

        $this->assertEquals([$message], $this->notification->getCurrentWarning());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::hasCurrentWarning
     */
    public function testHasCurrentWarning()
    {
        $message = 'foo bar';
        $this->notification->addWarning($message);

        $this->assertTrue($this->notification->hasCurrentWarning());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getCurrentError
     */
    public function testGetCurrentError()
    {
        $message = 'foo bar';
        $this->notification->addError($message);

        $this->assertEquals([$message], $this->notification->getCurrentError());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getCurrentError
     */
    public function testHasCurrentError()
    {
        $message = 'foo bar';
        $this->notification->addError($message);

        $this->assertTrue($this->notification->hasCurrentError());
    }

    public function testGetNotificationsFromPreviousRequest()
    {
        $type = 'info';
        $message = 'prev request';

        $this->notification->add($type, $message);

        $plugin = new Notification();
        $this->assertEquals([$message], $plugin->get($type));
    }

    public function testGetNotificationsFromCurrentRequest()
    {
        $type = 'info';
        $message = 'prev request';

        $this->notification->add($type, $message);

        $plugin = new Notification();
        $this->assertEquals([$message], $plugin->getCurrent($type));
    }

    public function testGetNotificationsFromPrevAndCurrentRequest()
    {
        $type = 'info';
        $prevMessage = 'prev request';
        $currentMessage = 'current request';

        $this->notification->add($type, $prevMessage);

        $plugin = new Notification();
        $plugin->add($type, $currentMessage);

        $prevResult = $plugin->get($type);
        $currentResult = $plugin->getCurrent($type);

        $this->assertEquals([$prevMessage, $currentMessage], array_merge($prevResult, $currentResult));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::clearCurrent
     */
    public function testClearCurrentNotifications()
    {
        $type = 'info';
        $prevMessage = 'prev request';
        $currentMessage = 'current request';

        $this->notification->add($type, $prevMessage);

        $plugin = new Notification();
        $plugin->add($type, $currentMessage);

        $plugin->clearCurrent();

        $this->assertTrue($plugin->has($type));
        $this->assertFalse($plugin->hasCurrent($type));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::clearCurrent
     */
    public function testClearSingleCurrentNotifications()
    {
        $type = 'info';
        $type2 = 'success';
        $message = 'foo bar';
        $message2 = 'baz bar';

        $this->notification->add($type, $message);
        $this->notification->add($type2, $message2);

        $this->notification->clearCurrent($type);

        $this->assertFalse($this->notification->hasCurrentInfo());
        $this->assertEquals([$message2], $this->notification->getCurrentSuccess());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::clear
     */
    public function testClearNotifications()
    {
        $type = 'info';
        $prevMessage = 'prev request';
        $currentMessage = 'current request';

        $this->notification->add($type, $prevMessage);

        $plugin = new Notification();
        $plugin->add($type, $currentMessage);

        $plugin->clear();

        $this->assertFalse($plugin->has($type));
        $this->assertEquals([$currentMessage], $plugin->getCurrent($type));
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::clear
     */
    public function testClearSingleNotification()
    {
        $type = 'info';
        $type2 = 'success';
        $message = 'foo bar';
        $message2 = 'bar baz';

        $this->notification->add($type, $message);
        $this->notification->add($type2, $message2);

        $plugin = new Notification();
        $plugin->clear($type);

        $this->assertFalse($plugin->hasInfo());
        $this->assertEquals([$message2], $plugin->getSuccess());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getAll
     */
    public function testGetAll()
    {
        $type = 'info';
        $type2 = 'success';
        $message = 'foo bar';
        $message2 = 'bar baz';

        $this->notification->add($type, $message);
        $this->notification->add($type2, $message2);

        $plugin = new Notification();

        $this->assertArrayHasKey($type, $plugin->getAll());
        $this->assertArrayHasKey($type2, $plugin->getAll());
    }

    /**
     * @covers SzmNotification\Controller\Plugin\Notification::getAllCurrent
     */
    public function testGetAllCurrent()
    {
        $type = 'info';
        $type2 = 'success';
        $message = 'foo bar';
        $message2 = 'bar baz';

        $this->notification->add($type, $message);
        $this->notification->add($type2, $message2);

        $this->assertArrayHasKey($type, $this->notification->getAllCurrent());
        $this->assertArrayHasKey($type2, $this->notification->getAllCurrent());
    }
}