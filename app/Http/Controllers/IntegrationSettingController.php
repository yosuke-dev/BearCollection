<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Models\IntegrationSetting;
use Service\UseCases\GetIntegrationSetting;

class IntegrationSettingController extends Controller
{
    /**
     * Index function
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $input = new GetIntegrationSetting;
        $data = $input->execute();
        return Inertia::render('IntegrationSettings/Index', ['integration_settings' => $data['integration_settings']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'integration_service_id' => ['required'],
        ])->validate();

        IntegrationSetting::create($request->all());

        return redirect()->back()
                    ->with('message', 'IntegrationSetting Created Successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'integration_service_id' => ['required'],
        ])->validate();

        if ($request->has('id')) {
            IntegrationSetting::find($request->input('id'))->update($request->all());
            return redirect()->back()
                    ->with('message', 'IntegrationSetting Updated Successfully.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            IntegrationSetting::find($request->input('id'))->delete();
            return redirect()->back();
        }
    }
}
