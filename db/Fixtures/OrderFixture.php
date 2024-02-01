<?php declare(strict_types = 1);

namespace Database\Fixtures;

use App\Domain\Order\Order;
use Doctrine\Persistence\ObjectManager;

class OrderFixture extends AbstractFixture
{

	/** @var ObjectManager */
	private $manager;

	public function __construct()
	{
	}

	public function getOrder(): int
	{
		return 2;
	}

	public function load(ObjectManager $manager): void
	{
		$this->manager = $manager;

		foreach ($this->getStaticOrders() as $order) {
			$this->saveOrder($order);
		}

		$this->manager->flush();
	}

	/**
	 * @return mixed[]
	 */
	protected function getStaticOrders(): iterable
	{
		yield ['customerId' => 1, 'products' => '[{"product_id":1,"quantity":1},{"product_id":2,"quantity":2}]', 'orderState' => Order::STATE_PENDING, 'price' => 119.97];
	}

	/**
	 * @param mixed[] $order
	 */
	protected function saveOrder(array $order): void
	{
		$entity = new Order(
			$order['customerId'],
			$order['products'],
			$order['orderState'],
			$order['price']
		);

		$this->manager->persist($entity);
	}
}
