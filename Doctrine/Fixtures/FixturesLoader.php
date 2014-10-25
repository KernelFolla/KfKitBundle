<?php

namespace Kf\KitBundle\Doctrine\Fixtures;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use InvalidArgumentException;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
class FixturesLoader
{
    private $purge = true;

    /** @var ContainerInterface */
    private $container;
    /** @var ObjectManager */
    private $manager;
    /** @var Loader */
    private $loader;

    function __construct(ContainerInterface $container, $manager = null)
    {
        $this->container = $container;
        $this->setManager($manager);
        $this->reset();
    }

    /**
     * @return $this
     */
    public function addAll()
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->container->get('kernel');
        $this->addKernel($kernel);

        return $this;
    }

    /**
     * @param KernelInterface $kernel
     * @return $this
     */
    public function addKernel(KernelInterface $kernel)
    {
        foreach ($kernel->getBundles() as $bundle) {
            $this->addPath($bundle->getPath() . '/DataFixtures/ORM');
        }

        return $this;
    }

    /**
     * @param array $paths
     * @return $this
     */
    public function addPaths(array $paths)
    {
        foreach ($paths as $path) {
            $this->addPath($path);
        }

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addPath($path)
    {
        if (is_dir($path)) {
            $this->loader->loadFromDirectory($path);
        }

        return $this;
    }


    /**
     * @param string $className Class name of fixture
     * @return $this
     */
    public function addClass($className)
    {
        $fixture = new $className();
        if ($this->loader->hasFixture($fixture)) {
            unset($fixture);

            return $this;
        }
        $this->loader->addFixture(new $className);
        if ($fixture instanceof DependentFixtureInterface) {
            foreach ($fixture->getDependencies() as $dependency) {
                $this->addClass($dependency);
            }
        }

        return $this;
    }

    /**
     * @param array $classes
     * @return $this
     */
    public function addClasses(array $classes)
    {
        foreach ($classes as $class) {
            $this->addClass($class);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $fixtures = $this->loader->getFixtures();
        if (!$fixtures) {
            throw new InvalidArgumentException('Could not find any fixtures to load');
        }

        $executor = new ORMExecutor($this->manager, $this->getPurger());
        $executor->execute($fixtures);
        $this->reset();

        return $this;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->loader = new ContainerAwareLoader($this->container);

        return $this;
    }

    /**
     * @return ORMPurger
     */
    private function getPurger()
    {
        if ($this->purge) {
            $purger = new ORMPurger($this->manager);
            $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);

            return $purger;
        } else {
            return null;
        }
    }

    /**
     * @param mixed $manager
     * @return $this
     */
    public function setManager($manager)
    {
        if ($manager instanceof ObjectManager) {
            $this->manager = $manager;
        } else {
            /** @var ManagerRegistry $doctrine */
            $doctrine      = $this->container->get('doctrine');
            $this->manager = $doctrine->getManager($manager);
        }

        return $this;
    }

    /**
     * @param boolean $purge
     * @return $this
     */
    public function setPurge($purge)
    {
        $this->purge = $purge;

        return $this;
    }

} 
