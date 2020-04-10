<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:edit-users');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::paginate(10);

        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->only(['name', 'email', 'password', 'password_confirmation']);

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed']
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->route('users.index');
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::find($id);

        if ($user) {
            return view('admin.users.edit', ['user' => $user]);
        }

        return redirect()->route('users.index');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $data = $request->only(['name', 'email', 'password', 'password_confirmation']);

            $validator = Validator::make([
               'name' => $data['name'],
               'email' => $data['email']
            ], [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255']
            ]);

            if ($validator->fails()) {
                return redirect()->route('users.edit', ['user' => $id])->withInput()->withErrors($validator);
            }

            $user->name = $data['name'];

            if ($user->email !== $data['email']) {
                $validatorEmail = Validator::make(['email' => $data['email']], ['email' => ['unique:users']]);

                if ($validatorEmail->fails()) {
                    $validator->errors()->add('email', __('validation.unique', ['attribute' => 'email']));
                }

                $user->email = $data['email'];
            }

            if (!empty($data['password'])) {
                if (strlen($data['password']) >= 4) {
                    if ($data['password'] === $data['password_confirmation']) {
                        $user->password = Hash::make($data['password']);
                    } else {
                        $validator->errors()->add('password', __('validation.confirmed', ['attribute' => 'password']));
                    }
                } else {
                    $validator->errors()->add('password', __('validation.min.string', [
                        'attribute' => 'password',
                        'min' => 4
                    ]));
                }
            }

            if (count($validator->errors())) {
                return redirect()->route('users.edit', ['user' => $id])->withErrors($validator)->withInput();
            }

            $user->save();
        }

        return redirect()->route('users.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $loggedId = Auth::id();

        if ($loggedId != $id) {
            $user = User::find($id);
            $user->delete();
        }

        return redirect()->route('users.index');
    }
}
