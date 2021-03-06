<?php

/**
 * Database handler - for access and management
 * @author Vince Urag
 */
class DatabaseHandler {

    /**
     * connection object
     */
    private $connection;
    private $last_id;

    function __construct()
    {

    }

    /**
     * Connect to the database
     * @return connection
     */
    public function newConnection() {
        $database = Core::getDbData();
        $this->connection = new mysqli($database['host'], $database['user'], $database['password'], $database['database']);
        if(mysqli_connect_errno()) {
            trigger_error("Error connecting to the database: ".$this->connection->error, E_USER_ERROR);
        }
        return $this->connection;
    }

    /**
     * Close the active connection
     */
    public function closeConnection() {
        $this->connection->close();
    }

    /**
     * Execute the sql query
     */
    public function exec($query) {

        if ($result = $this->connection->query($query)) {
            $this->last_id = $result;
        } else {
            trigger_error('Error executing query: '.$this->connection->error, E_USER_ERROR);
        }

        if(!is_bool($result)) {
            while($row = $result->fetch_assoc()) {
                $resultSet[] = $row;
            }
            return $resultSet;
        } else {
            return $result;
        }
    }

    /**
     * Update records in the database
     * @param String the table
     * @param array of changes field => value
     * @param String the condition
     * @return bool
     */
    public function update_record( $table, $changes, $condition )
    {
        $update = "UPDATE " . $table . " SET ";
        foreach( $changes as $field => $value )
        {
            $update .= "`" . $field . "`='{$value}',";
        }

        // remove our trailing ,
        $update = substr($update, 0, -1);
        if( $condition != '' )
        {
            $update .= "WHERE " . $condition;
        }

        $query = $this->connection->query($update);
        if($query) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Gets the number of affected rows from the previous query
     * @return int the number of affected rows
     */
    public function affectedRows()
    {
        return $this->connection->affected_rows;
    }

    public function test(){
        echo "test";
    }

}
