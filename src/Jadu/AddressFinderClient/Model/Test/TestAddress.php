<?php

namespace Jadu\AddressFinderClient\Model\Test;

use JsonSerializable;

/**
 * Class Address.
 *
 * @author Jadu Ltd.
 */
class TestAddress implements JsonSerializable
{

        /**
     * @var string
     */
    const TYPE_STREET = 'street';

    /**
     * @var string
     */
    const TYPE_PROPERTY = 'property';

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $paon;

    /**
     * @var string
     */
    private $saon;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $locality;

    /**
     * @var string
     */
    private $town;

    /**
     * @var string
     */
    private $post_town;

    /**
     * @var string
     */
    private $post_code;

    /**
     * @var int
     */
    private $easting;

    /**
     * @var int
     */
    private $northing;

    /**
     * @var string
     */
    private $uprn;

    /**
     * @var string
     */
    private $usrn;

    /**
     * @var string
     */
    private $logical_status;
  
    /**
     * @var string
     */
    private $type;

    /**
     * set paon.
     *
     * @var string
     */
    public function setPaon($paon)
    {
        $this->paon = $paon;
    }

    /**
     * set saon.
     *
     * @var string
     */
    public function setSaon($saon)
    {
        $this->saon = $saon;
    }

    /**
     * set street.
     *
     * @var string
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * set locality.
     *
     * @var string
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    }

    /**
     * set town.
     *
     * @var string
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * set postTown.
     *
     * @var string
     */
    public function setPostTown($post_town)
    {
        $this->post_town = $post_town;
    }

    /**
     * set postCode.
     *
     * @var string
     */
    public function setPostCode($post_code)
    {
        $this->post_code = $post_code;
    }

    /**
     * set easting.
     *
     * @var string
     */
    public function setEasting($easting)
    {
        $this->easting = $easting;
    }

    /**
     * set northing.
     *
     * @var string
     */
    public function setNorthing($northing)
    {
        $this->northing = $northing;
    }

    /**
     * set uprn.
     *
     * @var string
     */
    public function setUprn($uprn)
    {
        $this->uprn = $uprn;
    }

    /**
     * set usrn.
     *
     * @var string
     */
    public function setUsrn($usrn)
    {
        $this->usrn = $usrn;
    }

    /**
     * set setIdentifier.
     *
     * @var string
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * set logicalStatus.
     *
     * @var string
     */
    public function setLogicalStatus($logical_Status)
    {
        $this->logical_status = $logical_Status;
    }
    
    /**
     * set type.
     *
     * @var string
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get Paon.
     *
     * @return string
     */
    public function getPaon()
    {
        return $this->paon;
    }

    /**
     * Get Saon.
     *
     * @return string
     */
    public function getSaon()
    {
        return $this->saon;
    }

    /**
     * Get Street.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Get Locality.
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Get Town.
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Get PostTown.
     *
     * @return string
     */
    public function getPostTown()
    {
        return $this->post_town;
    }

    /**
     * Get PostCode.
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->post_code;
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
     * Get getIdentifier.
     *
     * @var string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Get logicalStatus.
     *
     * @return string
     */
    public function getLogicalStatus()
    {
        return $this->logical_status;
    }

    /**
     * set type.
     *
     * @var string
     */
    public function getType()
    {
       return $this->type;
    }


    public function jsonSerialize()
    {
        return [
            'identifier' => $this->getIdentifier(),
            'paon' => $this->getPaon(),
            'saon' => $this->getSaon(),
            'street' => $this->getStreet(),
            'locality' => $this->getLocality(),
            'town' => $this->getTown(),
            'post_town' => $this->getPostTown(),
            'post_code' => $this->getPostCode(),
            'easting' => $this->getEasting(),
            'northing' => $this->getNorthing(),
            'uprn' => $this->getUprn(),
            'usrn' => $this->getUsrn(),
            'logical_status' => $this->getLogicalStatus(),
            'type' => $this->getType()
        ];
    }
}
