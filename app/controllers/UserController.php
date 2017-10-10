<?php

class UserController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();

        return Response::json([
                'error' => false,
                'data' => $users->toArray()
                ], 200, [], JSON_PRETTY_PRINT
        );
    }

    /**
     * Send email to new user
     * @param $user
     */
    public function sendEmail($user)
    {
        // adding queue an email
        Mail::queue('emails.welcome', '', function($message) use ($user)
        {
            $message->from(\Config::get('app.email_from')
                    ->to($user->email)
                    ->subject('Welcome'));
        });
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

        $user = new User;
        $user->firstname = Request::get('firstname');
        $user->lastname = Request::get('lastname');
        $user->email = Request::get('email');

        $user->save();
        $this->sendEmail($user);

        return Response::json([
                'error' => false,
                'data' => $user->toArray()
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
        $user = User::find($id);

        return Response::json([
                'error' => false,
                'data' => $user->toArray()
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

        $user = User::find($id);
        $user->firstname = Request::get('firstname');
        $user->lastname = Request::get('lastname');
        $user->email = Request::get('email');
        $user->save();

        return Response::json([
                'error' => false,
                'data' => $user->toArray()
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
        $user = User::find($id);
        $user->delete();

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
            array(
                'firstname' => Request::get('firstname'),
                'lastname' => Request::get('lastname'),
                'email' => Request::get('email')
            ),
            array(
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email|unique:users'
            )
        );

        if ($validator->fails()) {
            return false;
        }
        return true;
    }
}
