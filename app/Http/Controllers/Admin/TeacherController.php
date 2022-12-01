<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


      $search = $request->get('searchfor');
      //create query get user_type 2  and search for name or email
      $profes = User::where('user_type', 3)->where(function($query) use ($search) {
        $query->where('name', 'like', '%'.$search.'%')
          ->orWhere('email', 'like', '%'.$search.'%');
      })->with(['usermetas','classrooms'])->orderBy('name')->get();

        return view('admin.profes', compact('profes','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $teacher)
    {
        return view('admin.profe', ['profe'=>$teacher ]);
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
    public function edit(User $teacher)
    {
        return view('admin.profe', [
            'profe'=>$teacher,
            'form_action'=>route('admin.teacher.edit', ['teacher'=>$teacher])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'email' => [
                'string',
                'email',
                'max:255'
            ],
            'personal_email'=> [
                'string',
                'email',
                'max:255'
            ],
            'tel' => ['regex:/[0-9()#&+*-=.]+/i'],
        ]);

        if ( !empty($validated['email']) ) {

            $existing_email = \Illuminate\Support\Facades\DB::table('users')->select('id')->where('email', $validated['email'])->where('id','<>', $teacher->id)->get();

            if ( $existing_email->count()>0 ) {
                return back()->withInput()->withErrors(['email'=>'Este correo ya lo tiene otro usuario']);
            }

            $teacher->email = strtolower($validated['email']);
        }

        $input = $request->all();

        if ( !empty($input['status']) ) {
            $teacher->status = $input['status'];
        }

        if ( !empty($input['fname']) && !empty($input['lname']) ) {
            $teacher->name = ucwords($input['fname'].' '.$input['lname']);
        }

        if ( !empty($input['password']) ) {
            $teacher->password = Hash::make($input['password']);
        }
        $teacher->save();

        if ( $request->hasFile('profilepic') ) {
            $input['profilepic'] = $teacher->saveProfilePic( $request->file('profilepic') );
        }

        $teacher->saveMetas( $input );

        $arrOcupados = $teacher->horariosOcupados();
        foreach( $arrOcupados AS $_dia=>$_arrHr ) {
            if ( !is_array($input['horarios'][($_dia)]) ) {
                $input['horarios'][($_dia)] = array();
            }
            $input['horarios'][($_dia)] = array_merge( $input['horarios'][($_dia)], $_arrHr);
            sort($input['horarios'][($_dia)]);
        }
        $teacher->saveHorarios($input['horarios']);

        return redirect()->route('admin.teacher.index')->with('success','Guardado con éxito');
    }//END update()

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
}
