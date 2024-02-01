<?php declare(strict_types = 1);

namespace App\Domain\Customer;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CustomerRepository")
 * @ORM\Table(name="`customer`")
 * @ORM\HasLifecycleCallbacks
 */
class Customer extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $firstName;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $lastName;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE) */
	private string $email;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $telephone;

	public function __construct(string $firstName, string $lastName, string $email, string $telephone)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->telephone = $telephone;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getTelephone(): string
	{
		return $this->telephone;
	}

	public function getFullname(): string
	{
		return $this->firstName . ' ' . $this->lastName;
	}

	public function rename(string $firstName, string $lastName): void
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}

	public function setFirstName(string $value): void
	{
		$this->firstName = $value;
	}

	public function setLastName(string $value): void
	{
		$this->lastName = $value;
	}

	public function setEmail(string $value): void
	{
		$this->email = $value;
	}

	public function setTelephone(string $value): void
	{
		$this->telephone = $value;
	}

}
