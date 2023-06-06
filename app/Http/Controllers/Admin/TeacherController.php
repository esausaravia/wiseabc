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
    $classroom = $request->get('classroom');
    $estatus = $request->get('estatus');
    $profes = User::withCount([
        'teachclasses'=>function($query){
          $query->where('status','active');
        }
      ])
      ->where('user_type', 3)
      ->when($search, function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%')
          ->orWhere('email', 'like', '%' . $search . '%');
      })
      ->when($estatus, function ($query) use ($estatus) {
        $query->where('status', $estatus);
      })
      ->when($classroom, function ($query) use ($classroom) {
        if ($classroom == '1') {
          $query->has('teachclasses');
        } else {
          $query->doesntHave('teachclasses');
        }
      })->orderBy('name')->get();

    return view('admin.profes', compact('profes', 'search', 'request'));
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(User $teacher)
  {
    return view('admin.profe', ['profe' => $teacher]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'fname' => 'required',
      'lname' => 'required',
      'email' => [
          'required',
          'string',
          'email',
          'max:255',
          Rule::unique(User::class),
      ],
      'tel' => ['required', 'regex:/[0-9()#&+*-=.]+/i']
    ]);

    $input = $request->all();

    $user = User::create([
      'user_type' => 3,
      'name' => ucwords( $validated['fname'].' '.$validated['lname'] ),
      'email' => $validated['email'],
      'password' => Hash::make($input['password'])
    ]);
    $user->status = !empty($input['status']) ? $input['status'] : 'PENDING';
    $user->save();

    $user->saveMetas($input);

    $user->saveHorarios($input['horarios']);

    return redirect()->route('admin.teacher.index')->with('success', 'Guardado con éxito');
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit(User $teacher)
  {
    if ( $teacher->fname===null ) {
      $arrName = explode(' ', $teacher->name);

      if ( count($arrName)<2 ) {
        $arrName = [$teacher->name,'WiseABC'];
      }

      $teacher->saveMetas([
        'lname' => array_pop($arrName),
        'fname' => implode(' ', $arrName)
      ]);
    }

    return view('admin.profe', [
      'profe' => $teacher,
      'form_action' => route('admin.teacher.edit', ['teacher' => $teacher])
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
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
      'personal_email' => [
        'string',
        'email',
        'max:255'
      ],
      'tel' => ['regex:/[0-9()#&+*-=.]+/i'],
    ]);

    if (!empty($validated['email'])) {

      $existing_email = \Illuminate\Support\Facades\DB::table('users')->select('id')->where('email', $validated['email'])->where('id', '<>', $teacher->id)->get();

      if ($existing_email->count() > 0) {
        return back()->withInput()->withErrors(['email' => 'Este correo ya lo tiene otro usuario']);
      }

      $teacher->email = strtolower($validated['email']);
    }

    $input = $request->all();

    if (!empty($input['status'])) {
      $teacher->status = $input['status'];
    }

    if (!empty($input['fname']) && !empty($input['lname'])) {
      $teacher->name = ucwords($input['fname'] . ' ' . $input['lname']);
    }

    if (!empty($input['password'])) {
      $teacher->password = Hash::make($input['password']);
    }
    $teacher->save();

    if ($request->hasFile('profilepic')) {
      $input['profilepic'] = $teacher->saveProfilePic($request->file('profilepic'));
    }

    $teacher->saveMetas($input);

    if(isset($input['horarios'])){
      $arrOcupados = $teacher->horariosOcupados();
      foreach ($arrOcupados as $_dia => $_arrHr) {
        if (!isset($input['horarios'][$_dia]) || !is_array($input['horarios'][$_dia])) {
          $input['horarios'][$_dia] = array();
        }
        $input['horarios'][($_dia)] = array_merge($input['horarios'][($_dia)], $_arrHr);
        sort($input['horarios'][($_dia)]);
      }
      $teacher->saveHorarios($input['horarios']);
    }


    return redirect()->route('admin.teacher.index')->with('success', 'Guardado con éxito');
  }//END update()

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
