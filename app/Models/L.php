<?php

namespace App\Models;

use JsonSerializable;

class L implements JsonSerializable
{
    protected $agent;
    protected $expire;
    protected $customerId;
    protected $licenseId;
    protected $customerName;

    public function __construct(array $data){
        $this -> agent = $data['agent'];
        $this -> expire = $data['expire'];
        $this -> customerId = $data['customerId'];
        $this -> licenseId = $data['licenseId'];
        $this -> customerName = $data['customerName'];
    }

    /**
     * @return mixed
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return mixed
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @return mixed
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @return mixed
     */
    public function getLicenseId()
    {
        return $this->licenseId;
    }

    public function jsonSerialize()
    {
        return
            [
                'agent' => $this->getAgent(),
                'expire' => $this->getExpire(),
                'customerId' => $this->getCustomerId(),
                'licenseId' => $this->getLicenseId(),
                'customerName' => $this->getCustomerName()
            ];
    }
}
