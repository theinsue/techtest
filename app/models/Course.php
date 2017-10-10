<?php

class Course extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    public $timestamps = false;

    public function scores()
    {
        return $this->hasMany(UserScore::class, 'course_id');
    }
}

