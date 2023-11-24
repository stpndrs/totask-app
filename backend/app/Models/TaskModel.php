<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TaskModel extends Model
{
    use HasFactory;

    protected $table = 'task';
    protected $guarded = ['id'];

    public function getDataByUser($user_id) {
        return self::where('user_id', $user_id)
            ->get();
    }
}
