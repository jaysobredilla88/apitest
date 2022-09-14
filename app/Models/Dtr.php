<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Dtr extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['employee_id', 'date', 'time_in', 'time_out'];

    public $work_in = '8:00 AM';
    public $break_hrs = 1;
    public $work_hrs = 8;

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function computeWorked()
    {
        $work_start = $this->time_in;
        $half_day = floor($this->work_hrs / 2);

        //get starting work hour
        if (date('Y-m-d H:i', strtotime($this->date. ' '.$this->time_in)) < date('Y-m-d H:i', strtotime($this->date. ' '.$this->work_in))) {
            $work_start = $this->work_in;
        }

        $hrs = floor((strtotime($this->date. ' '.$this->time_out) - strtotime($this->date. ' '.$work_start))/3600);

        if ($hrs > ($half_day + $this->break_hrs)) {
            //deduct break hours from total hours
            $hrs = $hrs - $this->break_hrs;
        }

        return $hrs;
    }

    public function computeLate()
    {
        $hrs = 0;
        if (date('Y-m-d H:i', strtotime($this->date. ' '.$this->time_in)) > date('Y-m-d H:i', strtotime($this->date. ' '.$this->work_in))) {
            $hrs = ceil((strtotime($this->date. ' '.$this->time_in) - strtotime($this->date. ' '.$this->work_in))/3600);
        }

        return $hrs;
    }

    public function computeUndertime()
    {
        $hrs = 0;
        $work_out = date('Y-m-d H:i', strtotime($this->date. ' '.$this->work_in. ' + '.($this->work_hrs + $this->break_hrs).' hours'));

        if (date('Y-m-d H:i', strtotime($this->date. ' '.$this->time_out)) < $work_out) {
            $hrs = ceil((strtotime($work_out) - strtotime($this->date. ' '.$this->time_out))/3600);
        }

        return $hrs;
    }

    public function computeOvertime()
    {
        $hrs = 0;
        $work_out = date('Y-m-d H:i', strtotime($this->date. ' '.$this->work_in. ' + '.($this->work_hrs + $this->break_hrs).' hours'));

        if (date('Y-m-d H:i', strtotime($this->date. ' '.$this->time_out)) > $work_out) {
            $hrs = ceil((strtotime($this->date. ' '.$this->time_out) - strtotime($work_out))/3600);
        }


        return $hrs;
    }
}
