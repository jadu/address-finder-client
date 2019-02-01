<?php

namespace Jadu\AddressFinderClient\Model\Address\Contract;

interface FormattableAddressInterface
{
    /**
     * Get PAON (building number / name).
     *
     * @return string
     */
    public function getPaon();

    /**
     * Get SAON.
     *
     * @return string
     */
    public function getSaon();

    /**
     * Get street.
     *
     * @return string
     */
    public function getStreet();

    /**
     * Get locality.
     *
     * @return string
     */
    public function getLocality();

    /**
     * Get town.
     *
     * @return string
     */
    public function getTown();

    /**
     * Get postal town.
     *
     * @return string
     */
    public function getPostTown();

    /**
     * Get postal code.
     *
     * @return string
     */
    public function getPostCode();

    /**
     * Get type of address.
     *
     * @return string
     */
    public function getType();

    /**
     * Generate a summary of the address.
     *
     * @return string
     */
    public function getSummary();
}
