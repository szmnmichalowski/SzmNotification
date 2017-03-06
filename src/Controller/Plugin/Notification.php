<?php

namespace SzmNotification\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\ManagerInterface as Manager;
use Zend\Session\Container;

class Notification extends AbstractPlugin
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Manager
     */
    protected $sessionManager;

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
}