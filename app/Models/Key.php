<?php

namespace App\Models;


use JsonSerializable;

class Key implements JsonSerializable
{
    protected $l;
    protected $s;

    public function __construct(array $data)
    {
        $this->l = $data['l'];
        $this->s = $data['s'];
    }

    /**
     * @return mixed
     */
    public function getL()
    {
        return $this->l;
    }

    /**
     * @return mixed
     */
    public function getS()
    {
        return $this->s;
    }

    public function jsonSerialize()
    {
        return
            [
                'l' => $this->getL(),
                's' => $this->getS()
            ];
    }
}
