<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CreateCustomerReqDto
{

	/** @Assert\NotBlank */
	/** @Assert\Email */
	#[NotBlank(message: 'email is required.')]
	#[Email]
	public string $email;

	/** @Assert\NotBlank */
	#[NotBlank(message: 'firstName is required.')]
	public string $firstName;

	/** @Assert\NotBlank */
	#[NotBlank(message: 'lastName is required.')]
	public string $lastName;

	/** @Assert\NotBlank */
	#[NotBlank(message: 'telephone is required.')]
	public string $telephone;

}
