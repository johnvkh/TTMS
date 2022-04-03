<?php

class OwnerTruckSharingModel
{


    function __construct()
    {
    }

    public function getAllOwnerTruckSharing($conn)
    {
        try {
            $sql = 'SELECT * FROM `owner_truck_sharing` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getOwnerTruckSharing($conn,  $code)
    {
        try {
            $sql = 'SELECT * FROM `owner_truck_sharing` WHERE code = :code ';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewOwnerTruckSharing(
        $conn,
        $code,
        $name,
        $name_en,
        $address,
        $tel,
        $start_contact_date,
        $type,
        $grade,
        $credit_limit,
        $number_day_credit,
        $contact_with_name,
        $note,
        $createBy
    ) {

        try {
            $sql = "
                insert into owner_truck_sharing(
                   code, name, name_en, address, tel,
                    start_contact_date, type, grade, credit_limit, 
                    number_day_credit, contact_with_name,
                   note, create_by, create_date
                ) values (
                    :code, :name, :name_en, :address, :tel,
                    :start_contact_date, :type, :grade, :credit_limit,
                    :number_day_credit,:contact_with_name, :note, :create_by, sysdate()
                )
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':name_en', $name_en);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':start_contact_date', $start_contact_date);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':grade', $grade);
            $stmt->bindParam(':credit_limit', $credit_limit);
            $stmt->bindParam(':number_day_credit', $number_day_credit);
            $stmt->bindParam(':contact_with_name', $contact_with_name);
            $stmt->bindParam(':note', $note);
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

    public function updateOwnerTruckSharing(
        $conn,
        $code,
        $name,
        $name_en,
        $address,
        $tel,
        $start_contact_date,
        $type,
        $grade,
        $credit_limit,
        $number_day_credit,
        $contact_with_name,
        $note,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE owner_truck_sharing SET 
                code = :code, 
                name = :name, 
                name_en = :name_en, 
                address = :address, 
                tel = :tel,
                start_contact_date = :start_contact_date, 
                type = :type, 
                grade = :grade, 
                credit_limit = :credit_limit,
                number_day_credit = :number_day_credit,
                contact_with_name = :contact_with_name,
                note = :note,
                update_by = :update_by, 
                update_date = sysdate()
                where  code = :code 
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':name_en', $name_en);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':start_contact_date', $start_contact_date);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':grade', $grade);
            $stmt->bindParam(':credit_limit', $credit_limit);
            $stmt->bindParam(':number_day_credit', $number_day_credit);
            $stmt->bindParam(':contact_with_name', $contact_with_name);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':code', $code);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteOwnerTruckSharing($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
