<?php declare(strict_types = 1);

namespace App\Domain\Product;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ProductRepository")
 * @ORM\Table(name="`product`")
 * @ORM\HasLifecycleCallbacks
 */
class Product extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $name;

	/** @ORM\Column(type="float", length=255, nullable=FALSE, unique=false) */
	private float $value;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $stockDesignation;

	/** @ORM\Column(type="integer", length=255, nullable=FALSE, unique=false) */
	private int $stockValue;

	public function __construct(string $name, float $value, string $stockDesignation, int $stockValue)
	{
		$this->name = $name;
		$this->value = $value;
		$this->stockDesignation = $stockDesignation;
		$this->stockValue = $stockValue;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getValue(): float
	{
		return $this->value;
	}

	public function getStockValue(): int
	{
		return $this->stockValue;
	}

	public function getStockDesignation(): string
	{
		return $this->stockDesignation;
	}

	public function setStockValue(int $stockValue): void
	{
		$this->stockValue = $stockValue;
	}

	public function setName(string $value): void
	{
		$this->name = $value;
	}

	public function setStockDesignation(string $value): void
	{
		$this->stockDesignation = $value;
	}

	public function setValue(float $value): void
	{
		$this->value = $value;
	}

}
