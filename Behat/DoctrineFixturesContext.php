<?php

namespace Kf\KitBundle\Behat;

use Kf\KitBundle\Doctrine\Fixtures\FixturesLoader;


class DoctrineFixturesContext extends DefaultContext
{
    /**
     * @Given I load all fixtures
     */
    public function iLoadAllFixtures(){
        $fl = new FixturesLoader($this->getContainer());
        $fl->addAll()->execute(); 
    }

    /**
     * @Given I load fixtures in path :path
     */
    public function iLoadFixturesiInPath($path){
        $fl = new FixturesLoader($this->getContainer());
        $fl->addPath($path)->execute(); 
    }

     /**
     * @Given I load fixtures in class :class
     */
    public function iLoadFixturesInClass($class){
        $fl = new FixturesLoader($this->getContainer());
        $fl->addClass($path)->execute(); 
    }
    

    /**
     * @Given I load fixtures in paths :paths
     */
    public function iLoadFixturesiInPaths($paths){
        $fl = new FixturesLoader($this->getContainer());
        $fl->addPaths(explode(',',$paths))->execute(); 
    }

     /**
     * @Given I load fixtures in classes :classes
     */
    public function iLoadFixturesInClasses($classes){
        $fl = new FixturesLoader($this->getContainer());
        $fl->addClasses(explode(',',$classes))->execute(); 
    }
    
}
