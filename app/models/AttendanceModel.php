<?php

class AttendanceModel
{

    public static function get($employee_id)
    {
    	$stmt = Database::connect()->prepare("SELECT days_worked, overtime FROM attendance
            WHERE employee_id = :employee_id
            ");
        $stmt->bindparam(":employee_id",$employee_id);
        $stmt->execute();
        $attendance = $stmt->fetch(PDO::FETCH_ASSOC);
        return $attendance;
    }
}