<?php

namespace App\Services\Binance;

class SymbolFilter
{
    /**
     * @var double
     */
    public $minPrice;

    /**
     * @var double
     */
    public $maxPrice;

    /**
     * @var float
     */
    public $tickSize;

    /**
     * @var float
     */
    public $minQty;

    /**
     * @var float
     */
    public $maxQty;

    /**
     * @var float
     */
    public $stepSize;

    /**
     * @var float
     */
    public $minNotional;
    
    /**
     * @var float
     */
    public $multiplierUp;

    /**
     * @var float
     */
    public $multiplierDown;

    /**
     * @var int
     */
    public $maxNumOrders;

    /**
     * @var float
     */
    public $maxPosition;
    
    public function __construct($filters)
    {
        $this->loadFromArray($filters);
    }

    public function loadFromArray($filters)
    {
        foreach ($filters as $filter) {
            switch ($filter['filterType']) {
                case 'PRICE_FILTER':
                    $this->minPrice = (double) $filter['minPrice'];
                    $this->maxPrice = (double) $filter['maxPrice'];
                    $this->tickSize = (double) $filter['tickSize'];
                    break;
                case 'LOT_SIZE':
                    $this->minQty = (double) $filter['minQty'];
                    $this->maxQty = (double) $filter['maxQty'];
                    $this->stepSize = (double) $filter['stepSize'];
                    break;
                case 'MIN_NOTIONAL':
                    $this->minNotional = (double) $filter['minNotional'];
                    break;
                case 'PERCENT_PRICE':
                    $this->multiplierUp = (double) $filter['multiplierUp'];
                    $this->multiplierDown = (double) $filter['multiplierDown'];
                    break;
                case 'MAX_NUM_ORDERS':
                    $this->maxNumOrders = (int) $filter['maxNumOrders'];
                    break;
                case 'MAX_POSITION':
                    $this->maxPosition = (double) $filter['maxPosition'];
                    break;
            }
        }
    }
    
}