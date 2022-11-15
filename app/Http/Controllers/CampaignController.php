<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CampaignRequest;
use App\Models\Campaign; 

class CampaignController extends Controller
{
    public function index()
    {
        return Campaign::all();
    }

    public function show($campaign_id)
    {
        return Campaign::find($campaign_id);
    }

    public function store(CampaignRequest $request)
    {

        $data = $request->validate([
            'title' => 'required',
            'messages' => 'required',
            'files' => 'required',
        ]);

        return Campaign::create($request->all());
    }

    public function update(Request $request, $campaign_id)
    {
        $campaign = Campaign::find($campaign_id);
        $campaign->update($request->all());
        return $campaign;
    }

    public function destroy($campaign_id)
    {
        return Campaign::destroy($campaign_id);
    }

    public function search($title)
    {
        return Campaign::where('title', 'like', '%'.$title. '%')->get();
    }
}
