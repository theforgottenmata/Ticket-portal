<?php



namespace App\BackendModule\Presenters;

use App\Forms\SignInFormFactory;
use App\Forms\SignUpFormFactory;
use App\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;


class AdministrationPresenter extends BasePresenter
{
	private $signInFactory;

	private $signUpFactory;


	public function __construct(SignInFormFactory $signInFactory, SignUpFormFactory $signUpFactory)
	{
		parent::__construct();
		$this->signInFactory = $signInFactory;
		$this->signUpFactory = $signUpFactory;
	}

	public function actionLogin()
	{
		if ($this->user->isLoggedIn()) $this->redirect('Administration:');
	}


	public function actionLogout()
	{
		$this->user->logout();
		$this->redirect('login');
	}

	/** Předá jméno přihlášeného uživatele do šablony administrační stránky. */
	public function renderDefault()
    {
        if ($this->user->isLoggedIn()) $this->template->username = $this->user->identity->username;

    }
	protected function createComponentLoginForm()
	{
		return $this->signInFactory->create(function () {
			$this->flashMessage('Úspěšné přihlášení');
			$this->redirect('Administration:');
		});
	}

	protected function createComponentRegisterForm()
	{
		return $this->signUpFactory->create(function (Form $form, ArrayHash $values) {
			$this->flashMessage('Byl jste úspěšně zaregistrován.');
			$this->user->login($values->username, $values->password); // login
			$this->redirect('Administration:');
		});
	}
}
