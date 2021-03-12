<?php



    namespace App\Presenters;

    use App\Forms\FormFactory;
    use Nette\Application\AbortException;
    use Nette\Application\UI\Presenter;

    /**
     * Základní presenter pro všechny ostatní presentery aplikace.
     * @package App\Presenters
     */
    abstract class BasePresenter extends Presenter
    {
        /** @var FormFactory Továrna na formuláře. */
        protected $formFactory;

        /**
         * Získává továrnu na formuláře pomocí DI.
         * @param FormFactory $formFactory automaticky injektovaná továrna na formuláře
         */
        public final function injectFormFactory(FormFactory $formFactory)
        {
            $this->formFactory = $formFactory;
        }

        /**
         * Na začátku každé akce u všech presenterů zkontroluje uživatelská oprávnění k této akci.
         * @throws AbortException Jestliže uživatel nemá oprávnění k dané akci a bude přesměrován.
         */
        protected function startup()
        {
            parent::startup();

            /*
            if (!$this->getUser()->isAllowed($this->getName(), $this->getAction())) {
                $this->flashMessage('Nejsi přihlášený nebo nemáš dostatečná oprávnění.');
                $this->redirect(':Backend:Administration:login');
            }
            */
        }


    }
