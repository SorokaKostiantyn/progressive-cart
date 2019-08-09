<?php
namespace SorokaKostiantyn\Cart;

use Illuminate\Support\Collection;

class ItemAttributeCollection extends Collection
{

    public function __get($name)
    {
        $value = $this->has($name) ? $this->get($name) : null;
        return $value;
    }
}