<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

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
        switch (strlen($this->document)) {
            case 8:
                $this->result = (new \Peru\Reniec\Dni())->get($this->document);
                break;
            case 11:
                $this->result = (new \Peru\Sunat\Ruc())->get($this->document);
                break;
        }
    }

    /**
     * @Then La empresa deberia llamarse :name
     */
    public function theCompanyNameShouldBe($name)
    {
        /**@var $company \Peru\Sunat\Company */
        $company = $this->result;
        PHPUnit_Framework_Assert::assertSame(
            $name,
            $company->razonSocial
        );
    }

    /**
     * @Then La persona deberia llamarse :name
     */
    public function thePersonNameShouldBe($name)
    {
        /**@var $person \Peru\Reniec\Person */
        $person = $this->result;
        PHPUnit_Framework_Assert::assertSame(
            $name,
            $person->nombres
        );
    }
}
