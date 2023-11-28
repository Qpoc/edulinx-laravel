<?php

namespace App\Http\Controllers\Teamwork;

use Exception;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use App\Helper\ResponseContent;
use App\Request\Team\StoreRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;

class TeamController extends Controller
{
    use ApiResponseHelpers;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function paginate(){
        return config('teamwork.team_model')::where('owner_id', Auth::id())->paginate(config('pagination.per_page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {

        DB::beginTransaction();
        try {
            $teamModel = config('teamwork.team_model');

            $team = $teamModel::create([
                'name' => $request->name,
                'owner_id' => $request->user()->getKey(),
            ]);
            $request->user()->attachTeam($team);

            foreach ($request->invitations as $invitation) {
                if (!Teamwork::hasPendingInvite($invitation, $team)) {
                    Teamwork::inviteToTeam($invitation, $team, function ($invite) {
                        Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
                            $m->to($invite->email)->subject('Invitation to join team ' . $invite->team->name);
                        });
                    });
                }
            }
            DB::commit();
            return $this->respondWithSuccess(ResponseContent::getResponse(
                $team,
                'Course Creation: Successful',
                'The course has been created successfully'
            ));
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseContent::getServerError();
        }
    }

    /**
     * Switch to the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function switchTeam($id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);
        try {
            auth()->user()->switchTeam($team);
        } catch (UserNotInTeamException $e) {
            abort(403);
        }

        return redirect(route('teams.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        if (!auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        return view('teamwork.edit')->withTeam($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $teamModel = config('teamwork.team_model');

        $team = $teamModel::findOrFail($id);
        $team->name = $request->name;
        $team->save();

        return redirect(route('teams.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teamModel = config('teamwork.team_model');

        $team = $teamModel::findOrFail($id);
        if (!auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $team->delete();

        $userModel = config('teamwork.user_model');
        $userModel::where('current_team_id', $id)
            ->update(['current_team_id' => null]);

        return redirect(route('teams.index'));
    }
}
