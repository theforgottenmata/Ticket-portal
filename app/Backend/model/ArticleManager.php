<?php



namespace App\Backend\Model;

use App\Model\DatabaseManager;



class ArticleManager extends DatabaseManager
{
	const
		TABLE_NAME = 'article',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'name',
	    COLUMN_DATE = 'date';


	public function getArticles()
	{
        return $this->database->table(self::TABLE_NAME)->order(self::COLUMN_DATE . ' ASC');
    }


	public function getArticle($name)
	{
		return $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $name)->fetch();
	}

	public function saveArticle($article)//insert & update
	{
		if (empty($article[self::COLUMN_ID])) {
			unset($article[self::COLUMN_ID]);
			$this->database->table(self::TABLE_NAME)->insert($article);//insert
		} else
			$this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $article[self::COLUMN_ID])->update($article);//upload
	}

	public function removeArticle($name)
	{
		$this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $name)->delete();
	}


}
