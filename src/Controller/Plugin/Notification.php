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

namespace SzmNotification\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\ManagerInterface as Manager;
use Zend\Session\Container;
use Zend\Stdlib\SplQueue;

class Notification extends AbstractPlugin
{
    /**
     * Namespace for info notifications
     */
    const NAMESPACE_INFO = 'info';

    /**
     * Namespace for success notifications
     */
    const NAMESPACE_SUCCESS = 'success';

    /**
     * Namespace for warning notifications
     */
    const NAMESPACE_WARNING = 'warning';

    /**
     * Namespace for error notifications
     */
    const NAMESPACE_ERROR = 'error';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Manager
     */
    protected $sessionManager;

    /**
     * @var array
     */
    protected $notifications = [];

    /**
     * @var bool
     */
    protected $isAdded = false;

    /**
     * @var array
     */
    protected $customMethods = [
        'add',
        'get',
        'getcurrent',
        'has',
        'hascurrent'
    ];

    /**
     * @param Manager $manager
     * @return $this
     */
    public function setSessionManager(Manager $manager)
    {
        $this->sessionManager = $manager;

        return $this;
    }

    /**
     * @return Manager
     */
    public function getSessionManager()
    {
        if (!$this->sessionManager instanceof Manager) {
            $this->setSessionManager(Container::getDefaultManager());
        }

        return $this->sessionManager;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        if ($this->container instanceof Container) {
            return $this->container;
        }

        $manager = $this->getSessionManager();
        $this->container = new Container('notifications', $manager);

        return $this->container;
    }

