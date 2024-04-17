<?php

class Database
{
    private $db;

    public function __construct($path)
    {
        $this->db = new SQLite3($path);
    }

    public function Execute($sql)
    {
        return $this->db->exec($sql);
    }

    public function Fetch($sql)
    {
        $result = $this->db->query($sql);
        $data = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function Create($table, $data)
    {
        $keys = implode(", ", array_keys($data));
        $values = implode("', '", array_values($data));
        $sql = "INSERT INTO $table ($keys) VALUES ('$values')";
        $this->Execute($sql);
        return $this->db->lastInsertRowID();
    }

    public function Read($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = $id";
        return $this->Fetch($sql);
    }

    public function Update($table, $id, $data)
    {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }
        $set = rtrim($set, ", ");
        $sql = "UPDATE $table SET $set WHERE id = $id";
        return $this->Execute($sql);
    }

    public function Delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = $id";
        return $this->Execute($sql);
    }

    public function Count($table)
    {
        $sql = "SELECT COUNT(*) AS count FROM $table";
        $result = $this->Fetch($sql);
        return $result[0]['count'];
    }
}
