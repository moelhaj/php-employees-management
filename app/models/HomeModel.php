<?php

class HomeModel
{
	public static function salary()
    {
        $stmt = Database::connect()->prepare("SELECT d.department_name, e.employee_id, e.first_name, e.last_name, p.net_payable
			FROM employees e
			INNER JOIN payroll p
			ON e.employee_id = p.employee_id
            INNER JOIN departments d
            ON e.department_id = d.department_id
            GROUP BY d.department_name
		");
		$stmt->execute();
        $salary = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $salary;
    }

    public static function male()
    {
    	$stmt = Database::connect()->prepare("SELECT id FROM employees WHERE gender = 'male'");
    	$stmt->execute();
    	$male = $stmt->rowCount();
    	return $male;
    }

    public static function departments()
    {
        $stmt = Database::connect()->prepare("SELECT department_id FROM departments");
        $stmt->execute();
        $departments = $stmt->rowCount();
        return $departments;
    }


}


