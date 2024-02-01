<?php declare(strict_types = 1);

namespace App\Domain\Api\Response;

use App\Domain\Product\Product;

final class ProductResDto
{

	public int $id;

	public string $name;

	public float $value;

	public string $stockDesignation;

	public int $stockValue;

	public static function from(Product $product): self
	{
		$self = new self();
		$self->id = $product->getId();
		$self->name = $product->getName();
		$self->value = $product->getValue();
		$self->stockDesignation = $product->getStockDesignation();
		$self->stockValue = $product->getStockValue();

		return $self;
	}

}
