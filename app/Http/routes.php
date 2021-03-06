<?php
use Illuminate\Http\Request;
Route::auth();

Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
    /*var_dump($query->sql);
    var_dump($query->bindings);
    var_dump($query->time);*/
});

// API
Route::group(['prefix' => 'api/'], function () {
	Route::resource('campaign', 'CampaignController', ['except' => ['store', 'update', 'edit']]);
	Route::resource('campaign_user', 'CampaignUserController', ['except' => ['store', 'update', 'edit']]);
	Route::resource('chronicle', 'ChronicleController', ['except' => ['store', 'update', 'edit']]);
	Route::resource('character', 'CharacterController', ['except' => ['store', 'update', 'edit']]);
	Route::resource('user', "UserController", ['except' => ['store', 'update', 'edit']]);

	Route::get('campaigns_per_page/{page_nr}', 'CampaignController@campaigns_per_page');
	Route::get('identify_campaign/{campaign_id}', 'CampaignController@identify');
	Route::get('mytoken', function(){
		$result = array( 'token' => csrf_token() );
		return response()->json( $result );
	});
	Route::get('username_and_id', 'UserController@info');
	Route::get('campaign/{campaign_id}/page/{page_nr}', 'ChronicleController@chronicles_per_page');
	Route::get("fetch_portraits", 'PortraitController@portraits');
});

// API: Signed in users
Route::group(['prefix' => 'api/', 'middleware' => 'auth'], function () {
	//Route::resource('campaigns', 'CampaignController');
	Route::resource('campaign', 'CampaignController', ['only' => ['store', 'update', 'edit', 'destroy']]);
	Route::resource('rpg_chat', 'RpgChatController',  ['only' => ['store', 'update', 'edit', 'destroy']]);
	Route::resource('chronicle', 'chronicleController', ['only' => ['store', 'update', 'edit', 'destroy']]);
	Route::resource('campaign_user', 'CampaignUserController', ['only' => ['store', 'update', 'edit', 'destroy']]);
	Route::resource('user', "UserController", ['only' => ['store', 'update', 'edit', 'destroy']]);
	Route::resource('character', 'CharacterController', ['only' => ['store', 'update', 'edit', 'destroy']]);

	Route::get('activate_campaign/{campaign_id}', 'CampaignController@activate');
	Route::get('camp_applications_setup/{campaign_id}', 'CampaignController@campaignApplication_setup');
	Route::get('apply_to_campaign/{campaign_id}', 'CampaignController@apply_to_campaign' );
	Route::get('character/{id}/status/{status}', 'CharacterController@set_status');
	Route::get('rpg_update/{campaign_id}/newest/{chat_id}/{chronicle_id}', 'RpgController@rpg_update');
	Route::get('setup_rpg/{campaign_id}', 'RpgController@rpg_setup');
	Route::post('rpg/role_dice', 'RpgChatController@storeDiceRole');
});

// Route::get('/redirect', 'SocialAuthController@redirect');
//Route::get('/callback/facebook', 'SocialAuthController@callback');

Route::get('/facebook/callback', 'SocialAuthController@fb_callback');
Route::get('/callback/{provider}', 'SocialAuthController@callback');
Route::get('redirect/{provider}', 'SocialAuthController@redirect');
// Route::get('callback/', function(Request $request)
// {
// 	echo $request->query('code');;
// 	//echo Route::input('code');
//     return Redirect::to('/callback/facebook');
// });

Route::get('index', function() {
	return view('index');
});

Route::get('/', function() {
	return view('index');
});

