<?php

namespace App\Model\healthcare;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class patient_hospital_mapping
* @property string $id 
* @property string $patient_id 
* @property string $hospital_id 
* @property string $hospital_patient_id 
* @property string $created_at 
* @property string $updated_at 
*/
class PatientHospitalMapping extends MineModel
{
    public bool $timestamps = false;


    protected ?string $table = 'patient_hospital_mapping';

    protected array $fillable = ['id','patient_id','hospital_id','hospital_patient_id','created_at','updated_at',];

    protected array $casts = ['id' => 'int','patient_id' => 'string','hospital_id' => 'string','hospital_patient_id' => 'string','created_at' => 'string','updated_at' => 'string',];
}