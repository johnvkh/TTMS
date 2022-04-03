<?php

class UnitModel
{

    function __construct()
    {
    }

    public function getAllUnit($conn)
    {
        try {
            $sql = "select * from tis_unit order by id desc";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getUnit($conn, $id)
    {
        try {
            $sql = "select * from tis_unit WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkUnitName($conn, $unitName)
    {
        try {
            $sql = "select unit_name from tis_unit where unit_name = :unit_name";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':unit_name', $unitName);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createUnit(
        $conn,
        $unitName,
        $create_by
    ) {

        try {
            $sql = "insert into tis_unit (unit_name, create_by, create_date) values (:unit_name, :create_by, sysdate())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':unit_name', $unitName);
            $stmt->bindParam(':create_by', $create_by);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function udpateUnit(
        $conn,
        $id,
        $unitName,
        $update_by
    ) {
        try {
            $sql = "update tis_unit set unit_name = :unit_name, update_by = :update_by, update_date = sysdate() where id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':unit_name', $unitName);
            $stmt->bindParam(':update_by', $update_by);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteUnit($conn, $id)
    {
        try {
            $sql = "delete from tis_unit where id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
