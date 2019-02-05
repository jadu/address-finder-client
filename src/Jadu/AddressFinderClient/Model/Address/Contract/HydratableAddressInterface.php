<?php

namespace Jadu\AddressFinderClient\Model\Address\Contract;

use DateTime;

interface HydratableAddressInterface
{
    /**
     * Set PAON.
     *
     * @param string $paon
     */
    public function setPaon($paon);

    /**
     * Set SAON (e.g. flat number).
     *
     * @param string $saon
     */
    public function setSaon($saon);

    /**
     * Set street.
     *
     * @param string $street
     */
    public function setStreet($street);

    /**
     * Set locality.
     *
     * @param string $locality
     */
    public function setLocality($locality);

    /**
     * Set town.
     *
     * @param string $town
     */
    public function setTown($town);

    /**
     * Set postal town.
     *
     * @param string $postTown
     */
    public function setPostTown($postTown);

    /**
     * Set postal code.
     *
     * @param string $postCode
     */
    public function setPostCode($postCode);

    /**
     * Set address type.
     *
     * @param string $type
     */
    public function setType($type);

    /**
     * Set administrative area.
     *
     * @param string $administrativeArea
     */
    public function setAdministrativeArea($administrativeArea);

    /**
     * Set easting.
     *
     * @param int $easting
     */
    public function setEasting($easting);

    /**
     * Set northing.
     *
     * @param int $northing
     */
    public function setNorthing($northing);

    /**
     * Set UPRN.
     *
     * @param string $uprn
     */
    public function setUprn($uprn);

    /**
     * Set USRN.
     *
     * @param string $usrn
     */
    public function setUsrn($usrn);

    /**
     * Set external reference.
     *
     * @param string $externalReference
     */
    public function setExternalReference($externalReference);

    /**
     * Set the address reference.
     *
     * @param string $reference
     */
    public function setReference($reference);

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt);

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt);

    /**
     * @param int $version
     */
    public function setVersion($version);
}
