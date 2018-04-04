<?php

class EmployeesModel
{	


    public static function add($employee_id, $first_name, $middle_name, $last_name, $email, $phone, $age, $marital_status, $gender, $birth_date, $nationality, $department, $job_title, $manager_id, $contract_type, $salary, $hire_date, $end_date)
    {
        $stmt = Database::connect()->prepare("INSERT INTO employees(employee_id, first_name, middle_name, last_name, email, phone, age, marital_status, gender, birth_date, nationality, department_id, job_id, manager_id, contract_type, salary, hire_date, end_date)VALUES(:employee_id, :first_name, :middle_name, :last_name, :email, :phone, :age, :marital_status, :gender, :birth_date, :nationality, :department, :job_title, :manager_id, :contract_type, :salary, :hire_date, :end_date)");

        $stmt->bindparam(":employee_id",$employee_id);
        $stmt->bindparam(":first_name",$first_name);
        $stmt->bindparam(":middle_name",$middle_name);
        $stmt->bindparam(":last_name",$last_name);
        $stmt->bindparam(":email",$email);
        $stmt->bindparam(":phone",$phone);
        $stmt->bindparam(":age",$age);
        $stmt->bindparam(":marital_status",$marital_status);
        $stmt->bindparam(":gender",$gender);
        $stmt->bindparam(":birth_date",$birth_date);
        $stmt->bindparam(":nationality",$nationality);
        $stmt->bindparam(":department",$department);
        $stmt->bindparam(":job_title",$job_title);
        $stmt->bindparam(":manager_id",$manager_id);
        $stmt->bindparam(":contract_type",$contract_type);
        $stmt->bindparam(":salary",$salary);
        $stmt->bindparam(":hire_date",$hire_date);
        $stmt->bindparam(":end_date",$end_date);
        $stmt->execute();
    }

	public static function get($start,$perPage)
    {
        $stmt = Database::connect()->prepare("SELECT e.employee_id, e.first_name, e.middle_name, e.last_name, e.email, e.phone, e.age, e.marital_status, e.gender, e.birth_date, e.nationality, d.department_name, j.job_title, e.contract_type, e.hire_date, a.city, a.country, a.address 
            FROM employees e 
            INNER JOIN address a 
            ON e.employee_id = a.employee_id 
            INNER JOIN jobs j 
            ON e.job_id = j.job_id 
            INNER JOIN departments d 
            ON e.department_id = d.department_id
            LIMIT :start, :perpage ");
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $employees;
    }

    public static function count()
    {
        $stmt = Database::connect()->prepare("SELECT id FROM employees");
        $stmt->execute();
        $count = $stmt->rowCount();
        return $count;
    }

    public static function last()
    {
        $stmt = Database::connect()->prepare("SELECT employee_id FROM employees ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $last = $stmt->fetch(PDO::FETCH_ASSOC);
        return $last;
    }

    public static function manager(){
        $stmt = Database::connect()->prepare("SELECT e.employee_id, e.first_name, e.last_name, d.department_id, d.department_name FROM employees e 
            INNER JOIN dept_manager de
            ON e.employee_id = de.manager_id
            INNER JOIN departments d
            ON d.department_id = de.department_id
            ");
        $stmt->execute();
        $manager = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $manager;
    }


    public static function employee($id)
    {
        $stmt = Database::connect()->prepare("SELECT e.employee_id, e.first_name, e.middle_name, e.last_name, e.email, e.phone, e.age, e.marital_status, e.gender, e.birth_date, e.nationality, d.department_name, j.job_title, e.contract_type, e.hire_date, a.city, a.state, a.country, a.address 
            FROM employees e 
            INNER JOIN address a 
            ON e.employee_id = a.employee_id 
            INNER JOIN jobs j 
            ON e.job_id = j.job_id 
            INNER JOIN departments d 
            ON e.department_id = d.department_id
            WHERE e.employee_id = :id LIMIT 1");
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_OBJ);
        return $employee;
    }


    public static function update($employee_id, $first_name, $middle_name, $last_name, $email, $phone, $age, $marital_status, $gender, $birth_date, $nationality)
    {
        $stmt = Database::connect()->prepare("UPDATE employees SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, email = :email, phone = :phone, age = :age, marital_status = :marital_status, gender = :gender, birth_date = :birth_date, nationality = :nationality WHERE employee_id = :employee_id");

        $stmt->bindparam(":employee_id",$employee_id);
        $stmt->bindparam(":first_name",$first_name);
        $stmt->bindparam(":middle_name",$middle_name);
        $stmt->bindparam(":last_name",$last_name);
        $stmt->bindparam(":email",$email);
        $stmt->bindparam(":phone",$phone);
        $stmt->bindparam(":age",$age);
        $stmt->bindparam(":marital_status",$marital_status);
        $stmt->bindparam(":gender",$gender);
        $stmt->bindparam(":birth_date",$birth_date);
        $stmt->bindparam(":nationality",$nationality);
        $stmt->execute();
    }

    public static function search($employee_name)
    {
        $stmt = Database::connect()->prepare("SELECT e.employee_id, e.first_name, e.middle_name, e.last_name, e.email, e.phone, e.age, e.marital_status, e.gender, e.birth_date, e.nationality, e.department_id, e.job_id, e.manager_id, e.salary, e.hire_date, e.end_date, a.city, a.country, a.address, j.job_title 
            FROM employees e
            INNER JOIN address a
            ON e.employee_id = a.employee_id
            INNER JOIN jobs j
            ON e.job_id = j.job_id
        WHERE e.first_name LIKE :name OR e.last_name LIKE :name ");
        $stmt->bindValue(':name', '%'.$employee_name.'%', PDO::PARAM_INT);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $employees;
    }


    public static function emp_dept($start,$perPage,$department)
    {
        $stmt = Database::connect()->prepare("SELECT e.employee_id, e.first_name, e.middle_name, e.last_name, e.email, e.phone, e.age, e.marital_status, e.gender, e.birth_date, e.nationality, e.department_id, e.job_id, e.manager_id, e.salary, e.hire_date, e.end_date, a.city, a.country, a.address, j.job_title
            FROM employees e
            INNER JOIN address a
            ON e.employee_id = a.employee_id
            INNER JOIN jobs j
            ON e.job_id = j.job_id
            WHERE e.department_id = :department
            LIMIT :start, :perpage ");
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':department', $department, PDO::PARAM_INT);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $employees;
    }


    public static function count_emp_dept($department)
    {
        $stmt = Database::connect()->prepare("SELECT id FROM employees WHERE department_id = :department");
        $stmt->bindValue(':department', $department, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->rowCount();
        return $count;
    }
}