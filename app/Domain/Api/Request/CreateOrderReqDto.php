<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Apitte\Core\Exception\Api\ClientErrorException;
use App\Domain\Order\OrderProduct;
use Nette\Http\IResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CreateOrderReqDto
{

	/** @Assert\NotBlank */
	#[NotBlank(message: 'customerId is required.')]
	public int $customerId;

	/**
	 * @var array<int, array{productId: int, quantity: int}>
	 * @Assert\NotBlank
	 */
	#[NotBlank(message: 'products are required.')]
	public array $products;

	public float $price;

	public int $orderState;

	public function setState(int $state): void
	{
		$this->orderState = $state;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	public function validateProducts(): void
	{
		$result = [];
		foreach ($this->products as $product) {

			if (!isset($product['productId']) || !isset($product['quantity'])) {
				throw ClientErrorException::create()
					->withMessage('The order products are in an incorrect format.Please ensure that each product is represented as an associative array with keys "productId" and "quantity".')
					->withCode(IResponse::S404_NotFound);
			}

			$result[] = new OrderProduct($product['productId'], $product['quantity']);
		}

		$this->products = $result;
	}

}
