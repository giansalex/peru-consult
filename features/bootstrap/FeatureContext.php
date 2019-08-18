<?php

use Behat\Behat\Context\Context;
use Peru\Http\ContextClient;
use Peru\Http\EmptyResponseDecorator;
use Peru\Jne\Dni;
use Peru\Jne\DniParser;
use Peru\Reniec\Person;
use Peru\Sunat\Company;
use Peru\Sunat\HtmlParser;
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
     * @Given hay un documento :document
     */
    public function thereIsADocument($document)
    {
        $this->document = $document;
    }

    /**
     * @When ejecuto la consulta
     */
    public function executeConsult()
    {
        $client = new EmptyResponseDecorator(new ContextClient());
        switch (strlen($this->document)) {
            case 8:
                $cs = new Dni($client, new DniParser());
                $this->result = $cs->get($this->document);
                break;
            case 11:
                $cs = new Ruc($client, new RucParser(new HtmlParser()));
                $this->result = $cs->get($this->document);
                break;
        }
    }

    /**
     * @Then La empresa deberia llamarse :name
     */
    public function theCompanyNameShouldBe($name)
    {
        if (empty($this->result)) {
            return;
        }

        /**@var $company Company */
        $company = $this->result;
        Assert::assertSame(
            $name,
            $company->razonSocial
        );
    }

    /**
     * @Then La persona deberia llamarse :name
     */
    public function thePersonNameShouldBe($name)
    {
        if (empty($this->result)) {
            return;
        }
        /**@var $person Person */
        $person = $this->result;
        Assert::assertSame(
            $name,
            $person->nombres
        );
    }
}
