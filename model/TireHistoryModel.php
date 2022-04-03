<?php

class TireHistoryModel
{
    function __construct()
    {
    }

    public function page($conn, $page_no)
    {
        try {
            $per_page = 50;
            $offset = ($page_no - 1) * $per_page;
            $stmt = $conn->prepare('SELECT COUNT(*) FROM tire_history');
            if ($stmt->execute()) {
                $total_rows = $stmt->fetch()[0];
                return array(
                    'offset' => $offset,
                    'perPage' => $per_page,
                    'totalResult' => $total_rows
                );
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getAllTireHistory($conn, $offset, $per_page)
    {
        try {
            $sql = 'SELECT * FROM `tire_history` ORDER BY id DESC LIMIT :offset,:per_page';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':per_page', $per_page, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTireHistory($conn, $tire_code)
    {
        try {
            $sql = 'SELECT * FROM `tire_history` WHERE tire_code = :tire_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tire_code', $tire_code);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTireType($conn, $tireType)
    {
        try {
            $sql = 'SELECT * FROM `tire_history` WHERE tire_type = :tire_type';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tire_type', $tireType);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewTireHistory(
        $conn,
        $tire_code,
        $tire_brand,
        $tire_model,
        $size,
        $tire_type,
        $use_date,
        $createBy
    ) {

        try {
            $sql = "
            insert into tire_history (
                tire_code, tire_brand, tire_model, size, tire_type, use_date, create_by, create_date
            ) 
            values (
                :tire_code, :tire_brand, :tire_model, :size, :tire_type, :use_date, :create_by, sysdate()
            )
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tire_code', $tire_code);
            $stmt->bindParam(':tire_brand', $tire_brand);
            $stmt->bindParam(':tire_model', $tire_model);
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':tire_type', $tire_type);
            $stmt->bindParam(':use_date', $use_date);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateTireHistory(
        $conn,
        $tire_code,
        $tire_brand,
        $tire_model,
        $size,
        $tire_type,
        $use_date,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE tire_history SET 
                `tire_brand` = :tire_brand, 
                `tire_model` = :tire_model,
                `size` = :size,
                `tire_type` = :tire_type,
                `use_date` = :use_date,
                 `update_by` = :update_by,
                  `update_date` = sysdate()
                  where   `tire_code` = :tire_code
            ";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':tire_brand', $tire_brand);
            $stmt->bindParam(':tire_model', $tire_model);
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':tire_type', $tire_type);
            $stmt->bindParam(':use_date', $use_date);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':tire_code', $tire_code);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteTireHistory($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
