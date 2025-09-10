<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentJoinApplicationLog extends BaseUuidModel
{
    use HasFactory;

    protected $table = 'student_join_applications_logs';

    protected $fillable = [
        'application_id',
        'user_id',
        'action',
        'comment',
    ];

    public function application()
    {
        return $this->belongsTo(StudentJoinApplication::class, 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
