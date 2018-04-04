<?php

class Home extends View
{
	public static function index()
	{
		$departments = HomeModel::departments();
		$count = EmployeesModel::count();
		$employeesSalary = HomeModel::salary();
		$male = HomeModel::male();
		$female = $count - $male;


		$grossWages = PayrollModel::grossWages();
		$net_payables = PayrollModel::netPayables();
		$deduction = PayrollModel::deduction();
		$health = PayrollModel::health();
		$transport = PayrollModel::transport();
		$other_allowance = PayrollModel::other_allowance();
		$total_allowance = $health + $transport + $other_allowance;
		//Gross wages are the total amount an employee is paid before any taxes
		//total net payables
		// health insurance total of health insurance

		View::render('index.html', [
            'count'  => $count,
            'employeesSalary' => $employeesSalary,
            'male' => $male,
            'female' => $female,
            'departments' => $departments,
            'grossWages' => $grossWages,
            'net_payables' => $net_payables,
            'deduction' => $deduction,
            'health' => $health,
            'transport' => $transport,
            'other_allowance' => $other_allowance,
            'total_allowance' => $total_allowance
        ]);
	}
}