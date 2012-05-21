<?php
/** Contains payment management model
 * @author Janis Peisenieks
 * @package Maksajumi
 * @subpackage Default
 */
/**
 * Model for payment magament
 * @author Janis Peisenieks
 * @package Maksajumi
 * @subpackage Default
 */
class Model_Maksajumi extends Zend_Db_Table_Abstract
{
    /**
     * Name of the DB table to use
     * @var string
     */
    public $_name = "maksajumi";
    /**
     * Name of the primary key to use
     * @var string
     */
    public $_primaryKey = "maksajumsId";

    /**
     * Function for new payment creation
     * @param $data array The data to insert
     */
    public function createMaksajums($data)
    {

        $curdate = date("Y-m-d H:i:s");
        $data['maksajumsCreated'] = $curdate;
        //If the payment is completed, set the finish date
        if ($data['maksajumsCompleted']) {
            $data['maksajumsFinished'] = $curdate;
        }

        $this->insert($data);
    }

    /**
     * Function for payment editing
     * @param $data array The data to update
     * @param $id int Id of the payment to update
     */
    public function editMaksajums($data, $id)
    {
        $curdate = date("Y-m-d H:i:s");
        //If the payment is completed, set the finish date
        if ($data['maksajumsCompleted']) {
            $data['maksajumsFinished'] = $curdate;
        }
        $this->update($data, $this->getAdapter()->quoteInto("maksajumsId=?", $id));
    }

    /**
     * Payment retrieval function
     * @param $id int Id of the payment to return
     * @return null|array
     */
    public function getMaksajums($id)
    {
        $return = $this->fetchRow($this->select()->where("maksajumsId=?", $id));
        //If a record has been found, convert it to an array
        if (!is_null($return) && $return !== false) {
            $return = $return->toArray();
        }
        return $return;
    }

    /**
     * Retrieve payments by userId
     * @param $id int The userId for whom to get the payments
     * @return array Array of payments
     */
    public function getMaksajumsByUser($id)
    {
        $sql = $this->getFullQuery();
        return $this->getAdapter()->fetchAll($sql->where("maksajumsUserId=?", $id));
    }

    /**
     * Create a payment for multiple users at once
     * @param array $data Data to interpret
     */
    public function createMultiMaksajums($data)
    {
        //We loop through every key to find the users for whom to create payment
        foreach ($data as $key => $enabled) {
            // If the string contains a user_ prefix, and has been set for creation, create a payment
            if (strpos($key, "user_") !== false && $enabled == 1) {

                $realData = array();
                //Split the string up, to get the user, for whom to create payment
                $split = explode("user_", $key);
                $userId = $split[1];
                //Set up a new array of data
                $realData['maksajumsUserId'] = $userId;
                $realData['maksajumsValue'] = $data['maksajumsValue'];
                $realData['maksajumsTitle'] = $data['maksajumsTitle'];
                $realData['maksajumsCompleted'] = $data['maksajumsCompleted'];
                //Pass the array of creation data to the single payment creation function
                $this->createMaksajums($realData);

            }

        }

    }

    /**
     * Return the balance for all users
     * @return array
     */
    public function getBilanceForAll()
    {
        return $this->getAdapter()->fetchAll($this->bilanceQuery());
    }

    /**
     * Initialize a query, that gathers the balance for every user
     * @return Zend_Db_Select
     */
    private function bilanceQuery()
    {
        //Retrieve the full query, with joins
        $sql = $this->getFullQuery(false);
        /**
         * Sum up the payments.
         *
         * We use simple arithmetic and multiplication to get the balance without complicated if's
         */
        $sql->from(array('m' => $this->_name), new Zend_Db_Expr("*,sum(`maksajumsValue` * ((-1)+(1 *`maksajumsCompleted`))) as balance"));
        //Group the query by userId
        $sql->group("maksajumsUserId");
        return $sql;
    }

    /**
     * Initialize the multi-table query
     * @param bool $withFrom Should the table already contain a from clause
     * @param string $order The way to order the select
     * @return Zend_Db_Select
     */
    private function getFullQuery($withFrom = true, $order = "name asc")
    {
        $sql = $this->getAdapter()->select();
        if ($withFrom) {
            $sql->from(array('m' => $this->_name));
        }
        $userModel = new Model_Users();
        $sql->join(array("u" => $userModel->_name), 'u.userId=m.maksajumsUserId');
        if (!is_null($order)) {
            $sql->order($order);
        }
        return $sql;
    }

    /**
     * Retrieve balance for a single user
     * @param int $id The userId for whome to get the balance
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getBilanceForUser($id)
    {
        return $this->fetchAll($this->select()->where("maksajumsUserId=?", $id));
    }

    /**
     * Retrieve all payments
     * @param null $order Way to order the payments
     * @return array
     */
    public function getMaksajumi($order = null)
    {
        //Retrieve the initialized query
        $sql = $this->getFullQuery(true, $order);
        //Fetch all records
        return $this->getAdapter()->fetchAll($sql);
    }

    /**
     * Change the completion status of a payment
     * @param int $id The paymentId to update
     * @param int $status The status to which to update
     * @return int
     */
    public function changeMaksajums($id, $status)
    {
        $data['maksajumsCompleted'] = $status;
        //If the payment is completed, set the finish date
        if ($status) {
            $data['maksajumsFinished'] = date("Y-m-d H:i:s");
        }
        return $this->update($data, $this->getAdapter()->quoteInto("maksajumsId=?", $id));
    }

    /**
     * Delete a payment
     * @param int $id paymentId to delete
     * @return int
     */
    public function deleteMaksajums($id)
    {
        return $this->delete($this->getAdapter()->quoteInto($this->_primaryKey . "=?", $id));

    }


}
