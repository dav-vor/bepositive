<?php declare(strict_types = 1);

namespace App\Domain\Api\Facade;

use Apitte\Core\Exception\Api\ServerErrorException;
use App\Domain\Api\Request\CreateProductReqDto;
use App\Domain\Api\Request\DeleteProductReqDto;
use App\Domain\Api\Request\UpdateProductReqDto;
use App\Domain\Api\Response\ProductResDto;
use App\Domain\Order\OrderProduct;
use App\Domain\Product\Product;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class ProductsFacade
{

	public function __construct(private EntityManagerDecorator $em)
	{
	}

	/**
	 * @param  mixed[]  $criteria
	 * @param  string[] $orderBy
	 * @return ProductResDto[]
	 */
	public function findBy(array $criteria = [], array $orderBy = ['id' => 'ASC'], int $limit = 10, int $offset = 0): array
	{
		$entities = $this->em->getRepository(Product::class)->findBy($criteria, $orderBy, $limit, $offset);

		$result = [];

		foreach ($entities as $entity) {
			$result[] = ProductResDto::from($entity);
		}

		return $result;
	}

	/**
	 * @return ProductResDto[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[]  $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): ProductResDto
	{
		$entity = $this->em->getRepository(Product::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return ProductResDto::from($entity);
	}

	public function findOne(int $id): ProductResDto
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function create(CreateProductReqDto $dto): Product
	{
		$product = new Product(
			$dto->name,
			$dto->value,
			$dto->stockDesignation,
			$dto->stockValue,
		);

		$this->em->persist($product);
		$this->em->flush($product);

		return $product;
	}

	public function findProductById(int $id): Product
	{
		$entity = $this->em->getRepository(Product::class)->findOneBy(['id' => $id]);
		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return $entity;
	}

	/**
	 * @param OrderProduct[] $orderProducts
	 */
	public function processOrderProducts(array $orderProducts): float
	{
		$products = [];
		$price = 0;
		foreach ($orderProducts as $orderProduct) {
			$quantity = $orderProduct->getQuantity();
			if ($quantity < 0) {
				throw ServerErrorException::create()
				 ->withMessage('Cannot create order: quantity cannot be negative');
			}

			$product = $this->em->getRepository(Product::class)->findOneBy(['id' => $orderProduct->getProductId()]);
			if (!$product) {
				throw ServerErrorException::create()
				->withMessage('Cannot create order: product not found');
			}

			$stockValue = $product->getStockValue();
			if ($stockValue >= $quantity) {

				$product->setStockValue($stockValue - $quantity);
				$products[] = $product;
				$price += $quantity * $product->getValue();
			} else {
				throw ServerErrorException::create()
				->withMessage('Cannot create order: insufficient quantity available in stock');
			}
		}

		foreach ($products as $product) {
			$this->save($product);
		}

		return $price;
	}

	public function save(Product $product): void
	{
		$this->em->persist($product);
		$this->em->flush();
	}

	/**
	 * @param OrderProduct[] $products
	 */
	public function returnProducts(array $products): void
	{
		foreach ($products as $product_id => $orderProduct) {

			$product = $this->em->getRepository(Product::class)->findOneBy(['id' => $product_id]);
			if (!$product) {
				throw ServerErrorException::create()
				 ->withMessage('Cannot change order status: product not found');
			}

			$product->setStockValue($product->getStockValue() + $orderProduct->getQuantity());

			$this->save($product);
		}
	}

	public function update(UpdateProductReqDto $dto): Product
	{
		$product = $this->em->getRepository(Product::class)->findOneBy(['id' => $dto->id]);
		if (!$product) {
			throw ServerErrorException::create()
				->withMessage('Cannot update product: product not found');
		}

		$product->setName($dto->name ?? $product->getName());
		$product->setStockDesignation($dto->stockDesignation ?? $product->getStockDesignation());
		$product->setValue($dto->value ?? $product->getValue());
		$product->setStockValue($dto->stockValue ?? $product->getStockValue());

		$this->em->persist($product);
		$this->em->flush($product);

		return $product;
	}

	public function delete(DeleteProductReqDto $dto): void
	{
		$product = $this->em->getRepository(Product::class)->findOneBy(['id' => $dto->id]);
		$this->em->remove($product);
		$this->em->flush($product);
	}

}
