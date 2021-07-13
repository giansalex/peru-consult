<?php

use Behat\Behat\Context\Context;
use Peru\Http\CurlClient;
use Peru\Http\EmptyResponseDecorator;
use Peru\Jne\Dni;
use Peru\Jne\DniParser;
use Peru\Reniec\Person;
use Peru\Sunat\Company;
use Peru\Sunat\Parser\HtmlRecaptchaParser;
use Peru\Sunat\Ruc;
use Peru\Sunat\RucParser;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var string
     */
    private $document;

    /**
     * @var mixed
     */
    private $result;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given un documento :documento
     */
    public function thereIsADocument($documento)
    {
        $this->document = $documento;
    }

    /**
     * @When ejecuto la consulta
     */
    public function executeConsult()
    {
        $client = new EmptyResponseDecorator(new CurlClient());
        switch (strlen($this->document)) {
            case 8:
                $cs = new Dni($client, new DniParser());
                $this->result = $cs->get($this->document);
                break;
            case 11:
                $cs = new Ruc($client, new RucParser(new HtmlRecaptchaParser()));
                $this->result = $cs->get($this->document);
                break;
        }
    }

    /**
     * @Then la empresa deberia llamarse :nombres
     */
    public function theCompanyNameShouldBe($nombres)
    {
        if (empty($this->result)) {
            return;
        }

        /**@var $company Company */
        $company = $this->result;
        Assert::assertSame(
            $nombres,
            $company->razonSocial
        );
    }

    /**
     * @Then la persona deberia llamarse :nombres
     */
    public function thePersonNameShouldBe($nombres)
    {
        if (empty($this->result)) {
            return;
        }
        /**@var $person Person */
        $person = $this->result;
        Assert::assertSame(
            $nombres,
            $person->nombres
        );
    }
}
