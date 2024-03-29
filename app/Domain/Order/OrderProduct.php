<?php declare(strict_types = 1);

namespace App\Domain\Order;

class OrderProduct
{

	private int $productId;

	private int $quantity;

	public function __construct(int $productId, int $quantity)
	{
		$this->productId = $productId;
		$this->quantity = $quantity;
	}

	public function getProductId(): int
	{
		return $this->productId;
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

}
