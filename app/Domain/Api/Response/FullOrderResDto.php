<?php declare(strict_types = 1);

namespace App\Domain\Api\Response;

final class FullOrderResDto
{

	public OrderResDto $order;

	public CustomerResDto $customer;

	/** @var ProductResDto[] */
	public array $products;

	/**
	 * @param ProductResDto[] $products
	 * @return static
	 */
	public static function from(OrderResDto $order, array $products, CustomerResDto $customer): self
	{
		$self = new self();
		$self->order = $order;
		$self->customer = $customer;
		$self->products = $products;

		return $self;
	}

}
