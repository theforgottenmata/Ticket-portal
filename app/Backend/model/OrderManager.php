<?php



    namespace App\Backend\Model;

    use App\Model\DatabaseManager;



    class OrderManager extends DatabaseManager
    {
        const
            TABLE_NAME = 'order',
            COLUMN_ID = 'id',
            COLUMN_NAME = 'name',
            COLUMN_DATE = 'date';


        public function getOrders()
        {
            return $this->database->table(self::TABLE_NAME)->order(self::COLUMN_DATE . ' ASC');
        }



    }
