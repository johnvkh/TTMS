<?php

class DocumentNoModel
{


    function __construct()
    {
    }

    public function getAllDocumentNo($conn)
    {
        try {
            $sql = 'SELECT * FROM `document_no` ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getDocumentNo($conn,  $code)
    {
        try {
            $sql = 'SELECT * FROM `document_no` WHERE code = :code ORDER BY id DESC';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewDocumentNo(
        $conn,
        $new_running_no_truck,
        $new_running_no_sharing,
        $new_bill_number,
        $createBy
    ) {

        try {
            $sql = "
                insert into document_no(
                   new_running_no_truck, new_running_no_sharing, new_bill_number, create_by, create_date
                ) values (
                    :new_running_no_truck, :new_running_no_sharing, :new_bill_number, :create_by, sysdate()
                ) 
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':new_running_no_truck', $new_running_no_truck);
            $stmt->bindParam(':new_running_no_sharing', $new_running_no_sharing);
            $stmt->bindParam(':new_bill_number', $new_bill_number);
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

    public function updateDocumentNo(
        $conn,
        $code,
        $newRunningNumber,
        $updateBy
    ) {
        try {
            $sql = "UPDATE document_no SET old = :old, update_by = :update_by, update_date = SYSDATE() WHERE code = :code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':old', $newRunningNumber);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':code', $code);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteDocumentNo($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
