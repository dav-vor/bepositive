<?php declare(strict_types = 1);

namespace App\Domain\Api\Facade;

use Apitte\Core\Exception\Api\MessageException;
use Apitte\Core\Exception\Api\ServerErrorException;
use App\Domain\Api\Request\CreateOrderReqDto;
use App\Domain\Api\Response\OrderResDto;
use App\Domain\Customer\Customer;
use App\Domain\Order\Order;
use App\Domain\Order\OrderProduct;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;
use Symfony\Component\Serializer\Serializer;

final class OrdersFacade
{

	public function __construct(private EntityManagerDecorator $em, private Serializer $serializer)
	{
	}

	/**
	 * @param  mixed[]  $criteria
	 * @param  string[] $orderBy
	 * @return OrderResDto[]
	 */
	public function findBy(array $criteria = [], array $orderBy = ['id' => 'ASC'], int $limit = 10, int $offset = 0): array
	{
		$entities = $this->em->getRepository(Order::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$products = $this->deserializeProducts($entity->getProducts());

			$result[] = OrderResDto::from($entity, $products);
		}

		return $result;
	}

	/**
	 * @return OrderResDto[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[]  $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): OrderResDto
	{
		$entity = $this->em->getRepository(Order::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		$products = $this->deserializeProducts($entity->getProducts());

		return OrderResDto::from($entity, $products);
	}

	public function findOne(int $id): OrderResDto
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function create(CreateOrderReqDto $dto): Order
	{
		$products = $this->serializer->serialize($dto->products, 'json');

		$order = new Order(
			$dto->customerId,
			$products,
			$dto->orderState,
			$dto->price,
		);

		$this->em->persist($order);
		$this->em->flush($order);

		return $order;
	}

	public function changeState(int $order, int $state): Order
	{
		if (!in_array($state, Order::STATES, true)) {
			throw ServerErrorException::create()
			->withMessage('Cannot change state: unknown state');
		}

		$order = $this->em->getRepository(Order::class)->findOneBy(['id' => $order]);
		if ($order->getOrderState() === Order::STATE_CANCELLED) {
			throw MessageException::create()
			->withMessage('Cannot change state: the order is already cancelled');
		}

		if ($order->getOrderState() === $state) {
			throw MessageException::create()
			->withMessage('Cannot change state: the order is already in this state');
		}

		$order->setOrderState($state);

		$this->em->persist($order);
		$this->em->flush($order);

		return $order;
	}

	/**
	 * @return OrderResDto[]
	 */
	public function findOrdersByCustomerEmail(string $email): array
	{
		$customer = $this->em->getRepository(Customer::class)->findOneBy(['email' => $email]);

		return $this->findBy(['customerId' => $customer->getId()]);
	}

	/**
	 * @return OrderProduct[]
	 */
	public function deserializeProducts(string $jsonData): array
	{
		$data = json_decode($jsonData, true);

		if ($data !== null) {
			$orderProducts = [];
			foreach ($data as $item) {
				$orderProducts[] = new OrderProduct($item['product_id'], $item['quantity']);
			}
		} else {
			throw MessageException::create()
			->withMessage('Cannot get order products');
		}

		return $orderProducts;
	}

}
