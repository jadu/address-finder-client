<?php

namespace Jadu\AddressFinderClient\Model\Address\Model;

use DateTime;
use Jadu\AddressFinderClient\Model\Address\Contract\AddressInterface;
use Jadu\AddressFinderClient\Model\Address\Formatter\AddressSummaryFormatter;

/**
 * Class Address.
 *
 * @author Jadu Ltd.
 */
class Address implements AddressInterface
{
    /**
     * @var string
     */
    protected $paon;

    /**
     * @var string
     */
    protected $saon;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $locality;

    /**
     * @var string
     */
    protected $town;

    /**
     * @var string
     */
    protected $postTown;

    /**
     * @var string
     */
    protected $postCode;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $administrativeArea;

    /**
     * @var int
     */
    protected $easting;

    /**
     * @var int
     */
    protected $northing;

    /**
     * @var string
     */
    protected $uprn;

    /**
     * @var string
     */
    protected $usrn;

    /**
     * @var string
     */
    protected $externalReference;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * @var int
     */
    protected $version;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
        $this->version = 1;
    }

    /**
     * {@inheritdoc}
     */
    public function setPaon($paon)
    {
        $this->paon = $paon;
    }

    /**
     * {@inheritdoc}
     */
    public function setSaon($saon)
    {
        $this->saon = $saon;
    }

    /**
     * {@inheritdoc}
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    }

    /**
     * {@inheritdoc}
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostTown($postTown)
    {
        $this->postTown = $postTown;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function setAdministrativeArea($administrativeArea)
    {
        $this->administrativeArea = $administrativeArea;
    }

    /**
     * {@inheritdoc}
     */
    public function setEasting($easting)
    {
        $this->easting = $easting;
    }

    /**
     * {@inheritdoc}
     */
    public function setNorthing($northing)
    {
        $this->northing = $northing;
    }

    /**
     * {@inheritdoc}
     */
    public function setUprn($uprn)
    {
        $this->uprn = $uprn;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsrn($usrn)
    {
        $this->usrn = $usrn;
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalReference($externalReference)
    {
        $this->externalReference = $externalReference;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaon()
    {
        return $this->paon;
    }

    /**
     * {@inheritdoc}
     */
    public function getSaon()
    {
        return $this->saon;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * {@inheritdoc}
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostTown()
    {
        return $this->postTown;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get administrative area.
     *
     * @return string
     */
    public function getAdministrativeArea()
    {
        return $this->administrativeArea;
    }

    /**
     * Get easting.
     *
     * @return int
     */
    public function getEasting()
    {
        return $this->easting;
    }

    /**
     * Get northing.
     *
     * @return int
     */
    public function getNorthing()
    {
        return $this->northing;
    }

    /**
     * Get UPRN.
     *
     * @return string
     */
    public function getUprn()
    {
        return $this->uprn;
    }

    /**
     * Get USRN.
     *
     * @return string
     */
    public function getUsrn()
    {
        return $this->usrn;
    }

    /**
     * Get external reference.
     *
     * @return string
     */
    public function getExternalReference()
    {
        return $this->externalReference;
    }

    /**
     * Get address summary.
     *
     * {@inheritdoc}
     */
    public function getSummary()
    {
        return AddressSummaryFormatter::generateFromAddress($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * {@inheritdoc}
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
