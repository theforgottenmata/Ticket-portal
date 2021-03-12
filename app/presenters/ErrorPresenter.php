<?php



namespace App\Presenters;

use Nette\Application\BadRequestException;
use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Application\Responses\CallbackResponse;
use Nette\Application\Responses\ForwardResponse;
use Nette\Http;
use Nette\SmartObject;
use Tracy\ILogger;

/**
 * Presenter pro vlastní zpracování chyb na stránce.
 * @package App\Presenters
 */
class ErrorPresenter implements IPresenter
{
	use SmartObject;

	private $logger;


	public function __construct(ILogger $logger)
	{
		$this->logger = $logger;
	}


	public function run(Request $request)
	{
		// Získání instance výjimky.
		$e = $request->getParameter('exception');

		// Pokud jde o chybu v dotazu vrať jako odpověď přesměrování na vlastní chybovou stránku.
		if ($e instanceof BadRequestException)
			return new ForwardResponse($request->setPresenterName('Backend:Help')->setParameters(['name' => 'chyba']));

		// Jinak se jedná o chybu serveru.
		$this->logger->log($e, ILogger::EXCEPTION); // Loguje výjimku.

		// Vrací jako odpověď chybovou stránku serveru.
		return new CallbackResponse(function (Http\IRequest $httpRequest, Http\IResponse $httpResponse) use($e){
			// Pokud je jako odpověď očekáváno HTML, načti šablonu pro chybovou stránku serveru.
			if (preg_match('#^text/html(?:;|$)#', $httpResponse->getHeader('Content-Type')))
				throw $e;
			    //require __DIR__ . '/../templates/Error/500.phtml';
		});
	}
}
