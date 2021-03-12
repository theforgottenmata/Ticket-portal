<?php



    namespace App\BackendModule\Presenters;
    use App\Presenters\BasePresenter;




    /**
     * Presenter pro akce s errory
     * @package App\Backend\Presenters
     */
    class HelpPresenter extends BasePresenter
    {




        public function renderDefault()
        {

                $this->redirect(':Homepage:error'); // Vyhazuje výjimku BadRequestException.

        }
    }
