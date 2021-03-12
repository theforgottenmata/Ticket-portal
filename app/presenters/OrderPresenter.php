<?php

    namespace App\Presenters;


    use App\Backend\Model\ArticleManager;
    use Latte\Strict;
    use Latte\Engine;
    use App\Model\OrderManager;
    use Nette\Mail\IMailer;
    use Nette\Mail\SendException;
    use Nette\Application\UI\Form;
    use Nette\Mail\Message;


    use Nette\Utils\ArrayHash;

    class OrderPresenter extends BasePresenter
    {

        private $contactEmail;

        private $mailer;


        private $orderManager;






        public function __construct($contactEmail, IMailer $mailer, OrderManager $orderManager)
        {
            parent::__construct();

            $this->orderManager= $orderManager;
            $this->contactEmail = $contactEmail;
            $this->mailer = $mailer;

        }

        public function renderDefault($name = null)
        {
            // Pokusí se načíst akci s danym url a pokud nebude nalezen vyhodí chybu 404.
            if (!($article = $this->orderManager->getCart($name)))
                $this->error(); // Vyhazuje výjimku BadRequestException.  // Pokud není zadaná nazev akce, vezme se jmeno výchozího článku.


            $this->template->order = $this->orderManager->getCart($name); // Předá akci do šablony.
        }

        public function renderList()
        {


            $this->template->orders = $this->orderManager->getCarts();
        }


        protected function createComponentOrderForm()
        {


            $form = $this->formFactory->create();




            $countries = [
                'CZ' => 'Česká Republika',
                'SK' => 'Slovensko',
                'GB' => 'Velká Británie',


            ];



            $form->addText('id');

            // Vytvoření formuláře a definice jeho polí.
            $form->addHidden('order_article');
            $form->addHidden('price');


            // ->setDefaults( return $this->database->query('SELECT id FROM article WHERE _id = ?', $id););

            $form->addText('name', 'Jméno: ')->setRequired('Vyplňte jméno');
            $form->addText('surname', 'Příjmení: ')->setRequired('Vyplnte příjmení');
            $form->addEmail('mail', 'E-mail:')->setRequired('Vyplňte email');
            $form->addInteger('phone', 'Tel.číslo: 608777608 ')
                ->addRule(Form::INTEGER, 'Tel.číslo musí být číslo')
                ->addRule(Form::MIN_LENGTH, 'Tel. číslo musí být minimálně %d znaků dlouhá.', 9)
                ->addRule(Form::MAX_LENGTH, 'Tel. číslo musí být maximálně %d znaků dlouhá.', 9)
                ->setRequired('Vyplňte tel. číslo');
            $form->addText('address', 'Adresa: ')->setRequired('Vyplňte adresu');
            $form->addText('city', 'Město: ')->setRequired('Vyplňte město');
            $form->addText('zip_code', 'PSČ: ')->setRequired('Vyplňte PSČ');
            $form->addSelect('country', 'Země:', $countries)
                ->setPrompt('--- Zvolte zemi* ---')->setRequired('Zvolte zemi');
            $form->addTextArea('message', 'Poznámka: ');
            $form->addHidden('date')

                ->setDefaultValue(date_format(new \DateTime(), 'd.m.Y H:i'));



            $form->addSubmit('submit', 'Odeslat platbu');


            // Funkce se vykonaná při úspěšném odeslání formuláře a zpracuje zadané hodnoty.
            $form->onSuccess[] = function (Form $form) {

                $values = $form->getValues();

                $latte = new Engine;

                $info = [
                    'id' => $values->id,
                    'name' => $values->name,
                    'surname' => $values->surname,
                    'price' => $values->price
                ];
                try {
                    $this->orderManager->saveCart($values);


                    $mail = new Message;
                    $mail->setFrom($this->contactEmail)
                        ->addTo($values->mail)
                        ->setHtmlBody($latte ->renderToString(__DIR__ . '/../templates/Order/email.latte', $info));

                    $this->mailer->send($mail);

                    $mailMe = new Message;
                    $mailMe->setFrom($values->mail)
                        ->addTo($this->contactEmail)
                        ->setSubject('Tickets')
                        ->setBody("Někdo si objednal vstupenky");
                    $this->mailer->send($mail);

                    $this->flashMessage('Objednávka byla úspěšně odeslána');
                    $this->redirect('Homepage:default');
                } catch (SendException $e) {
                    $form->addError('Něco se nepovedlo');

                }



            };



            return $form;
        }








    }

