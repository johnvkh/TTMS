<?php

class RepairTypeModel
{

    function __construct()
    {
    }

    public function getAllRepairType($conn, $fromId, $toId)
    {
        try {
            $sql = "select * from repair_type ";
            if (!empty($fromId) && !empty($toId)) {
                $sql .= " where id between :from_id and :to_id ";
            }
            $sql .= " order by id desc";
            $stmt = $conn->prepare($sql);
            if (!empty($fromId) && !empty($toId)) {
                $stmt->bindParam(':from_id', $fromId);
                $stmt->bindParam(':to_id', $toId);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRepairType($conn, $id)
    {
        try {
            $sql = "select * from repair_type WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRepairTypeByName($conn, $name)
    {
        try {
            $sql = "select * from repair_type WHERE name = :name";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewRepairType(
        $conn,
        $name,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `repair_type`(`name`, `create_by`, `create_date`)
            VALUES(:name, :create_by, SYSDATE())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
            http_response_code(200);
        }
    }

    public function updateRepairType(
        $conn,
        $id,
        $name,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
           `repair_type`
       SET
           `name` = :name,
           `update_by` = :update_by,
           `update_date` = sysdate()
       WHERE
           `id` = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':update_by', $updateBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteRepairType($conn, $id)
    {
        try {
            $sql = "delete from repair_type where id = :id";
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
