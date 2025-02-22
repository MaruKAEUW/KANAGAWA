<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'orders_detail';
    protected $fillable = [
        'id','created_at','updated_at','orders_id','user_id','course_id','price'
    ];


    protected $appends = [
        'status_name',
    ];

    public function getStatusNameAttribute() {
        $course_id = $this->course_id;
        $user_id = $this->user_id;
        $RateYo = RateYo::where('user_id', $user_id)->where('course_id',$course_id)->first();
        if($RateYo){
            return 1;
        }else{
            return 2;
        }

        
    }


    public function Course()
    {
            return $this->hasOne(Course::class, 'id', 'course_id');
    }

    public function Order()
    {
            return $this->hasOne(Order::class, 'id', 'orders_id');
    }
}