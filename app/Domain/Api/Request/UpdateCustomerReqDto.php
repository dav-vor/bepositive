<?php declare(strict_types = 1);

namespace App\Domain\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class UpdateCustomerReqDto
{

	/** @Assert\NotBlank() */
	#[NotBlank(message: 'id is required.')]
	public int $id;

	/** @Assert\Email */
	#[Email(message: 'The email {{ value }} is not a valid email.')]
	public string $email;

	public string $firstName;

	public string $lastName;

	public string $telephone;

}
