<?php

namespace App\Model\injury;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class injury_party_information
* @property string $id 
* @property string $application_id 
* @property string $name 
* @property string $phone 
* @property string $gender 
* @property string $id_number 
* @property string $address 
* @property string $transportation_method 
* @property string $vehicle_owner 
* @property string $license_plate 
* @property string $vehicle_owner_address 
* @property string $insured_person 
* @property string $insurance_company 
* @property string $insurance_type 
* @property string $insurance_amount 
* @property string $created_at 
* @property string $updated_at 
* @property string $created_by 
* @property string $updated_by 
* @property string $deleted_at 
* @property string $ward_number 
* @property string $bed_number 
* @property string $injury_diagnosis 
* @property string $total_medical_expenses_insurance 
* @property string $total_medical_expenses_self_pay 
* @property string $hospitalization_number 
* @property string $hospitalization_department 
* @property string $discharge_status 
* @property string $total_medical_expenses_road_fund 
*/
class InjuryPartyInformation extends MineModel
{
    protected ?string $table = 'injury_party_information';

    protected array $fillable = ['id','application_id','name','phone','gender','id_number','address','transportation_method','vehicle_owner','license_plate','vehicle_owner_address','insured_person','insurance_company','insurance_type','insurance_amount','created_at','updated_at','created_by','updated_by','deleted_at','ward_number','bed_number','injury_diagnosis','total_medical_expenses_insurance','total_medical_expenses_self_pay','hospitalization_number','hospitalization_department','discharge_status','total_medical_expenses_road_fund','is_injured',
    'hospital_id'

];

    protected array $casts = ['id' => 'int','application_id' => 'int','name' => 'string','phone' => 'string','gender' => 'string','id_number' => 'string','address' => 'string','transportation_method' => 'string','vehicle_owner' => 'string','license_plate' => 'string','vehicle_owner_address' => 'string','insured_person' => 'string','insurance_company' => 'string','insurance_type' => 'string','insurance_amount' => 'string','created_at' => 'string','updated_at' => 'string','created_by' => 'string','updated_by' => 'string','deleted_at' => 'string','ward_number' => 'string','bed_number' => 'string','injury_diagnosis' => 'string','total_medical_expenses_insurance' => 'string','total_medical_expenses_self_pay' => 'string','hospitalization_number' => 'string','hospitalization_department' => 'string','discharge_status' => 'string','total_medical_expenses_road_fund' => 'string','is_injured' => 'int','hospital_id' => 'int'];
}