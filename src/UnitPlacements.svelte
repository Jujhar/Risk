<script>
  import { onMount } from 'svelte';
  import { fade } from 'svelte/transition';

  let computerRoll = 1;
  let computerRolled = false;
  onMount(() => {
    computerRoll = Math.floor(Math.random() * 4) + 1;
    computerRolled = true;
  })

  let showScreen = 1;

  let key;
  let keyCode;
  let state = 'none';
  let initial = 'Press space to roll';
  let result = initial;
  let rollActive = false;
  let boxRight = 4;
  let boxLeft = 5;
  let boxUp = 6;
  let boxDown = 3;

  // For initialization of dice positions
  function getRandomInt(max) {
    return Math.floor(Math.random() * max);
  }

	function handleKeydown(event) {
		key = event.key;
		keyCode = event.keyCode;

    if (keyCode == 32 && result == initial){
      state = 'none';
      document.getElementById('up').classList.remove('selected')
      document.getElementById('down').classList.remove('selected')
      document.getElementById('left').classList.remove('selected')
      document.getElementById('right').classList.remove('selected')
      document.getElementById('none').classList.remove('selected')
      document.getElementById('none').innerHTML = '&nbsp;';

      rollActive = true;
      let num = getRandomInt(7);

      // edge case
      if (num == 0) {
        num = 6;
      }

      boxLeft = num

      if ((num - 1) == 0){
        num = 6;
      }
      num = num - 1;
      boxUp = num;

      if ((num - 1) == 0){
        num = 6;
      }
      num = num - 1;
      boxRight = num;

      if ((num - 1) == 0){
        num = 6;
      }
      num = num - 1;
      boxDown = num;


      setTimeout(function(){ roll() }, 450);
            setTimeout(function(){ roll() }, 750);
            setTimeout(function(){ roll() }, 1050);
            setTimeout(function(){ roll() }, 1350);
            setTimeout(function(){ roll() }, 1450);
          //  setTimeout(function(){ roll() }, 1550);
          //  setTimeout(function(){ roll() }, 1600);

      setTimeout(function(){
        rollActive = false;

        if (state == 'none') {
          document.getElementById('none').innerHTML = "1";
          result = 'You rolled 1';
          die = 1;
          document.getElementById(state).classList.add('selected')
        }

        else {
          document.getElementById(state).classList.add('selected')
          result = 'You rolled ' + document.getElementById(state).childNodes[0].innerHTML;
          die = parseInt(document.getElementById(state).childNodes[0].innerHTML);
        }

      }, 1600);
    }


    // show results
    if (keyCode == 32 && result != initial) {
      document.getElementById('dice_roll').style.display = 'none';
      document.getElementById('dice_roll_p2').style.display = 'inherit';
    }

    // close results
    if (keyCode == 32 && document.getElementById('dice_roll_p2').style.display == 'inherit') {
      showScreen--;
      if (showScreen < 0){
        document.getElementById('dice_roll_p2').style.display = 'none';
      }
    }

    // set cursor and set game pieces
    if (keyCode == 32 && computerRolled && result != initial) {
      if (parseInt(result.slice(result.length - 1)) >= computerRoll) {

        /* your turn */
        document.body.style.cursor = `url('pieces/unit-c.png'), auto`;
      } else {

        /* you lost */
        document.body.style.cursor = `url('pieces/unit-c.png'), auto`;
      }

      // todo place units
      // todo minus units remaining
    }

    // Choose key - ← > ^ ↓
      if (keyCode == 39){
        state = 'right';
        document.getElementById("right").style.backgroundColor = "black";
        setTimeout(function(){ document.getElementById("right").style.backgroundColor = ""; }, 450);
      }

      if (keyCode == 37){
        state = 'left';
        document.getElementById("left").style.backgroundColor = "black";
        setTimeout(function(){ document.getElementById("left").style.backgroundColor = ""; }, 450);
      }

      if (keyCode == 38){
        state = 'up';
        document.getElementById("up").style.backgroundColor = "black";
        setTimeout(function(){ document.getElementById("up").style.backgroundColor = ""; }, 450);
      }

      if (keyCode == 40){
        state = 'down';
        document.getElementById("down").style.backgroundColor = "black";
        setTimeout(function(){ document.getElementById("down").style.backgroundColor = ""; }, 450);
      }
    // choose key

	}

  function roll(){
    boxRight = boxRight - 1;
    if (boxRight == 0) {
      boxRight = 6;
    }

    boxLeft = boxLeft - 1;
    if (boxLeft == 0) {
      boxLeft = 6;
    }

    boxUp = boxUp - 1;
    if (boxUp == 0) {
      boxUp = 6;
    }

    boxDown = boxDown - 1;
    if (boxDown == 0) {
      boxDown = 6;
    }
  }

  export let die;
  export let computer;
