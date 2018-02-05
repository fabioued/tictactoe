<?php

namespace App\Http\Controllers;

use App\Events\NewGame;
use App\Game;
use App\Turn;
use App\User;
use Response;
use Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user       = $request->user();
        $usersQuery = User::where('id','!=',$user->id);
        if($request->has('search')){
            $usersQuery->where('name', 'like',"%{$request->search}%");
        }

        $users       = $usersQuery->paginate(5);

        $bestPlayers = User::orderBy('score','desc')->take(4)->get();

        return view('home',compact('user','users','bestPlayers'));
    }
    public function newGame(Request $request,$username){

        $user       = $request->user();
        $player_id  = $request->player_id;
        $game_id    = Game::insertGetId([]);
        for($i = 1; $i <= 9; $i++){

            Turn::insert([
                "game_id"   => $game_id,
                "id"        => $i,
                "player_id" => $i % 2 ? $user->id : $player_id,
                "type"      => $i % 2 ? 'x' : 'o',
            ]);
        }
        //dd($user->name);
        $event= event(new NewGame($player_id, $game_id, $user->name));
        //dd($event);
        //wait
        //Session::put('invite', 'wait');
//        $invite_response = Session::get('invite');
//
//        dd($invite_response);
//        return redirect("/board/{$game_id}");
          return redirect()->action('HomeController@invite',['game_id'=>$game_id]);
    }

    public function invite(Request $request,$game_id){

        //Session::put('invite', 'Yes');
        $path     = $request->path();
        $response = str_after($path,'response=');
        dd($response);
        if($response == 'invite-ok'){
            return redirect("/board/{$game_id}");
        }
        elseif ($response == 'invite-refused'){
            return redirect()->back();
        }

    }
}
