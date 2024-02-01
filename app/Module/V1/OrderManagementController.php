<?php declare(strict_types = 1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Facade\OrdersFacade;
use App\Domain\Api\Facade\ProductsFacade;
use App\Domain\Api\Request\ChangeOrderStateReqDto;
use App\Domain\Api\Request\CreateOrderReqDto;
use App\Domain\Order\Order;
use Doctrine\DBAL\Exception\DriverException;
use Nette\Http\IResponse;

/**
 * @Apitte\Path("/orders")
 * @Apitte\Tag("Orders")
 */
class OrderManagementController extends BaseV1Controller
{

	private OrdersFacade $ordersFacade;

	private ProductsFacade $productsFacade;

	public function __construct(
		OrdersFacade $ordersFacade,
		ProductsFacade $productsFacade
	)
	{
		$this->ordersFacade = $ordersFacade;
		$this->productsFacade = $productsFacade;
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Create new order.
	 * ")
	 * @Apitte\Path("/create")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\CreateOrderReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/**
	* @var CreateOrderReqDto $dto
*/
		$dto = $request->getParsedBody();
		$dto->validateProducts();
		try {
			$dto->setPrice($this->productsFacade->processOrderProducts($dto->products));
			$dto->setState(Order::STATE_PENDING);
			$this->ordersFacade->create($dto);

			return $response->withStatus(IResponse::S201_Created)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot create order')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Change order state.
	 * ")
	 * @Apitte\Path("/change_state")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\ChangeOrderStateReqDto")
	 */
	public function changeOrderState(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/**
	* @var ChangeOrderStateReqDto $dto
*/
		$dto = $request->getParsedBody();
		try {
			$order = $this->ordersFacade->changeState($dto->id, $dto->state);
			if ($dto->state === Order::STATE_CANCELLED) {
				$this->productsFacade->returnProducts($order->getProducts());
			}

			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (DriverException $e) {
			throw \Throwable::class
				->withMessage('Cannot change order')
				->withPrevious($e);
		}
	}

}
