<?php declare(strict_types = 1);

namespace App\Domain\Api\Response;

use App\Domain\Order\Order;
use App\Domain\Order\OrderProduct;

final class OrderResDto
{

	public int $id;

	public int $customerId;

	/** @var OrderProduct[] */
	public array $products;

	public int $orderState;

	public float $price;

	/**
	 * @param OrderProduct[] $products
	 * @return static
	 */
	public static function from(Order $order, array $products): self
	{
		$self = new self();
		$self->id = $order->getId();
		$self->customerId = $order->getCustomerId();
		$self->products = $products;
		$self->orderState = $order->getOrderState();
		$self->price = $order->getPrice();

		return $self;
	}

}
