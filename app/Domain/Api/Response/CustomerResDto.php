<?php declare(strict_types = 1);

namespace App\Domain\Api\Response;

use App\Domain\Customer\Customer;

final class CustomerResDto
{

	public int $id;

	public string $email;

	public string $firstName;

	public string $lastName;

	public string $fullName;

	public static function from(Customer $customer): self
	{
		$self = new self();
		$self->id = $customer->getId();
		$self->email = $customer->getEmail();
		$self->firstName = $customer->getFirstName();
		$self->lastName = $customer->getLastName();
		$self->fullName = $customer->getFullname();

		return $self;
	}

}
