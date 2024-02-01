<?php declare(strict_types = 1);

namespace Database\Fixtures;

use App\Domain\Customer\Customer;
use App\Domain\Customer\Product;
use App\Model\Fixtures\ReflectionLoader;
use Database\Fixtures\User;
use Doctrine\Persistence\ObjectManager;

class CustomerFixture extends AbstractFixture
{

	/** @var ObjectManager */
	private $manager;

	public function getOrder(): int
	{
		return 1;
	}

	public function load(ObjectManager $manager): void
	{
		$this->manager = $manager;

		#foreach ($this->getStaticUsers() as $customer) {
		#	$this->saveUser($customer);
		#}

		foreach ($this->getRandomCustomers() as $customer) {
			$this->manager->persist($customer);
		}

		$this->manager->flush();
	}

	/**
	 * @return mixed[]
	 */
	protected function getStaticUsers(): iterable
	{
		yield ['id'=> 1, 'email' => 'admin@admin.cz', 'firstName' => 'Apitte', 'lastName' => 'Admin', 'telephone' => '123456789'];
	}

	/**
	 * @return Customer[]
	 */
	protected function getRandomCustomers(): iterable
	{
		$loader = new ReflectionLoader();
		$objectSet = $loader->loadData([
			Customer::class => [
				'customer{1..1}' => [
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
	 * @param mixed[] $customer
	 */
	protected function saveCustomer(array $customer): void
	{
		$entity = new Customer(
			$customer['firstName'],
			$customer['surName'],
			$customer['email'],
			$customer['telephone'],
		);

		$this->manager->persist($entity);
	}

}
