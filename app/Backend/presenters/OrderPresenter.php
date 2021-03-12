<?php



    namespace App\BackendModule\Presenters;
    use App\Backend\Model\OrderManager;
    use App\Presenters\BasePresenter;
    use Nette\Application\UI\Form;
    use Nette\Database\UniqueConstraintViolationException;
    use Nette\Utils\ArrayHash;


    /**
     * Presenter pro articles.
     * @package App\Backend\Presenters
     */
    class OrderPresenter extends BasePresenter
    {


        private $orderManager;


        public function __construct( OrderManager $orderManager)
        {
            parent::__construct();

            $this->orderManager = $orderManager;
        }




        public function renderList()
        {
            $this->template->orders = $this->orderManager->getOrders();
        }




    }
