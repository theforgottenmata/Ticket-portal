<?php



    namespace App\Model;
    use Nette\Utils\DateTime;
    use Nette\Database\SqlLiteral;


    use Nette\Database\Table\ActiveRow;

    /**
     * Model pro správu objednávek v redakčním systému.
     * @package App\Backend\Model
     */
    class OrderManager extends DatabaseManager
    {
        /** Konstanty pro práci s databází. */
        const
            TABLE_NAME = 'article',
            COLUMN_ID = 'id',
            COLUMN_NAME = 'name',
            COLUMN_DATE = 'date',
            SAVE_TABLE_NAME = 'order',
            SAVE_COLUMN_ID = 'id';



        public function getCarts()
        {
            $today = new DateTime();
            return $this->database->table(self::TABLE_NAME)->where('date > ?', $today)->order(self::COLUMN_DATE . ' ASC');
        }



        public function getCart($name)
        {
            return $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $name)->fetch();
        }

        public function saveCart($order)
        {
                unset($order[self::SAVE_COLUMN_ID]);
                $this->database->table(self::SAVE_TABLE_NAME)->insert($order);//insert
            $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $order->order_article)->update(array('count' => new SqlLiteral('count - 1')));

        }




    }


