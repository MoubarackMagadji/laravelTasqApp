<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function login(){
        return view('users.login');
    }

    public function register(){
        return view('users.register');
    }

    public function registerPost(Request $request){
        // dd($request->all());

        $user = $request->validate([
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>['required', 'email', Rule::unique('users','email')],
            'password'=>'required|min:6'
        ]);

        $user['password'] = bcrypt($user['password']);

        $user = User::create($user);

        auth()->login($user);

        return redirect('/dashboard');
        // ->with('success','Congrats. You are now a member')
    }

    public function loginPost(Request $request){
        // dd($request->all());
        $user = $request->validate([
            'email' => ['required','email'],
            'password' => ['required', 'min:6'],
        ]);
        
        if(auth()->attempt($user)){
            $request->session()->regenerate();
            return redirect( route('dashboard'));
        }else{
            return redirect()->back()->onlyInput('email')->with('error','Invalid credentials');
        }

    }


    public function logout(){
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function avatar( Request $request){
        // dd($request->hasFile('avatar'));

        $file = $request->validate([
           'avatar'=>'required|image|max:3000' 
        ]);

        $user = auth()->user();

        Storage::delete('public/avatars/'.$user->avatar);

        $image = Image::make($request->file('avatar'))->fit(150)->encode('jpg');
        
        $fileName = auth()->user()->id.'-'.uniqid().'.jpg';

        Storage::put('public/avatars/'.$fileName , $image);

        
        $user->avatar = $fileName;
        $user->save();
        
        return redirect()->back();
    }

    
}
