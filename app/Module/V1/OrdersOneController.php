<?php declare(strict_types = 1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use App\Domain\Api\Facade\CustomersFacade;
use App\Domain\Api\Facade\OrdersFacade;
use App\Domain\Api\Facade\ProductsFacade;
use App\Domain\Api\Response\FullOrderResDto;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;
use App\Model\Utils\Caster;
use Nette\Http\IResponse;

/**
 * @Apitte\Path("/orders")
 * @Apitte\Tag("Orders")
 */
class OrdersOneController extends BaseV1Controller
{

	private OrdersFacade $ordersFacade;

	private ProductsFacade $productsFacade;

	private CustomersFacade $customersFacade;

	public function __construct(
		OrdersFacade $ordersFacade,
		ProductsFacade $productsFacade,
		CustomersFacade $customersFacade
	)
	{
		$this->ordersFacade = $ordersFacade;
		$this->productsFacade = $productsFacade;
		$this->customersFacade = $customersFacade;
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Get order by id.
	 * ")
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 * @Apitte\RequestParameter(name="id", in="path", type="int", description="Order ID")
	 * })
	 */
	public function byId(ApiRequest $request): FullOrderResDto
	{
		try {
			$order = $this->ordersFacade->findOne(Caster::toInt($request->getParameter('id')));
			$products = $this->productsFacade->findBy(['id' => array_keys($order->products)]);
			$customer = $this->customersFacade->findOne($order->customerId);

			return FullOrderResDto::from($order, $products, $customer);
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Order not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

}
