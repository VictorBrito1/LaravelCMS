<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * SettingController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $settings = [];

        $dbSettings = Setting::all();

        foreach ($dbSettings as $dbSetting) {
            $settings[$dbSetting['name']] = $dbSetting['content'];
        }

        return view('admin.settings.index', ['settings' => $settings]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $data = $request->only(['title', 'subtitle', 'email', 'bgcolor', 'textcolor']);

        $validator = $this->validator($data);

        if ($validator->fails()) {
            return redirect()->route('settings')->withErrors($validator);
        }

        foreach ($data as $item => $value) {
            Setting::where('name', $item)->update(['content' => $value]);
        }

        return redirect()->route('settings')->with('warning', 'Informações alteradas com sucesso');
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'title' => ['string', 'max:100'],
            'subtitle' => ['string', 'max:100'],
            'email' => ['string', 'email'],
            'bgcolor' => ['string', 'regex:/#[A-Z0-9]{6}/i'],
            'textcolor' => ['string', 'regex:/#[A-Z0-9]{6}/i']
        ]);
    }
}
