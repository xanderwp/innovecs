<?php

namespace App\Model\DataBase;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;
use Carbon\Carbon;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'email', 'amount', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    static $status = ['rejected', 'approved'];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->status = self::$status[array_rand(self::$status)];
        });
    }

    public static function getMonthSum(){
        return self::groupBy('email')->where('status', 'approved')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->selectRaw('sum(amount) as sum, email')
            ->pluck('sum','email')->toArray();
    }

    public static function getWeekSum(){
        $result = self::orderBy('created_at', 'desc')
            ->groupBy('email')
            ->groupBy(\DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->where('status', 'approved')
            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->startOfWeek()->subDay()])
            ->get(array(
                \DB::raw('DAYOFWEEK(created_at) as day'),
                \DB::raw('sum(amount) as "sum"'),
                'email'
            ));
        if($result) {
            foreach ($result as $transactions) {
                $report[$transactions->email][$transactions->day] = $transactions->sum;
            }
            return $report;
        }
        return $result;
    }
}
