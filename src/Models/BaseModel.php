<?php

namespace App\Models;

use App\Components\Database;

/**
 * Base model
 */
class BaseModel
{
    /**
     * Request execution error flag
     * @var bool
     */
    public $isError = false;

    /**
     * Name of the table in the database
     * @var string
     */
    protected $table;

    /**
     * Error data
     * @var array
     */
    private $errors = [];

    /**
     * Database connection
     * @var object
     */
    private $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getDataBase();
    }

    /**
     * Returns error data
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Prepares and executes the request
     * @param string $sql - request text
     * @param array $params - request parameters
     * @return object
     */
    protected function execQuery(string $sql, array $params = [])
    {
        try {
            $prepQuery = $this->db->prepare($sql);
            $response = $prepQuery->execute($params);

            if (empty($response)) {
                $this->isError = true;
                $this->errors[] = $prepQuery->errorInfo()[0];
            }

            return $prepQuery;
        } catch (\Throwable $th) {
            $this->isError = true;
            $this->errors[] = $th->getMessage();
        }
    }
}
