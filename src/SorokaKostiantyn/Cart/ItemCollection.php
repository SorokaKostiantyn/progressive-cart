<?php
namespace SorokaKostiantyn\Cart;

use SorokaKostiantyn\Cart\Helpers\Helpers;
use Illuminate\Support\Collection;

class ItemCollection extends Collection
{
    /**
     * Sets the config parameters.
     *
     * @var
     */
    protected $config;

    /**
     * ItemCollection constructor.
     *
     * @param array|mixed $items
     * @param             $config
     */
    public function __construct($items, $config)
    {
        parent::__construct($items);

        $this->config = $config;
    }

    /**
     * get the sum of price
     *
     * @return mixed|null
     */
    public function getPriceSum()
    {
        $sum = $this->price * $this->quantity;
        return Helpers::formatValue($sum, $this->config['format_numbers'], $this->config);
    }

    public function __get($name)
    {
        $value = $this->has($name) ? $this->get($name) : null;

        return $value;
    }

    /**
     * check if item has conditions
     *
     * @return bool
     */
    public function hasConditions()
    {
        if (!isset($this['conditions'])) {
            return false;
        }

        if (is_array($this['conditions'])) {
            return count($this['conditions']) > 0;
        }
        $conditionInstance = "SorokaKostiantyn\\Cart\\CartCondition";
        if ($this['conditions'] instanceof $conditionInstance) {
            return true;
        }

        return false;
    }

    /**
     * check if item has conditions
     *
     * @return mixed|null
     */
    public function getConditions()
    {
        $value = $this->hasConditions() ? $this['conditions'] : [];

        return $value;
    }

    /**
     * get the single price in which conditions are already applied
     * @param bool $formatted
     * @return mixed|null
     */
    public function getPriceWithConditions($formatted = true)
    {
        $originalPrice = $this->price;
        $newPrice = 0.00;
        $processed = 0;

        if ($this->hasConditions()) {
            if (is_array($this->conditions)) {
                foreach ($this->conditions as $condition) {
                    ($processed > 0) ? $toBeCalculated = $newPrice : $toBeCalculated = $originalPrice;
                    $newPrice = $condition->applyCondition($toBeCalculated);
                    $processed++;
                }
            } else {
                $newPrice = $this['conditions']->applyCondition($originalPrice);
            }

            return Helpers::formatValue($newPrice, $formatted, $this->config);
        }
        return Helpers::formatValue($originalPrice, $formatted, $this->config);
    }

    /**
     * get the sum of price in which conditions are already applied
     * @param bool $formatted
     * @return mixed|null
     */
    public function getPriceSumWithConditions($formatted = true)
    {
        $sum = $this->getPriceWithConditions(false) * $this->quantity;
        return Helpers::formatValue($sum, $formatted, $this->config);
    }
}
