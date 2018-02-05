@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="profile-picture">
                        <img class="img-circle img-responsive" src="https://www.gravatar.com/avatar/{{md5($user->email)}}?d=retro&s=200">
                    </div>
                    <div class="profile-info">
                        <div class="profile-username">{{$user->name}}</div>
                        <div class="profile-score">Your Score:{{$user->score}}</div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    @if($bestPlayers->count())
                        <h2 class="text-center">Best Players</h2>
                        <div class="list-group">
                            @foreach($bestPlayers as $player)
                                <a class="list-group-item clearfix">
                                    <img class="img-circle img-responsive" src="https://www.gravatar.com/avatar/{{md5($user->email)}}?d=retro">
                                    <span class="player-info">
                                        Name:  {{$player->name}}<br>
                                        Score: <small>{{$player->score}}</small>
                                    </span>
                                    <form action="{{route('newGame',['username'=>str_slug($player->name)])}}" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="player_id" value="{{$player->id}}">
                                        <button type="submit" class="btn btn-lg btn-blue pull-right">Play</button>
                                    </form>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default content">
                <div class="panel-body">
                    <div class="text-right">
                        <form class="form-inline" method="get">
                            <label> Search: </label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="search a user here">
                                <span class="input-group-btn">
                                    <button class="btn bg-primary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>
                    @if($users->count())
                        <div class="list-group">
                            @foreach($users as $player)
                                <a class="list-group-item clearfix">
                                    <img class="img-circle img-responsive" src="https://www.gravatar.com/avatar/{{md5($user->email)}}?d=retro">
                                    <span class="player-info">
                                        Name:  {{$player->name}}<br>
                                        Email: {{$player->email}}<br>
                                        Score: <small>{{$player->score}}</small>
                                    </span>
                                    <form action="{{route('newGame',['username'=>str_slug($player->name)])}}" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="player_id" value="{{$player->id}}">
                                        <button type="submit" class="btn btn-lg btn-blue pull-right">Play</button>
                                    </form>
                                </a>
                            @endforeach
                            <div class="col-lg-offset-1">
                                {{$users->render()}}
                            </div>
                        </div>
                        @else
                        <h2 class="text-center">There is no user right now!!!</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="new-game-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> New Game</h4>
            </div>
            <div class="modal-body">
                <p class="text-center"><span id="from"></span> invited you to a game</p>
            </div>
            <div class="modal-footer">
                {{--<button class="btn btn-danger"  id="close-button"    type="button" data-dismiss="modal">Close</button>--}}
                <button class="btn btn-primary" id="play-button"     type="button">Play</button>
            </div>
        </div>
    </div>
</div>
<form id="new-game-form" method="get">
    {{csrf_field()}}
</form>
<ul class="bg-bubbles">
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</ul>
@endsection

@section('scripts')
    <script language="JavaScript">
        var pusher = new Pusher('810c76b183f790d8898a', {
            cluster: 'eu',
            encrypted: true
        });
        var gamePlayChannel = pusher.subscribe('new-game-channel');

        gamePlayChannel.bind('App\\Events\\NewGame',function(data){

            //console.log(data);

            if(data.destinationUserId == '{{ $user->id }}'){

                $('#from').html(data.from);
                $('#new-game-form').attr('action', '/board/' + data.gameId);
                $('#new-game-modal').modal('show');
            }
        });
        $('#play-button').on('click', function(){
//            var action      = document.getElementById("new-game-form").action;
//            var ok_action   = action + '/response=invite-ok';
//            $('#new-game-form').attr('action', ok_action);
//            //alert(ok_action);
            $('#new-game-form').submit();
        });
//        $('#close-button').on('click', function(){
//            var action      = document.getElementById("new-game-form").action;
//            var new_action  = action + '/response=invite-refused';
//            $('#new-game-form').attr('action', new_action);
//            //alert(new_action);
//            $('#new-game-form').submit();
//        });
    </script>
@endsection