    /**
     * @param $type
     * @param $message
     * @param int $hops
     * @return $this
     */
    public function add($type, $message, $hops = 1)
    {
        $container = $this->getContainer();

        if (!$this->isAdded) {
            $this->getNotificationsFromContainer();
            $container->setExpirationHops($hops);
        }

        if (!isset($container->{$type}) || !$container->{$type} instanceof SplQueue) {
            $container->{$type} = new SplQueue();
        }

        $container->{$type}->push($message);
        $this->isAdded = true;

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function addInfo($message)
    {
        return $this->add(self::NAMESPACE_INFO, $message);
    }

    /**
     * @param $message
     * @return $this
     */
    public function addSuccess($message)
    {
        return $this->add(self::NAMESPACE_SUCCESS, $message);
    }

    /**
     * @param $message
     * @return $this
     */
    public function addWarning($message)
    {
        return $this->add(self::NAMESPACE_WARNING, $message);
    }

    /**
     * @param $message
     * @return $this
     */
    public function addError($message)
    {
        return $this->add(self::NAMESPACE_ERROR, $message);
    }

    /**
     * @param $type
     * @param $message
     * @return $this
     */
    public function __invoke($type = null, $message = null)
    {
        if (!$type || !$message) {
            return $this;
        }

        return $this->add($type, $message);
    }

    /**
     * @param $namespace
     * @return array
     */
    public function get($namespace)
    {
        if ($this->has($namespace)) {
            return $this->notifications[$namespace]->toArray();
        }

        return [];
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $this->getNotificationsFromContainer();

        return $this->notifications;
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return $this->get(self::NAMESPACE_INFO);
    }

    /**
     * @return array
     */
    public function getSuccess()
    {
        return $this->get(self::NAMESPACE_SUCCESS);
    }

    /**
     * @return array
     */
    public function getWarning()
    {
        return $this->get(self::NAMESPACE_WARNING);
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->get(self::NAMESPACE_ERROR);
    }

    /**
     * @param $namespace
     * @return bool
     */
    public function has($namespace)
    {
        $this->getNotificationsFromContainer();

        return isset($this->notifications[$namespace]);
    }

    /**
     * @return bool
     */
    public function hasInfo()
    {
        return $this->has(self::NAMESPACE_INFO);
    }

    /**
     * @return bool
     */
    public function hasSuccess()
    {
        return $this->has(self::NAMESPACE_SUCCESS);
    }

    /**
     * @return bool
     */
    public function hasWarning()
    {
        return $this->has(self::NAMESPACE_WARNING);
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->has(self::NAMESPACE_ERROR);
    }

    /**
     * @param $namespace
     * @return array|mixed
     */
    public function getCurrent($namespace)
    {
        if ($this->hasCurrent($namespace)) {
            $container = $this->getContainer();

            return $container->{$namespace}->toArray();
        }

        return [];
    }

    /**
     * Get all current notifications
     *
     * @return array
     */
    public function getAllCurrent()
    {
        $notifications = [];
        $container = $this->getContainer();

        foreach ($container as $namespace => $notification) {
            $notifications[$namespace] = $this->getCurrent($namespace);
        }

        return $notifications;
    }

    /**
     * Get info notifications added during this request
     *
     * @return array|mixed
     */
    public function getCurrentInfo()
    {
        return $this->getCurrent(self::NAMESPACE_INFO);
    }

    /**
     * Get success notifications added during this request
     *
     * @return array|mixed
     */
    public function getCurrentSuccess()
    {
        return $this->getCurrent(self::NAMESPACE_SUCCESS);
    }

    /**
     * Get warning notifications added during this request
     *
     * @return array|mixed
     */
    public function getCurrentWarning()
    {
        return $this->getCurrent(self::NAMESPACE_WARNING);
    }

    /**
     * Get error notifications added during this request
     *
     * @return array|mixed
     */
    public function getCurrentError()
    {
        return $this->getCurrent(self::NAMESPACE_ERROR);
    }

    /**
     * @param $namespace
     * @return bool
     */
    public function hasCurrent($namespace)
    {
        $container = $this->getContainer();

        return isset($container->{$namespace});
    }

    /**
     * Check if info notifications have been added during this request
     *
     * @return bool
     */
    public function hasCurrentInfo()
    {
        return $this->hasCurrent(self::NAMESPACE_INFO);
    }

    /**
     * Check if success notifications have been added during this request
     *
     * @return bool
     */
    public function hasCurrentSuccess()
    {
        return $this->hasCurrent(self::NAMESPACE_SUCCESS);
    }

    /**
     * Check if warning notifications have been added during this request
     *
     * @return bool
     */
    public function hasCurrentWarning()
    {
        return $this->hasCurrent(self::NAMESPACE_WARNING);
    }

    /**
     * Check if error notifications have been added during this request
     *
     * @return bool
     */
    public function hasCurrentError()
    {
        return $this->hasCurrent(self::NAMESPACE_ERROR);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // we take first 3 chars from called method
        $length = 3;
        $name = strtolower($name);
        if (strpos($name, 'current')) {
            $length += strlen('current');
        }

        $method = substr($name, 0, $length);
        if (!in_array($method, $this->customMethods)) {
            return false;
        }

        $namespace = substr($name, $length);
        if ($method !== 'add') {
            return $this->$method($namespace);
        }

        // If user called add method then we have to check if he provided notification text
        if (!isset($arguments[0]) || !is_string($arguments[0])) {
            throw new \InvalidArgumentException(sprintf(
                '%s method must contains notification text, %s given.',
                $name,
                isset($arguments[0]) ? gettype($arguments[0]) : null
            ));
        }

        $this->$method($namespace, $arguments[0]);
        return $this;
    }

    /**
     * Clear notifications from container
     */
    protected function getNotificationsFromContainer()
    {
        if (!empty($this->notifications) || $this->isAdded) {
            return;
        }

        $container = $this->getContainer();
        $namespaces = [];

        foreach ($container as $namespace => $notification) {
            $this->notifications[$namespace] = $notification;
            $namespaces[] = $namespace;
        }

        $this->clearNotificationsFromContainer($namespaces);
    }

    /**
     * @param $namespaces
     */
    protected function clearNotificationsFromContainer($namespaces)
    {
        $namespaces = is_array($namespaces) ? $namespaces : [$namespaces];
        $container = $this->getContainer();

        foreach ($namespaces as $namespace) {
            unset($container->{$namespace});
        }
    }

    /**
     * Clear notifications from container if namespace is provided or clear all notifications
     * added during this request if namespace is not provided.
     *
     * @param null $namespace
     * @return bool
     */
    public function clearCurrent($namespace = null)
    {
        $container = $this->getContainer();

        if ($namespace) {
            if (!$container->offsetExists($namespace)) {
                return false;
            }

            unset($container->{$namespace});
            return true;
        }

        foreach ($container as $namespace => $notifications) {
            $container->offsetUnset($namespace);
        }

        return true;
    }

    /**
     * Clear notifications from previous request by provided namespace or clear all
     * notifications if namespace is not provided
     *
     * @param null $namespace
     * @return bool
     */
    public function clear($namespace = null)
    {
        $this->getNotificationsFromContainer();

        if ($namespace) {
            if (isset($this->notifications[$namespace])) {
                unset($this->notifications[$namespace]);
                return true;
            }

            return false;
        }

        foreach ($this->notifications as $nm => $type) {
            unset($this->notifications[$nm]);
        }

        return true;
    }
}