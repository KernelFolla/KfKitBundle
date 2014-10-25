<?php

namespace Kf\KitBundle\Behat;

use Behat\MinkExtension\Context\RawMinkContext;

use Behat\Mink\Exception\UnsupportedDriverActionException,
    Behat\Mink\Driver\BrowserKitDriver,
    Behat\Behat\Event\ScenarioEvent,
    Behat\Behat\Event\OutlineExampleEvent,
    Symfony\Component\BrowserKit\Client;

/**
 * Context class for managing redirects within an application.
 *
 * @author  Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author  Marijn Huizendveld <marijn.huizendveld@gmail.com>
 * @original https://github.com/Behat/CommonContexts/blob/master/Behat/CommonContexts/MinkRedirectContext.php
 */
class MinkRedirectContext extends RawMinkContext
{
    private $always;

    /**
     * Prevent following redirects.
     * @return  void
     * @When /^I do not follow redirects$/
     */
    public function iDoNotFollowRedirects()
    {
        $this->getClient()->followRedirects(false);
    }

    /**
     * Prevent following redirects always.
     * @return  void
     * @When /^I do not follow redirects always$/
     */
    public function iDoNotFollowRedirectsAlways()
    {
        $this->iDoNotFollowRedirects();
        $this->always = true;
    }


    /**
     * @param ScenarioEvent|OutlineExampleEvent $event
     * @return void
     * @AfterScenario
     */
    public function afterScenario($event)
    {
        if ($this->getSession()->getDriver() instanceof BrowserKitDriver) {
            $this->getClient()->followRedirects(true);
        }
    }

    /**
     * Follow redirect instructions.
     *
     * @param   string $page
     * @return  void
     * @Then /^I (?:am|should be) redirected(?: to "([^"]*)")?$/
     */
    public function iAmRedirected($page = null)
    {
        $headers = $this->getSession()->getResponseHeaders();

        if (empty($headers['Location']) && empty($headers['location'])) {
            throw new \RuntimeException('The response should contain a "Location" header');
        }

        if (null !== $page) {
            $header = empty($headers['Location']) ? $headers['location'] : $headers['Location'];
            if (is_array($header)) {
                $header = current($header);
            }

            \PHPUnit_Framework_Assert::assertEquals(
                $header,
                $this->locatePath($page),
                'The "Location" header points to the correct URI'
            );
        }

        $client = $this->getClient();

        $client->followRedirects(true);
        $client->followRedirect();
        if ($this->always) {
            $this->followRedirects(false);
        }
    }

    /**
     * Returns current active mink session.
     *
     * @throws UnsupportedDriverActionException
     * @return  Client
     *
     */
    protected function getClient()
    {
        $driver = $this->getSession()->getDriver();

        if (!$driver instanceof BrowserKitDriver) {
            $message = 'This step is only supported by the browserkit drivers';
            throw new UnsupportedDriverActionException($message, $driver);
        }

        return $driver->getClient();
    }
}