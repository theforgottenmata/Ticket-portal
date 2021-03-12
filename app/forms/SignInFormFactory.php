<?php



namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;

/**
 * Továrna na přihlašovací formulář.
 * @package App\Forms
 */
class SignInFormFactory
{
	use SmartObject;

	private $formFactory;

	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->formFactory = $factory;
		$this->user = $user;
	}


	public function create(callable $onSuccess)
	{
		$form = $this->formFactory->create();
		$form->addText('username', 'Jméno :')
            ->setRequired('Zadejte vaše přihlašovací jméno.');
		$form->addPassword('password', 'Heslo :')
            ->setRequired('Zadejte vaše heslo.');
		$form->addSubmit('login', 'Přihlásit');

		$form->onSuccess[] = function (Form $form, ArrayHash $values) use ($onSuccess) {
			try {
				// Zkusíme se přihlásit.
				$this->user->login($values->username, $values->password);
				$onSuccess($form, $values); // Zavoláme specifickou funkci.
			} catch (AuthenticationException $e) {
				// Přidáme chybovou zprávu alespoň do formuláře.
				$form->addError('Špatné jméno nebo heslo');
			}
		};

		return $form;
	}
}
