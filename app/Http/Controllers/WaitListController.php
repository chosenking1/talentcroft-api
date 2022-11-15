<?php

namespace App\Http\Controllers;

use App\Models\WaitList;
use App\Services\SendPulseService;
use Illuminate\Http\Request;
use App\Http\Requests\WaitListRequest;


class WaitListController extends Controller
{

    public function index()
    {
        $wait_lists = WaitList::searchable();
        return view('wait_lists.index', compact('wait_lists'));
    }

    public function show(Request $request, WaitList $wait_list)
    {
        return view('wait_lists.show', compact('wait_list'));
    }

    public function create()
    {
        return view('wait_lists.create');
    }

    public function store(Request $request)
    {
        $this->validateData([
            'country' => 'required',
            'first_name' => 'required|string|min:3',
            'email' => 'string|email',
            'last_name' => 'required|string|min:3'
        ]);
        if (!(new SendPulseService)->validateEmail($request->email)) {
            return $this->respondWithErrors(['errors' => ['email' => ['Email is not valid']]]);
        }
        $waitlist = WaitList::firstOrCreate($request->only(['country', 'first_name', 'email', 'last_name']), $request->only(['country', 'first_name', 'email', 'last_name']));
        $mail = (new SendPulseService)->addContact($waitlist);
//        $waitlist->sendWelcomeMail();
        return $this->respondWithSuccess($waitlist);
    }

    public function edit(Request $request, WaitList $wait_list)
    {
        return view('wait_lists.edit', compact('wait_list'));
    }

    public function update($referrer_code)
    {
        $wait = WaitList::whereReferreCode($referrer_code)->firstOrFail();
        $wait->code_used = true;
        $wait->save();
        return $this->respondWithSuccess('Code Used');
    }

    public function destroy(Request $request, WaitList $wait_list)
    {
        $wait_list->delete();
        return redirect()->route('waitlists.index')->with('status', 'WaitList destroyed!');
    }
}
