<?php

namespace App\Model\healthcare;

use Hyperf\DbConnection\Model\Model as MineModel;


/**
* Class medical_expenses
* @property string $id 
* @property string $visit_id 
* @property string $expense_name 
* @property string $price 
* @property string $quantity 
*/
class MedicalExpenses extends MineModel
{
    public bool $timestamps = false;


    protected ?string $table = 'medical_expenses';

    protected array $fillable = ['id','visit_id','expense_name','price','quantity',];

    protected array $casts = ['id' => 'int','visit_id' => 'string','expense_name' => 'string','price' => 'string','quantity' => 'string',];
}