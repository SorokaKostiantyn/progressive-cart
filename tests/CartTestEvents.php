<?php

use SorokaKostiantyn\Cart\Cart;
use Mockery as m;

require_once __DIR__.'/helpers/SessionMock.php';

class CartTestEvents extends PHPUnit\Framework\TestCase {

    const CART_INSTANCE_NAME = 'shopping';

    public function setUp(){}

    public function tearDown()
    {
        m::close();
    }

    public function test_event_cart_created()
    {
        $events = m::mock('Illuminate\Contracts\Events\Dispatcher');
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME.'.created', m::type('array'));

        $cart = new Cart(
            new SessionMock(),
            $events,
            self::CART_INSTANCE_NAME,
            'SAMPLESESSIONKEY',
            require(__DIR__.'/helpers/configMock.php')
        );

        $this->assertTrue(true);
    }

    public function test_event_cart_adding()
    {
        $events = m::mock('Illuminate\Events\Dispatcher');
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME.'.created', m::type('array'));
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME.'.adding', m::type('array'));
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME.'.added', m::type('array'));

        $cart = new Cart(
            new SessionMock(),
            $events,
            self::CART_INSTANCE_NAME,
            'SAMPLESESSIONKEY',
            require(__DIR__.'/helpers/configMock.php')
        );

        $cart->add(455, 'Sample Item', 100.99, 2, []);

        $this->assertTrue(true);
    }

    public function test_event_cart_adding_multiple_times()
    {
        $events = m::mock('Illuminate\Events\Dispatcher');
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME.'.created', m::type('array'));
        $events->shouldReceive('dispatch')->times(2)->with(self::CART_INSTANCE_NAME.'.adding', m::type('array'));
        $events->shouldReceive('dispatch')->times(2)->with(self::CART_INSTANCE_NAME.'.added', m::type('array'));

        $cart = new Cart(
            new SessionMock(),
            $events,
            self::CART_INSTANCE_NAME,
            'SAMPLESESSIONKEY',
            require(__DIR__.'/helpers/configMock.php')
        );

        $cart->add(455, 'Sample Item 1', 100.99, 2, []);
        $cart->add(562, 'Sample Item 2', 100.99, 2, []);

        $this->assertTrue(true);
    }

    public function test_event_cart_adding_multiple_times_scenario_two()
    {
        $events = m::mock('Illuminate\Events\Dispatcher');
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME . '.created', m::type('array'));
        $events->shouldReceive('dispatch')->times(3)->with(self::CART_INSTANCE_NAME . '.adding', m::type('array'));
        $events->shouldReceive('dispatch')->times(3)->with(self::CART_INSTANCE_NAME . '.added', m::type('array'));

        $items = [
            [
                'id' => 456, 'name' => 'Sample Item 1', 'price' => 67.99, 'quantity' => 4, 'attributes' => [],
            ], [
                'id' => 568, 'name' => 'Sample Item 2', 'price' => 69.25, 'quantity' => 4, 'attributes' => [],
            ], [
                'id' => 856, 'name' => 'Sample Item 3', 'price' => 50.25, 'quantity' => 4, 'attributes' => [],
            ],
        ];

        $cart = new Cart(
            new SessionMock(), $events, self::CART_INSTANCE_NAME, 'SAMPLESESSIONKEY',
            require(__DIR__ . '/helpers/configMock.php')
        );

        $cart->add($items);

        $this->assertTrue(true);
    }

    public function test_event_cart_remove_item()
    {
        $events = m::mock('Illuminate\Events\Dispatcher');
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME . '.created', m::type('array'));
        $events->shouldReceive('dispatch')->times(3)->with(self::CART_INSTANCE_NAME . '.adding', m::type('array'));
        $events->shouldReceive('dispatch')->times(3)->with(self::CART_INSTANCE_NAME . '.added', m::type('array'));
        $events->shouldReceive('dispatch')->times(1)->with(self::CART_INSTANCE_NAME . '.removing', m::type('array'));
        $events->shouldReceive('dispatch')->times(1)->with(self::CART_INSTANCE_NAME . '.removed', m::type('array'));

        $items = [
            [
                'id' => 456, 'name' => 'Sample Item 1', 'price' => 67.99, 'quantity' => 4, 'attributes' => [],
            ], [
                'id' => 568, 'name' => 'Sample Item 2', 'price' => 69.25, 'quantity' => 4, 'attributes' => [],
            ], [
                'id' => 856, 'name' => 'Sample Item 3', 'price' => 50.25, 'quantity' => 4, 'attributes' => [],
            ],
        ];

        $cart = new Cart(
            new SessionMock(), $events, self::CART_INSTANCE_NAME, 'SAMPLESESSIONKEY',
            require(__DIR__ . '/helpers/configMock.php')
        );

        $cart->add($items);

        $cart->remove(456);

        $this->assertTrue(true);
    }

    public function test_event_cart_clear()
    {
        $events = m::mock('Illuminate\Events\Dispatcher');
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME . '.created', m::type('array'));
        $events->shouldReceive('dispatch')->times(3)->with(self::CART_INSTANCE_NAME . '.adding', m::type('array'));
        $events->shouldReceive('dispatch')->times(3)->with(self::CART_INSTANCE_NAME . '.added', m::type('array'));
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME . '.clearing', m::type('array'));
        $events->shouldReceive('dispatch')->once()->with(self::CART_INSTANCE_NAME . '.cleared', m::type('array'));

        $items = [
            [
                'id' => 456, 'name' => 'Sample Item 1', 'price' => 67.99, 'quantity' => 4, 'attributes' => [],
            ], [
                'id' => 568, 'name' => 'Sample Item 2', 'price' => 69.25, 'quantity' => 4, 'attributes' => [],
            ], [
                'id' => 856, 'name' => 'Sample Item 3', 'price' => 50.25, 'quantity' => 4, 'attributes' => [],
            ],
        ];

        $cart = new Cart(
            new SessionMock(), $events, self::CART_INSTANCE_NAME, 'SAMPLESESSIONKEY',
            require(__DIR__ . '/helpers/configMock.php')
        );

        $cart->add($items);

        $cart->clear();

        $this->assertTrue(true);
    }
}