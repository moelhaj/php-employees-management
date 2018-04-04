<?php

class Config
{
	public static function index()
	{

		$config = ConfigModel::get();

		$house_rent = $config['house_rent'];

		$house_rent_percent = round((float)$house_rent * 100 ) . '%';

		$transport_allowance = $config['transport_allowance'];

		$transport_allowance_percent = round((float)$transport_allowance * 100 ) . '%';

		$other_allowance = $config['other_allowance'];

		$other_allowance_precent = round((float)$other_allowance * 100 ) . '%';

		$life_insurance_deductions = $config['life_insurance_deductions'];

		$life_insurance_deductions_precent = round((float)$life_insurance_deductions * 100 ) . '%';

		$holidays_float = $config['holidays'];
		$holidays = intval($holidays_float);

		View::render('config.html',[
			'config' => $config,
			'house_rent' => $house_rent,
			'house_rent_percent' => $house_rent_percent,
			'transport_allowance_percent' => $transport_allowance_percent,
			'other_allowance_precent' => $other_allowance_precent,
			'life_insurance_deductions_precent' => $life_insurance_deductions_precent,
			'holidays' => $holidays
		]);
	}


	public static function update()
	{
		$house_rent = $_POST['house_rent'];
        $medical_allowance = $_POST['medical_allowance'];
        $transport_allowance = $_POST['transport_allowance'];
        $other_allowance = $_POST['other_allowance'];
        $marital_status = $_POST['marital_status'];
        $holidays = $_POST['holidays'];
        $life_insurance_deductions = $_POST['life_insurance_deductions'];

        ConfigModel::update($house_rent, $medical_allowance, $transport_allowance, $other_allowance, $marital_status, $holidays, $life_insurance_deductions);

        View::redirect('config');
	}
}