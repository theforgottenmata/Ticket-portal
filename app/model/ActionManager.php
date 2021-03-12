<?php



    namespace App\Model;
    use Nette\Utils\DateTime;



    use Nette\Database\Table\ActiveRow;
    use Nette\Utils\ArrayHash;

    /**
     * Model pro správu akcí v redakčním systému.
     * @package App\Backend\Model
     */
    class ActionManager extends DatabaseManager
    {
        const
            TABLE_NAME = 'article',
            COLUMN_ID = 'id',
            COLUMN_NAME = 'name',
            COLUMN_DATE = 'date';



        public function getLists()
        {
            $today = new DateTime();
            return $this->database->table(self::TABLE_NAME)->where('date > ?', $today)->order(self::COLUMN_DATE . ' ASC');

        }


        public function getList($name)
        {
            return $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $name)->fetch();
        }

        public function saveList($action)
        {
            if (empty($action[self::COLUMN_ID])) {
                unset($action[self::COLUMN_ID]);
                $this->database->table(self::TABLE_NAME)->insert($action);//insert
            } else
                $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $action[self::COLUMN_NAME])->update($action);//upload
        }





    }
