<?php



namespace App\BackendModule\Presenters;
use App\Backend\Model\ArticleManager;
use App\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;


/**
 * Presenter pro articles.
 * @package App\Backend\Presenters
 */
class ArticlePresenter extends BasePresenter
{


	private $articleManager;


	public function __construct( ArticleManager $articleManager)
	{
		parent::__construct();

		$this->articleManager = $articleManager;
	}


	public function renderDefault($name = null)
	{
		if (!$name) $name = $this->redirect('Administration:'); // Pokud není zadané jmeno, vezme výchozí akce.

		if (!($article = $this->articleManager->getArticle($name)))

			$this->error(); //vyhazuje chybu
            $this->template->article = $article; // Předá akci do šablony.
	}

	public function renderList()
	{
		$this->template->articles = $this->articleManager->getArticles();
	}


	public function actionRemove($name = null)
	{
		$this->articleManager->removeArticle($name);
		$this->flashMessage('Akce byla úspěšně odstraněna.');
		$this->redirect('Article:list');
	}


    public function actionAdd(){

            $this['editForm']['img']->setRequired(true);

    }
	public function actionEdit($name = null)
	{
		if ($name) {
			if (!($article = $this->articleManager->getArticle($name)))
				$this->flashMessage('Akce nebyla nalezena.'); // Výpis chybové hlášky.
			else $this['editForm']->setDefaults($article);
		}
	}




	protected function createComponentEditForm()
	{
		// Vytvoření formuláře a definice jeho polí.
        $form = new Form;
        $form->addHidden('id');
        $form->addText('name', 'Název akce: ')->setRequired();
        $form->addUpload('img', 'Obrázek:')->setRequired(false)
            ->addRule(Form::IMAGE, 'Avatar musí být JPEG, PNG nebo GIF.')
            ->addRule(Form::MAX_FILE_SIZE, 'Max size of file is 4 mB.', 4 * 1024 * 1024);
        $form->addText('date', 'Datum akce:')->setRequired()
        ->setAttribute('placeholder', '31.12.2000')
        ->setType('Date')
        ->setDefaultValue(date_format(new \DateTime(), 'd.m.Y'));

        $form->addText('time', 'Čas:')->setRequired()
            ->setType('Time')
            ->setDefaultValue(date_format(new \DateTime(), 'H.i'));

        $form->addTextArea('content', 'Obsah: ')
            ->addRule(Form::MIN_LENGTH, 'Zpráva musí být minimálně %d znaků dlouhá.', 30)
            ->setRequired();
        $form->addText('price', 'Cena: ')->setRequired()
            ->addRule(Form::INTEGER);
        $form->addInteger('count', 'Počet:')
            ->setDefaultValue(1)
            ->addRule($form::RANGE, 'Úroveň musí být v rozsahu mezi %d a %d.', [0, 100]);

        //$form->addText('count', 'Počet vstupenek: ')->setRequired()
          //  ->addRule(Form::INTEGER);
        $form->addSubmit('save', 'Uložit akci');

		$form->onSuccess[] = function (Form $form, ArrayHash $values) {
            $values = $form->getValues();
            try {
                $path = "images/actions/" . $values->img->getName();
                $values->img->move($path);


                $this->articleManager->saveArticle($values);
                $this->flashMessage('Akce byla úspěšně uložena.');
                $this->redirect('Article:', $values->name);
            } catch (UniqueConstraintViolationException $e) {
                $this->flashMessage('Akce s tímto názvem již existuje.');
            }


		};
        return $form;
	}





}
