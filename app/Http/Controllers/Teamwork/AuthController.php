<?php

namespace App\Http\Controllers\Teamwork;

use App\Helper\ResponseContent;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;

class AuthController extends Controller
{

    use ApiResponseHelpers;

    public function validateToken($token){
        $invite = Teamwork::getInviteFromAcceptToken($token);

        if (!$invite) {
            return $this->respondNotFound('Invitation not found');
        }
        return $this->respondWithSuccess(['message' => 'Invitation found']);
    }

    /**
     * Accept the given invite.
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptInvite($token)
    {
        $invite = Teamwork::getInviteFromAcceptToken($token);
        if (!$invite) {
            return $this->respondNotFound('Invitation not found');
        }

        if (auth()->check()) {
            Teamwork::acceptInvite($invite);

            return $this->respondWithSuccess(ResponseContent::getResponse(
                $invite,
                'Invitation Successful',
                'The invitation successfully'
            ));
        } else {
            return $this->respondForbidden('You must be logged in to accept an invitation');
        }
    }
}
