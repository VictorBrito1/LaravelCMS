<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * ProfileController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        $loggedId = intval(Auth::id());

        $user = User::find($loggedId);

        if ($user) {
            return view('admin.profile.index', ['user' => $user]);
        }

        return redirect()->route('admin');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $loggedId = intval(Auth::id());

        $user = User::find($loggedId);

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
                return redirect()->route('profile', ['user' => $loggedId])->withInput()->withErrors($validator);
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
                return redirect()->route('profile', ['user' => $loggedId])->withErrors($validator)->withInput();
            }

            $user->save();

            return redirect()->route('profile')->with('warning', 'Informações alteradas com sucesso!');
        }

        return redirect()->route('profile');
    }
}
