<?php declare(strict_types = 1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use App\Domain\Api\Facade\CustomersFacade;
use App\Domain\Api\Response\CustomerResDto;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;
use App\Model\Utils\Caster;
use Nette\Http\IResponse;

/**
 * @Apitte\Path("/customers")
 * @Apitte\Tag("Customers")
 */
class CustomersOneController extends BaseV1Controller
{

	private CustomersFacade $customersFacade;

	public function __construct(CustomersFacade $customersFacade)
	{
		$this->customersFacade = $customersFacade;
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Get customer by email.
	 * ")
	 * @Apitte\Path("/email")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 * @Apitte\RequestParameter(name="email", in="query", type="string", description="Customer e-mail address")
	 * })
	 */
	public function byEmail(ApiRequest $request): CustomerResDto
	{
		try {
			return $this->customersFacade->findOneBy(['email' => $request->getParameter('email')]);
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Customer not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Get customer by id.
	 * ")
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 * @Apitte\RequestParameter(name="id", in="path", type="int", description="Customer ID")
	 * })
	 */
	public function byId(ApiRequest $request): CustomerResDto
	{
		try {
			return $this->customersFacade->findOne(Caster::toInt($request->getParameter('id')));
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Customer not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

}
