<?php

use SorokaKostiantyn\Cart\Cart;
use Mockery as m;

require_once __DIR__.'/helpers/SessionMock.php';

class ItemTestOtherFormat extends PHPUnit\Framework\TestCase
{

    /**
     * @var SorokaKostiantyn\Cart\Cart
     */
    protected $cart;

    public function setUp()
    {
        $events = m::mock('Illuminate\Contracts\Events\Dispatcher');
        $events->shouldReceive('dispatch');

        $this->cart = new Cart(
            new SessionMock(),
            $events,
            'shopping',
            'SAMPLESESSIONKEY',
            require(__DIR__.'/helpers/configMockOtherFormat.php')
        );
    }

    public function tearDown()
    {
        m::close();
    }

    public function test_item_get_sum_price_using_property()
    {
        $this->cart->add(455, 'Sample Item', 100.99, 2, []);

        $item = $this->cart->get(455);

        $this->assertEquals('201,980', $item->getPriceSum(), 'Item summed price should be 201.98');
    }

    public function test_item_get_sum_price_using_array_style()
    {
        $this->cart->add(455, 'Sample Item', 100.99, 2, []);

        $item = $this->cart->get(455);

        $this->assertEquals('201,980', $item->getPriceSum(), 'Item summed price should be 201.98');
    }
}