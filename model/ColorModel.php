<?php

class ColorModel {

    function __construct() {
        
    }

    public function getAllColor($conn) {
        $sql = "select * from color order by id desc";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getColor($conn, $colorId) {
        $sql = "select id from color where id = :color_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":color_id", $colorId);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getColorByName($conn, $colorName) {
        $sql = "select * from color where color_name like '%" . $colorName . "%'";
        try {
            $stmt = $conn->prepare($sql);
//            $stmt->bindParam(":color_name", $colorName);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewColor($conn, $colorName, $createBy) {
        $sql = "insert into color (color_name, create_by, create_date) values (:color_name, :create_by, sysdate())";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":color_name", $colorName);
            $stmt->bindParam(":create_by", $createBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function updateColor($conn, $colorId, $colorName, $updateBy) {
        $sql = "update color set color_name = :color_name, update_by = :update_by, update_date = sysdate() where id = :color_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":color_id", $colorId);
            $stmt->bindParam(":color_name", $colorName);
            $stmt->bindParam(":update_by", $updateBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function deleteColor($conn, $colorId) {
        $sql = "DELETE FROM color WHERE  id =" . $colorId . "";
        try {
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkColorName($conn, $colorName) {
        $sql = "select * from color where color_name = :color_name";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":color_name", $colorName);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkColorId($conn, $colorId) {
        $sql = "select id from color where id = :color_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":color_id", $colorId);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

}
