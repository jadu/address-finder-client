<?php

namespace Jadu\AddressFinderClient\Model;

/**
 * Property.
 *
 * @author Jadu Ltd.
 */
class Property
{
    /**
     * This method will map all the items in the data array to the class.
     *
     *  @param array $data
     */
    public function mapData(array $data)
    {
        foreach ($data as $key => $val) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var string
     */
    public $uprn;

    /**
     * @var string
     */
    public $usrn;

    /**
     * @var string
     */
    public $paon;

    /**
     * @var string
     */
    public $saon;

    /**
     * @var string
     */
    public $street_name;

    /**
     * @var string
     */
    public $town;

    /**
     * @var string
     */
    public $locality;

    /**
     * @var string
     */
    public $post_town;

    /**
     * @var string
     */
    public $post_code;

    /**
     * @var string
     */
    public $easting;

    /**
     * @var string
     */
    public $northing;

    /**
     * @var string
     */
    public $ward;

    /**
     * @var string
     */
    public $logical_status;
}
