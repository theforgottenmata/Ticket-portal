<?php

    namespace App\Presenters;

    use App\Model\ActionManager;
    use Nette;



    class ActionPresenter extends BasePresenter
    {


        private $actionManager;


        public function __construct( ActionManager $actionManager)
        {

            $this->actionManager= $actionManager;
        }

        public function renderDefault($name = null)
        {
            if (!$name) $name = $this->redirect('Homepage:'); // Pokud není zadaná nazev akce, vezme se jmeno výchozího článku.

            // Pokusí se načíst akci s danou URL a pokud nebude nalezen vyhodí chybu 404.
            if (!($article = $this->actionManager->getList($name)))
                $this->error(); // Vyhazuje výjimku BadRequestException.

            $this->template->actions = $article; //
        }

        public function renderList()
        {
            $this->template->actions = $this->actionManager->getLists();
        }



    }

