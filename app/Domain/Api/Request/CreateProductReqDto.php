<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CreateProductReqDto
{

	/** @Assert\NotBlank */
	#[NotBlank(message: 'name is required.')]
	public string $name;

	/** @Assert\NotBlank */
	#[NotBlank(message: 'value is required.')]
	public float $value;

	/** @Assert\NotBlank */
	#[NotBlank(message: 'stockDesignation is required.')]
	public string $stockDesignation;

	/** @Assert\NotBlank */
	#[NotBlank(message: 'stockValue is required.')]
	public int $stockValue;

}
