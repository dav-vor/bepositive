<?php declare(strict_types = 1);

namespace App\Domain\Api\Facade;

use Apitte\Core\Exception\Api\ServerErrorException;
use App\Domain\Api\Request\CreateCustomerReqDto;
use App\Domain\Api\Request\DeleteCustomerReqDto;
use App\Domain\Api\Request\UpdateCustomerReqDto;
use App\Domain\Api\Response\CustomerResDto;
use App\Domain\Customer\Customer;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class CustomersFacade
{

	public function __construct(private EntityManagerDecorator $em)
	{
	}

	/**
	 * @param  mixed[]  $criteria
	 * @param  string[] $orderBy
	 * @return CustomerResDto[]
	 */
	public function findBy(array $criteria = [], array $orderBy = ['id' => 'ASC'], int $limit = 10, int $offset = 0): array
	{
		$entities = $this->em->getRepository(Customer::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = CustomerResDto::from($entity);
		}

		return $result;
	}

	/**
	 * @return CustomerResDto[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[]  $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): CustomerResDto
	{
		$entity = $this->em->getRepository(Customer::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return CustomerResDto::from($entity);
	}

	public function findOne(int $id): CustomerResDto
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function create(CreateCustomerReqDto $dto): Customer
	{
		$customer = new Customer(
			$dto->firstName,
			$dto->lastName,
			$dto->email,
			$dto->telephone,
		);

		$this->em->persist($customer);
		$this->em->flush($customer);

		return $customer;
	}

	public function update(UpdateCustomerReqDto $dto): Customer
	{
		$customer = $this->em->getRepository(Customer::class)->findOneBy(['id' => $dto->id]);

		if (!$customer) {
			throw ServerErrorException::create()
				->withMessage('Cannot update product: product not found');
		}

		$customer->setFirstName($dto->firstName ?? $customer->getFirstName());
		$customer->setLastName($dto->lastName ?? $customer->getLastName());
		$customer->setEmail($dto->email ?? $customer->getEmail());
		$customer->setTelephone($dto->telephone ?? $customer->getTelephone());

		$this->em->persist($customer);
		$this->em->flush($customer);

		return $customer;
	}

	public function delete(DeleteCustomerReqDto $dto): void
	{
		$customer = $this->em->getRepository(Customer::class)->findOneBy(['id' => $dto->id]);
		$this->em->remove($customer);
		$this->em->flush($customer);
	}

}
