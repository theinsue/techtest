<?php

class CoursesController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $courses = Course::all();

        return Response::json([
            'error' => false,
            'data' => $courses->toArray()
        ], 200, [], JSON_PRETTY_PRINT
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if ($this->validate()) {
            return Redirect::back()->with('message','Validation failed!');
        }

        $course = new Course;
        $course->name = Request::get('name');
        $course->save();

        return Response::json([
            'error' => false,
            'data' => $course->toArray()
        ], 200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $course = Course::find($id);

        return Response::json([
            'error' => false,
            'data' => $course->toArray()
        ], 200, [], JSON_PRETTY_PRINT
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if ($this->validate()) {
            return Redirect::back()->with('message','Validation failed!');
        }

        $course = Course::find($id);
        $course->name = Request::get('name');
        $course->save();

        return Response::json([
            'error' => false,
            'data' => $course->toArray()
        ], 200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        $course->delete();

        return Response::json([], 204);
    }

    /**
     * Validate the fields
     *
     * @return bool
     */
    public function validate()
    {
        $validator = Validator::make(
            array('name' => Request::get('name')),
            array('name' => array('required', 'unique'))
        );

        if ($validator->fails()) {
            return false;
        }
        return true;
    }
}


