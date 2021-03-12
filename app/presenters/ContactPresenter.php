<?php


    namespace App\Presenters;

    use Nette\Application\UI\Form;
    use Nette\Mail\IMailer;
    use Nette\Mail\Message;
    use Nette\Mail\SendException;
    use Nette\Utils\ArrayHash;

    /**
     * Presenter pro kontaktní formulář.
'     */
    class ContactPresenter extends BasePresenter
    {
        private $contactEmail;

        private $mailer;

        public function __construct($contactEmail, IMailer $mailer)
        {
            parent::__construct();
            $this->contactEmail = $contactEmail;
            $this->mailer = $mailer;
        }


        protected function createComponentContactForm(): Form{
            $form = $this->formFactory->create();
            $countries = [
                'Chyba' =>'Chyba na webu',
                'Dostupnost' =>'Dostupnost akce',
                'Dotaz' =>'Dotaz',
                'Jine' =>'Jiné..',
            ];
            $form->addEmail('email')->setRequired('Vyplňte email');
            $form->addText('name')->setRequired('Vyplňte jméno');
            $form->addSelect('reason', 'Select',  $countries)
                ->setPrompt('--- Vyberte důvod ---')->setRequired('Zvolte důvod');
            $form->addTextArea('message')
                ->setRequired('Vyplňte zprávu');


            $form->addSubmit('send');

            $form->onSuccess[] = function (Form $form, ArrayHash $values) {
                try {
                    $mail = new Message;
                    $mail->setFrom($values->email)
                         ->addTo($this->contactEmail)
                         ->setSubject($values->reason)
                         ->setBody($values->message);
                    $this->mailer->send($mail);
                    $this->flashMessage('Email byl úspěšně odeslán.');
                    $this->redirect('Homepage:');
                } catch (SendException $e) {
                    $this->flashMessage('Email se nepodařilo odeslat.');
                }
            };
            return $form;
        }
    }
