<?php

class RepairFacilityModel
{

    function __construct()
    {
    }

    public function getAllRepairFacility($conn, $fromId, $toId)
    {
        try {
            $sql = "SELECT
            a.`id`,
            a.`name`,
            a.`en_name`,
            a.`address`,
            a.`tel`,
            a.`contact_start_date`,
            a.`sell_type`,
            a.`sell_grade`,
            a.`credit_amount`,
            a.`number_credits_given`,
            a.`contact_person_name`,
            a.`note`,
            a.`create_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date`
        FROM
            `repair_facility` a ";
            if (!empty($fromId) && !empty($toId)) {
                $sql .= " where id between :from_id and :to_id ";
            }
            $sql .= "  ORDER BY a.id DESC;";
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

    public function getRepairFacility($conn, $id)
    {
        try {
            $sql = "SELECT
            a.`id`,
            a.`name`,
            a.`en_name`,
            a.`address`,
            a.`tel`,
            a.`contact_start_date`,
            a.`sell_type`,
            a.`sell_grade`,
            a.`credit_amount`,
            a.`number_credits_given`,
            a.`contact_person_name`,
            a.`note`,
            a.`create_by`,
            a.`create_date`,
            a.`update_by`,
            a.`update_date`
        FROM
            `repair_facility` a
        WHERE a.id = :id
        ORDER BY a.id DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getRepairFacilityByName($conn, $name)
    {
        try {
            $sql = "SELECT
                    a.`id`,
                    a.`name`,
                    a.`en_name`,
                    a.`address`,
                    a.`tel`,
                    a.`contact_start_date`,
                    a.`sell_type`,
                    a.`sell_grade`,
                    a.`credit_amount`,
                    a.`number_credits_given`,
                    a.`contact_person_name`,
                    a.`note`,
                    a.`create_by`,
                    a.`create_date`,
                    a.`update_by`,
                    a.`update_date`
                FROM
                    `repair_facility` a
                WHERE a.name = :name
                ORDER BY a.id DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewRepairFacility(
        $conn,
        $name,
        $enName,
        $address,
        $tel,
        $contactStartDate,
        $sellType,
        $sellGrade,
        $creditAmount,
        $numberCreditsGiven,
        $contactPersonName,
        $note,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `repair_facility`(
                `name`,
                `en_name`,
                `address`,
                `tel`,
                `contact_start_date`,
                `sell_type`,
                `sell_grade`,
                `credit_amount`,
                `number_credits_given`,
                `contact_person_name`,
                `note`,
                `create_by`,
                `create_date`
            )
            VALUES(
                :name,
                :en_name,
                :address,
                :tel,
                :contact_start_date,
                :sell_type,
                :sell_grade,
                :credit_amount,
                :number_credits_given,
                :contact_person_name,
                :note,
                :create_by,
                sysdate()
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':en_name', $enName);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':contact_start_date', $contactStartDate);
            $stmt->bindParam(':sell_type', $sellType);
            $stmt->bindParam(':sell_grade', $sellGrade);
            $stmt->bindParam(':credit_amount', $creditAmount);
            $stmt->bindParam(':number_credits_given', $numberCreditsGiven);
            $stmt->bindParam(':contact_person_name', $contactPersonName);
            $stmt->bindParam(':note', $note);
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

    public function updateRepairFacility(
        $conn,
        $id,
        $name,
        $enName,
        $address,
        $tel,
        $contactStartDate,
        $sellType,
        $sellGrade,
        $creditAmount,
        $numberCreditsGiven,
        $contactPersonName,
        $note,
        $updateBy
    ) {
        try {
            $sql = "UPDATE
            `repair_facility`
        SET
            `name` = :name,
            `en_name` = :en_name,
            `address` = :address,
            `tel` = :tel,
            `contact_start_date` = :contact_start_date,
            `sell_type` = :sell_type,
            `sell_grade` = :sell_grade,
            `credit_amount` = :credit_amount,
            `number_credits_given` = :number_credits_given,
            `contact_person_name` = :contact_person_name,
            `note` = :note,
            `update_by` = :update_by,
            `update_date` = sysdate()
        WHERE 
            id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':en_name', $enName);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':contact_start_date', $contactStartDate);
            $stmt->bindParam(':sell_type', $sellType);
            $stmt->bindParam(':sell_grade', $sellGrade);
            $stmt->bindParam(':credit_amount', $creditAmount);
            $stmt->bindParam(':number_credits_given', $numberCreditsGiven);
            $stmt->bindParam(':contact_person_name', $contactPersonName);
            $stmt->bindParam(':note', $note);
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

    public function deleteRepairFacility($conn, $id)
    {
        try {
            $sql = "delete from repair_facility where id = :id";
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
