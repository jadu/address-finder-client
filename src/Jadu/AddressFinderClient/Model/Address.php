<?php

namespace Jadu\AddressFinderClient\Model;

use Jadu\ContinuumCommon\Address\Model\Address as ContinuumCommonAddress;

/**
 * Class Address.
 *
 * @author Jadu Ltd.
 */
class Address extends ContinuumCommonAddress
{
    /**
     * @var string
     */
    private $logicalStatus;

    /**
     * @return string
     */
    public function getLogicalStatus()
    {
        return $this->logicalStatus;
    }

    /**
     * @param string $logicalStatus
     */
    public function setLogicalStatus($logicalStatus)
    {
        $this->logicalStatus = $logicalStatus;
    }
}
