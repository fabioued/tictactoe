@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="game profile-info">
                <div class="profile-username">
                    {{$user->id == $nextTurn->player_id ? " You are next!" : "Waiting for Player 2..."}}</div>
                     <div class="profile-score">Your Score: {{$user->score}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">=
           <div class="tic-tac-toe">
               @foreach($locations as $index => $location)
                   <input type="radio"
                          class="player-{{$location["checked"] ? $location["type"] : $playerType}} {{$location["class"]}}"
                          id="block-{{$index}}"
                          value="{{$index}}"
                          {{$location["checked"] ? "checked" : "" }}
                          {{$user->id != $nextTurn->player_id ? "disabled" : ""}}
                   />
                   <label for="block-{{$index}}"></label>
               @endforeach
           </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <a id="exit-button" href="/home" class="btn btn-lg btn-primary" style="display: none;">Exit Game</a>
        </div>
    </div>
</div>
{{ csrf_field() }}
@endsection

@section('scripts')
    <script language="JavaScript">
        function checkResult(){
            var win = false;
            // Top Row
            if(
                $('#block-1.player-{{$playerType}}:checked').length &&
                $('#block-2.player-{{$playerType}}:checked').length &&
                $('#block-3.player-{{$playerType}}:checked').length
            ){
                win = true;
            }
            // Middle Row
            else if(
                $('#block-4.player-{{$playerType}}:checked').length &&
                $('#block-5.player-{{$playerType}}:checked').length &&
                $('#block-6.player-{{$playerType}}:checked').length
            ){
                win = true;
            }

            // Bottom Row
            else if(
                $('#block-7.player-{{$playerType}}:checked').length &&
                $('#block-8.player-{{$playerType}}:checked').length &&
                $('#block-9.player-{{$playerType}}:checked').length
            ){
                win = true;
            }
            //Left Column
            else if(
                $('#block-1.player-{{$playerType}}:checked').length &&
                $('#block-4.player-{{$playerType}}:checked').length &&
                $('#block-7.player-{{$playerType}}:checked').length
            ){
                win = true;
            }
            //Center Column
            else if(
                $('#block-2.player-{{$playerType}}:checked').length &&
                $('#block-5.player-{{$playerType}}:checked').length &&
                $('#block-8.player-{{$playerType}}:checked').length
            ){
                win = true;
            }
            //Right Column
            else if(
                $('#block-3.player-{{$playerType}}:checked').length &&
                $('#block-6.player-{{$playerType}}:checked').length &&
                $('#block-9.player-{{$playerType}}:checked').length
            ){
                win = true;
            }
            //Diagonal  Left to Right
            else if(
                $('#block-1.player-{{$playerType}}:checked').length &&
                $('#block-5.player-{{$playerType}}:checked').length &&
                $('#block-9.player-{{$playerType}}:checked').length
            ){
                win = true;
            }
            //Diagonal  Right to Left
            else if(
                $('#block-3.player-{{$playerType}}:checked').length &&
                $('#block-5.player-{{$playerType}}:checked').length &&
                $('#block-7.player-{{$playerType}}:checked').length
            ){
                win = true;
            }

            if(!win){
                if($('input[type=radio]:checked').length == 9){
                    return 'tie';
                }
            }
            else{
                return 'win';
            }

            return false;
        }
        var pusher = new Pusher('810c76b183f790d8898a', {
            cluster: 'eu',
            encrypted: true
        });
        var gamePlayChannel = pusher.subscribe('game-channel-{{$id}}-{{$opponentId}}');
        var gameOverChannel = pusher.subscribe('game-over-channel-{{$id}}-{{$opponentId}}');

        gamePlayChannel.bind('App\\Events\\Play', function(data){
            $('#block-' + data.location).removeClass('player-{{$playerType}}').addClass('player-' + data.type);
            $('#block-' + data.location).attr('checked', true);
            $('input[type=radio]').removeAttr('disabled');
            $('.profile-username').html('You are next!');
        });

        gameOverChannel.bind('App\\Events\\GameOver', function(data){
            $('#block-' + data.location).removeClass('player-{{$playerType}}').addClass('player-' + data.type);
            $('#block-' + data.location).attr('checked', true);
            if(data.result == 'win'){
                $('.profile-username').html('You Loose!');
            }
            else{
                $('.profile-username').html('Its a tie!');
            }
            $('#exit-button').show();
        });

        $(document).ready(function () {
            $('input[type=radio]').on('click', function(){
                $('input[type=radio]').attr('disabled', true);
                var result = checkResult();
                if(!result){
                    $('.profile-username').html('Waiting on player 2...');
                    $.ajax({
                        url: '/play/{{$nextTurn->game_id}}',
                        method: 'POST',
                        data: {
                            location: $(this).val(),
                            _token: $('input[name=_token]').val()
                        },
                        success: function(data){
                            //
                        }
                    });
                }
                else{
                    if(result == 'win'){
                        $('.profile-username').html('You Win!');
                    }
                    else{
                        $('.profile-username').html('Its a tie!');
                    }
                    $('#exit-button').show();
                    $.ajax({
                        url: '/game-over/{{$nextTurn->game_id}}',
                        method: 'POST',
                        data: {
                            location: $(this).val(),
                            result: result,
                            _token: $('input[name=_token]').val()
                        },
                        success: function(data){
                            //
                        }
                    });
                }
            });
        });
    </script>
@endsection