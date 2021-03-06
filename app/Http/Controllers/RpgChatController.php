<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sagohamnen\Rpg_chat\Rpg_chat_BL;
use Sagohamnen\Chronicle\Chronicle_BL;
use Sagohamnen\Last_read\Last_read_BL;

use App\Http\Requests;
use Auth;

class RpgChatController extends ApiController
{
	protected $BL;
	protected $chronicle_BL;
    protected $last_read_BL;

	public function __construct()
	{
		$this->BL = new Rpg_chat_BL();
		$this->chronicle_BL = new chronicle_BL();
        $this->last_read_BL = new last_read_BL();
	}

	public function index(){
		echo "inne i index";
	}

    // Show latest chats
    public function show($campaign_id) {
    	try {
    		$result = $this->BL->get_latest($campaign_id);
    		return $this->respond(array('chats' => $result) );
    	} catch(Exception $e)
    	{
    		return $this->respondInternalError($e);
    	}
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
            'text'   		=> 'required|min:1|max:'.config('sh.chat_max_length'),
            'type'          => 'required|min:1|max:2',
            'campaign_id'	=> 'required|min:1'
        ]);

        try {
    		return $this->respond( $this->BL->store($request));
    	} catch(Exception $e)
    	{
            echo "KOm hit för fel";
    		return $this->respondInternalError($e);
    	}
    }

    public function storeDiceRole(Request $request)
    {
        // If this fails, it throws an 422 exception if.
        // ajax, but if unit test is gives 302.
        $this->validate($request, [
            'campaign_id'        => 'required|numeric|min:1',
            'dice_nr'            => 'required|numeric|min:1|max:100',
            'dice_type'          => 'required|numeric|in:' . implode(',', config('sh.dice_types') ),
            'dice_mod'           => 'required|numeric|min:-1000, max:10000',
            'dice_ob'            => 'boolean',
            'dice_description'   => 'max:250'
        ]);

        try {
            $result = $this->BL->store_dices($request);

            if ($result === false) return $this->respondNotAuthorized();
            //return $this->respond( $result );
            return $this->respond( $result );
        } catch(Exception $e)
        {
            return $this->respondInternalError($e);
        }
    }
}
