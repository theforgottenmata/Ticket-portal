<?php

    namespace App\Presenters;

    use Nette;
    use Nette\Utils\DateTime;


    class HomepagePresenter extends Nette\Application\UI\Presenter
    {
        /** @var Nette\Database\Context */
        private $database;

        public function __construct ( Nette\Database\Context $database)
        {
            $this->database = $database;
        }


        public function renderDefault()
        {
            $today = new DateTime();

            $this->template->actions = $this->database->table('article')->where('date > ?', $today)->order('date' . ' ASC');

        }

    }