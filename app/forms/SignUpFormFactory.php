<?php



namespace App\Forms;

use App\Model\DuplicateNameException;
use App\Model\UserManager;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;

/**
 * Továrna na registrační formulář.
 * @package App\Forms
 */
class SignUpFormFactory
{
	use SmartObject;

	private $formFactory;

	private $userManager;


	public function __construct(FormFactory $factory, UserManager $userManager)
	{
		$this->formFactory = $factory;
		$this->userManager = $userManager;
	}

	public function create(callable $onSuccess)
	{
		$form = $this->formFactory->create();
		$form->addText('username', 'Jméno: ')->setRequired();
		$form->addPassword('password', 'Heslo: ')->setRequired();
		$form->addSubmit('register', 'Registrovat');

		$form->onSuccess[] = function (Form $form, ArrayHash $values) use ($onSuccess) {
			try {
				// Zkusíme zaregistrovat nového uživatele.
				$this->userManager->register($values->username, $values->password);
				$onSuccess($form, $values); // Zavoláme specifickou funkci.
			} catch (DuplicateNameException $e) {
				// Přidáme chybovou zprávu alespoň do formuláře.
				$form['username']->addError($e->getMessage());
			}
		};

		return $form;
	}
}
