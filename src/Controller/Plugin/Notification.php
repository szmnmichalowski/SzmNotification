<?php

namespace SzmNotification\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\ManagerInterface as Manager;
use Zend\Session\Container;

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