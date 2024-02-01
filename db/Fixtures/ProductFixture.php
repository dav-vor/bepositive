<?php declare(strict_types = 1);

namespace Database\Fixtures;

use App\Domain\Product\Product;
use App\Model\Fixtures\ReflectionLoader;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends AbstractFixture
{

	/** @var ObjectManager */
	private $manager;

	public function getOrder(): int
	{
		return 3;
	}

	public function load(ObjectManager $manager): void
	{
		$this->manager = $manager;

		foreach ($this->getStaticProducts() as $product) {
			$this->saveProduct($product);
		}

		$this->manager->flush();
	}

	/**
	 * @return mixed[]
	 */
	protected function getStaticProducts(): iterable
	{
		return array(
			array(
				'name' => 'Electric Appliance A',
				'value' => 19.99,
				'stock_designation' => 'Warehouse X',
				'stock_value' => 100,
				'created_at' => '2024-01-21 10:00:00',
				'updated_at' => null,
			),
			array(
				'name' => 'Smartphone Model B',
				'value' => 29.99,
				'stock_designation' => 'Warehouse Y',
				'stock_value' => 150,
				'created_at' => '2024-01-21 10:15:00',
				'updated_at' => null,
			),
			array(
				'name' => 'Home Automation System C',
				'value' => 39.99,
				'stock_designation' => 'Warehouse Z',
				'stock_value' => 200,
				'created_at' => '2024-01-21 10:30:00',
				'updated_at' => null,
			),
			array(
				'name' => 'Electric Blender D',
				'value' => 49.99,
				'stock_designation' => 'Warehouse X',
				'stock_value' => 120,
				'created_at' => '2024-01-21 11:00:00',
				'updated_at' => null,
			),
			array(
				'name' => 'Wireless Headphones E',
				'value' => 59.99,
				'stock_designation' => 'Warehouse Y',
				'stock_value' => 180,
				'created_at' => '2024-01-21 11:15:00',
				'updated_at' => null,
			),
			array(
				'name' => 'Smart Thermostat F',
				'value' => 69.99,
				'stock_designation' => 'Warehouse Z',
				'stock_value' => 220,
				'created_at' => '2024-01-21 11:30:00',
				'updated_at' => null,
			),
			array(
				'name' => 'Robotic Vacuum Cleaner G',
				'value' => 79.99,
				'stock_designation' => 'Warehouse X',
				'stock_value' => 90,
				'created_at' => '2024-01-21 12:00:00',
				'updated_at' => null,
			),
			array(
				'name' => 'High-Performance Laptop H',
				'value' => 89.99,
				'stock_designation' => 'Warehouse Y',
				'stock_value' => 130,
				'created_at' => '2024-01-21 12:15:00',
				'updated_at' => null,
			),
			array(
				'name' => 'Electric Toothbrush I',
				'value' => 99.99,
				'stock_designation' => 'Warehouse Z',
				'stock_value' => 170,
				'created_at' => '2024-01-21 12:30:00',
				'updated_at' => null,
			),
			array(
				'name' => 'Gaming Console J',
				'value' => 109.99,
				'stock_designation' => 'Warehouse X',
				'stock_value' => 200,
				'created_at' => '2024-01-21 13:00:00',
				'updated_at' => null,
			),
		);
	}

	/**
	 * @return Product[]
	 */
	protected function getRandomCustomers(): iterable
	{
		$loader = new ReflectionLoader();
		$objectSet = $loader->loadData([
			\App\Domain\Product\Product::class => [
				'product{1..1}' => [
					'__construct' => [
						'<firstName()>',
						'<lastName()>',
						'<email()>',
						'<phoneNumber()>'
					],
					'id' => '<(intval(strval($current)))>',
				],
			],
		]);

		return $objectSet->getObjects();
	}

	/**
	 * @param mixed[] $product
	 */
	protected function saveProduct(array $product): void
	{
		$entity = new Product(
			$product['name'],
			$product['value'],
			$product['stock_designation'],
			$product['stock_value'],
		);

		$this->manager->persist($entity);
	}

}