</script>

<svelte:window on:keydown={handleKeydown}/>

<html data-theme="retro" lang="en">
<body>

{#if computer}
<div id="computer">
<br />
<br />
{#if parseInt(computerRoll) > parseInt(result.slice(result.length - 1))}
  <h2>The computer is rolling..</h2>
{:else}
  <h2>The computer rolled {computerRoll}.</h2>
{/if}
<br />
<br />
{#if parseInt(computerRoll) > parseInt(result.slice(result.length - 1))}
  <h2 class="fade-in-image">The computer beat you by rolling {computerRoll}.</h2>
{:else if parseInt(computerRoll) == parseInt(result.slice(result.length - 1))}
  <h2 class="fade-in-image">Draw.</h2>
{:else}
  <h2 class="fade-in-image">You won by rolling {result.slice(result.length - 1)}.</h2>
{/if}

<br />
<br />
<br />
{#if computerRoll > parseInt(result.slice(result.length - 1))}
  <h2 class="fade-in-image-2">He will place the first piece</h2>
{:else if parseInt(result.slice(result.length - 1)) == computerRoll}
  <h2 class="fade-in-image-2">You place the first piece.</h2>
{:else}
  <h2 class="fade-in-image-2">You will place the first piece.</h2>
{/if}

</div>
{/if}

{#if rollActive}
  <div style="position: absolute;right:100px;top:100px;">
  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
  </div>
{/if}

<div style="padding-top: 10%;padding-bottom: 10%;padding-left: 10%;">
    <div class="flex text-6xl">
        <div class="w-1/3">
        </div>
        <div class="w-1/3">
        <div id="up">
        <div class="sq">
          {boxUp}
        </div>
        </div>
        </div>
        <div class="w-1/3">

        </div>
    </div>

    <div class="flex text-6xl">
        <div class="w-1/3">
        <div id="left">
        <div class="sq">
          {boxRight}
        </div>
        </div>
        </div>
        <div class="w-1/3">
        <div id="none" class="sq">
        &nbsp;
        </div>
        </div>
        <div class="w-1/3">
        <div id="right">
        <div class="sq">
          {boxLeft}
        </div>
        </div>
        </div>
    </div>

    <div class="flex text-6xl">
        <div class="w-1/3">

        </div>
        <div class="w-1/3">
        <div id="down">
        <div class="sq">
          {boxDown}
        </div>
        </div>
        </div>
        <div class="w-1/3">

        </div>
    </div>
</div>

</body>
</html>

<style>
@font-face {
  font-family: 'german';
  font-style: normal;
  font-weight: 400;
  src: local('german'), local('german'), url(18thCtrKurTxt.ttf) format('tff');
}

body {
  background-color: #be9706;
}
.sq {
  border: 2px solid #A6C7BE;width:85px;
  margin:4px;
  text-align: center;
}
#computer {
  color: white;
  position: absolute;
  height: 300px;
  top: 96px;
  background-color: #be9706;
  width: 700px;
}
:global(.selected) {
  font-family: german, monospace;
  color:  #fcffe2;
  font-weight: bolder;
}

/* Loader */
.lds-ring {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 64px;
  height: 64px;
  margin: 8px;
  border: 8px solid #fff;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #fff transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
/* loader */

/* You won result text */
.fade-in-image {
  animation: fadeIn 5s;
  -webkit-animation: fadeIn 5s;
  -moz-animation: fadeIn 5s;
  -o-animation: fadeIn 5s;
  -ms-animation: fadeIn 5s;
}
@keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@-moz-keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@-webkit-keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@-o-keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@-ms-keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
.fade-in-image-2 {
  animation: fadeIn 7s;
  -webkit-animation: fadeIn 7s;
  -moz-animation: fadeIn 7s;
  -o-animation: fadeIn 7s;
  -ms-animation: fadeIn 7s;
}
@keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@-moz-keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@-webkit-keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@-o-keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@-ms-keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}
/* you won result */

</style>
