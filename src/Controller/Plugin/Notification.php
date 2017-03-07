<?php

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
     * @param $namespace
     * @return bool
     */
    public function has($namespace)
    {
        $this->getNotificationsFromContainer();

        return isset($this->notifications[$namespace]);
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
}