<?php
/**
 * Created by PhpStorm.
 * User: Bassem
 * E
 * Date: 11/03/2019
 * Time: 14:51
 */

namespace Odoo\ConnectorBundle\Service;

use Odoo\ConnectorBundle\Traits\ripcord;

class OdooService
{
    private $db;
    private $url;
    private $username;
    private $password;


    public function __construct($url, $db, $username, $password)
    {
        $this->url = $url;
        $this->db = $db;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * To verify if the connection information is correct before trying to authenticate
     */
    public function checkAccess()
    {
        $common = ripcord::client($this->url . '/xmlrpc/2/common');
        return $common->version();
    }

    /**
     * returns a list of models
     */
    public function getModel()
    {
        return ripcord::client($this->url . '/xmlrpc/2/object');

    }

    /**
     * returns a user identifier
     */
    public function getUserId()
    {
        $common = ripcord::client($this->url . '/xmlrpc/2/common');
        return $userId = $common->authenticate($this->db, $this->username, $this->password, array());

    }

    /**
     * @param  string $model : the model name
     * @param  array $option : an array of parameters
     * @return list of records
     */

    public function search($model, $option = [])
    {
        $table = null;
        if ($option != null) {
            $table = $option;
        }
        $models = $this->getModel();
        $userId = $this->getUserId();
        $result = $models->execute_kw($this->db, $userId, $this->password,
            $model, 'search_read',
            array($table));
        return $result;
    }

    /**
     * @param  string $model : the model name
     * @param  integer $id : the record identifier
     * @return boolean
     */
    public function delete($model, $id)
    {
        $models = $this->getModel();
        $userId = $this->getUserId();
        $result = $models->execute_kw($this->db, $userId, $this->password, $model, 'unlink',
            array(array($id)));
        return $result;
    }

    /**
     * @param  string $model : the model name
     * @param  integer $id : the record identifier
     * @return integer : database identifier of the record
     */
    public function create($model, $option)
    {
        $models = $this->getModel();
        $userId = $this->getUserId();
        $result = $models->execute_kw($this->db, $userId, $this->password, $model, 'create', array($option));
        return $result;
    }

    /**
     * @param  string $model : the model name
     * @param  integer $id : the record identifier
     * @param  array $option : an array of parameters
     * @return integer : database identifier of the record
     */
    public function update($model, $id, $option)
    {
        $models = $this->getModel();
        $userId = $this->getUserId();
        $id = (int)$id;
        return $result = $models->execute_kw($this->db, $userId, $this->password, $model, 'write',
            array(array($id), $option));

    }
    /**
     * @param  string $model : the model name
     * used to inspect a model’s fields and check which ones seem to be of interest
     */
    public function fields($model)
    {
        $models = $this->getModel();
        $userId = $this->getUserId();
        return $models->execute_kw($this->db, $userId, $this->password, $model, 'fields_get',
            array());
    }


}
