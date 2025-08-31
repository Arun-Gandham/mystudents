<?php
namespace App\Models;

class School extends BaseUuidModel
{
    protected $table = 'schools';
    protected $fillable = [
        'name',
        'domain',
        'is_active',
    ];
    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function users()    { return $this->hasMany(User::class); }
    public function students() { return $this->hasMany(Student::class); }
    public function academics(){ return $this->hasMany(Academic::class); }
    public function grades()   { return $this->hasMany(Grade::class); }
    public function subjects() { return $this->hasMany(Subject::class); }
    public function details()  { return $this->hasOne(SchoolDetail::class); }
    public function sections() { return $this->hasManyThrough(Section::class, Grade::class); }
}
