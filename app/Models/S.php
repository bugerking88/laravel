<?php

namespace App\Models;


use JsonSerializable;

class S implements JsonSerializable
{
    protected $b;
    protected $c;
    protected $d;

    public function __construct(array $data)
    {
        $this->b = $data['b'];
        $this->c = $data['c'];
        $this->d = $data['d'];
    }

    /**
     * @return mixed
     */
    public function getB()
    {
        return $this->b;
    }

    /**
     * @return mixed
     */
    public function getC()
    {
        return $this->c;
    }

    /**
     * @return mixed
     */
    public function getD()
    {
        return $this->d;
    }

    public function jsonSerialize()
    {
        return
            [
                'b' => $this->getB(),
                'c' => $this->getC(),
                'd' => $this->getD()
            ];
    }
}
