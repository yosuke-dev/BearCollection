<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\IntegrationRedmineSetting;
use Service\UseCases\GetIntegrationRedmineSetting;
use Service\UseCases\RegisterProjectVersionRedmine;

class IntegrationRedmineSettingController extends Controller
{
    /**
     * Index function
     *
     * @return \Inertia\Response
     */
    public function show($integration_setting_id)
    {
        $input = new GetIntegrationRedmineSetting;
        $data = $input->execute($integration_setting_id);
        return $data['integration_redmine_setting'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'integration_setting_id' => ['required'],
            'api_key' => ['required'],
            'url' => ['required'],
        ])->validate();

        IntegrationRedmineSetting::create($request->all());
        $register = new RegisterProjectVersionRedmine($request['url'], $request['api_key']);
        $register->execute($request['integration_setting_id']);

        return ['message' => 'IntegrationRedmineSetting Created Successfully.'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'integration_setting_id' => ['required'],
            'api_key' => ['required'],
            'url' => ['required'],
        ])->validate();

        if ($request->has('id')) {
            $setting = IntegrationRedmineSetting::find($request->input('id'));
            $setting->update($request->all());
            $register = new RegisterProjectVersionRedmine($request['url'], $request['api_key']);
            $register->execute($request['integration_setting_id']);
            return ['message' => 'IntegrationRedmineSetting Updated Successfully.'];
        }
    }
}
