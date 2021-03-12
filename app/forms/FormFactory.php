<?php



namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Továrna na vytváření formulářů.
 * @package App\Forms
 */
class FormFactory
{
	use SmartObject;


	public function create()
	{
		$form = new Form;
		// Prostor pro výchozí nastavení.
		return $form;
	}



}


