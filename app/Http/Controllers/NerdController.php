<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nerd;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Input;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NerdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nerds = Nerd::all();

        return view('nerds.index', ['nerds' => $nerds]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // load the create form (app/views/nerds/create.blade.php)
        return view('nerds.create');
    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => 'required|max:255',
            'email'         => 'required|email|max:255',
            'nerd_level'    => 'required|numeric'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function store()
    public function store(Request $request)
    {

        $rules = array(
            'name'          => 'required|max:255',
            'email'         => 'required|email|max:255|unique:nerds',
            'nerd_level'    => 'required|numeric'
        );

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $nerd               = new Nerd;
            $nerd->name         = $request->input('name');
            $nerd->email        = $request->input('email');
            $nerd->nerd_level   = $request->input('nerd_level');
            $nerd->save();
            return redirect('nerds')->with('message', 'Successfully created nerd!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get the nerd
        $nerd = Nerd::find($id);

        // show the nerd
        return view('nerds.show')->withNerd($nerd);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get the nerd
        $nerd = Nerd::find($id);

        // show the edit form and pass the nerd
        return view('nerds.edit')->withNerd($nerd);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $rules = array(
            'name'              => 'required',
            'email'             => 'required|email',
            'nerd_level'        => 'required|numeric'
        );

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $nerd               = Nerd::find($id);
            $nerd->name         = $request->input('name');
            $nerd->email        = $request->input('email');
            $nerd->nerd_level   = $request->input('nerd_level');
            $nerd->save();
            return redirect('nerds')->with('message', 'Successfully updated nerd!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $nerd = Nerd::find($id);
        $nerd->delete();

        // redirect
        return redirect('nerds')->with('message', 'Successfully deleted the nerd!');
    }
}
