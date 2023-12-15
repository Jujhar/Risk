<script>
  import { onMount } from 'svelte';

  onMount(() => {
  })

  let key;
  let keyCode;
  let state = 'none';
  let initial = 'Press space to roll and then use arrow keys';
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

      //                                                         ██████
      //                                                     ████░░░░░░████
      //                                                   ██░░░░░░░░░░    ██
      //                                                 ██░░░░██████████░░  ██
      //                                                 ██░░░░██      ██░░  ██
      // ████████████████████████████████████████████████░░░░██          ██░░░░██
      // ██░░░░░░░░░░░░░░░░░░░░░░░░░left░░░░░░░░░░░░░░░░░░░░░██    <     ██░░░░██
      //   ██░░░░██░░░░██░░░░████████████████████████████░░░░██          ██░░░░██
      //     ████  ████  ████                            ██░░░░██      ██░░░░██
      //                                                 ██░░░░██████████░░░░██
      //                                                   ██░░░░░░░░░░░░░░██
      //                                                     ████░░░░░░████
      //                                                         ██████

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

{#if rollActive}
  <div style="position: absolute;right:100px;top:100px;">
  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
  </div>
{/if}

<div style="padding-top: 10%;padding-bottom: 10%;padding-left: 10%;font-family: inherit;">
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

<div class="text-center text-white" style="padding-top: 1%;padding-bottom: 1%;padding-left: 32%;">
  {result}
<div>

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
  background-color: #ac6b29;
  font-family: monospace;
}
.sq {
  border: 2px solid #A6C7BE;width:85px;
  margin:4px;
  text-align: center;
}
#computer {
  position: absolute;
  height: 300px;
  top: 96px;
  background-color: aquamarine;
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
</style>
