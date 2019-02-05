<?php

namespace Jadu\AddressFinderClient\Model\Address\Contract;

use DateTime;

interface AddressInterface extends FormattableAddressInterface, HydratableAddressInterface
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
     * Get the address reference.
     *
     * @return string
     */
    public function getReference();

    /**
     * The unique reference that the address provider uses to identify the address, if not the UPRN.
     *
     * @return mixed
     */
    public function getExternalReference();

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @return DateTime
     */
    public function getUpdatedAt();

    /**
     * @return int
     */
    public function getVersion();

    /**
     * @return string
     */
    public function getUprn();

    /**
     * @return string
     */
    public function getUsrn();
}
