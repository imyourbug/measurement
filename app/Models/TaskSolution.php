<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'position',
        'unit',
        'kpi',
        'result',
        'image',
        'detail',
        'task_id',
        'solution_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'id');
    }
}