<script>
  import { onMount } from 'svelte';
  onMount(() => {
  })

  let turnCount = 3;
  let selectionTerritory = 'x';
  let selection = [];

  let localPlay = false;
  let unitsSetStart = 40;
  let generalsStart = 1;
  let artilleryStart = 1;
  let infantryStart = 25;
  let generalsStartP2 = 1;
  let artilleryStartP2 = 1;
  let infantryStartP2 = 25;
  let setup_error = " "

  $: totalStartingUnits = unitsSetStart -
    infantryStart - (artilleryStart * 5)
    - (generalsStart * 10);

  $: totalStartingUnitsP2 = infantryStartP2 + (artilleryStartP2 * 5)
    + (generalsStartP2 * 10);

  function handleClick() {
    this.style.backgroundColor = 'green'
    selection.push(this);
	}

  function deSelect() {
    selection.forEach(item=>{
      item.style.backgroundColor = '';
    })
    selection = [];
  }

  function doneSetup() {
    if (totalStartingUnits == 0) {
      setup_error = ' ';
      this.parentElement.style.display = 'none';
    } else {
      if (totalStartingUnits > 0) {
        setup_error = 'Please use all available units';
      } else {
        setup_error = 'Please increase starting units';
      }
    }
  }

  function move() {
    if (turnCount > 0 && this.childNodes.length == 0) {
      selection.forEach(item=>{
        item.style.backgroundColor = '';
        this.appendChild(item);
      })
      turnCount--;
      selection = [];
    }
  }
</script>

<!--<svelte:window on:keydown={handleKeydown}/>-->

<html data-theme="retro" lang="en">
<body>

<!-- Start screen -->
<div id="setup_menu">
  <h1><b>Game Setup</b></h1>
  <br>
  <input bind:group={localPlay} value={false} type="radio" name="age" checked>
  <label for="age1">vs computer &nbsp;</label>

  <input bind:group={localPlay} name="scoops" value={true} type="radio">
  <label for="age2">vs local</label><br>

  <br>
  Starting units - remaining units pts.
  <br>



  <input bind:value={unitsSetStart} step="2" type="number" class="setup_input"
        min='0' >
  <input style="background-color: #577ea1;" bind:value={totalStartingUnits} type="number" class="setup_input" disabled>
  {#if setup_error != ' '}
    <span class="star_error">❊</span>
  {/if}

  <br>
  <br>
  <br>

  <input bind:value={generalsStart}
    type="number"
    class="setup_input"
    min='0'>&nbsp;&nbsp;Starting generals (10 pts)
  <br />

  <input bind:value={artilleryStart}
    type="number"
    class="setup_input"
    min='0'>&nbsp;&nbsp;Starting artillery (5 pts)
  <br />

  <input bind:value={infantryStart}
    type="number"
    class="setup_input"
    min='0'>&nbsp;&nbsp;Starting infantry (1 pt)
  <br>
  <br>

  {#if localPlay == true}
    <div class="P2box">
      <br />
      <b style="text-shadow: rgb(124 34 34 / 28%) 2px -1px 2px">
        Player 2 &nbsp;(<em>{totalStartingUnitsP2}</em> pts.)
      </b>
      <br />
      <br />
      <input bind:value={generalsStartP2}
        type="number"
        class="setup_input"
        min='0'><b>&nbsp;&nbsp;Starting generals - 10</b>
      <br />

      <input bind:value={artilleryStartP2}
        type="number"
        class="setup_input"
        min='0'><b>&nbsp;&nbsp;Starting artillery - 5</b>
      <br />

      <input bind:value={infantryStartP2}
        type="number"
        class="setup_input"
        min='0'><b>&nbsp;&nbsp;Starting infantry - 1</b>
      <br>
      <br>
    </div>
    <br />
    <br />
  {/if}

  <button
    style="border: 1px solid goldenrod; padding: 2px;margin: 4px; width: 80px"
    on:click={doneSetup}>
    Done
  </button>
  <br>
  <br>
  <em style="color:green; text-shadow: 2px -4px 10px #faffb5;">{setup_error}</em>
  <br>
</div>
<!-- start screen -->


  <!-- <div style="position: absolute; left:200px; top:390px;">
  <img src="general.webp" width=100 />
  </div> -->

  <!-- turn count -->
  <div style="position: absolute; left:200px; top:690px;">
  <big><big style="font-size: 4em;">{turnCount}</big></big><br />
  <span on:click={deSelect} class="clickable">🚫 Deselect Units</span>
  {localPlay}
  </div>

  <!-- Regions -->
  <!-- America -->
  <div id="Alaska" class="tile" style="position: absolute; height:200px; width:300px; left:0px; top:390px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>

  <div id="Northwest Territory" class="tile" style="position: absolute; height:200px; width:400px; left:300px; top:420px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
    <span style="position:sticky; left:2px; top:3px;" on:click={handleClick}>
      <img src="general.webp" width=100 />
    </span>
    <span style="position: sticky; left:4px; top:1px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/artillery.png" width=100 />
    </span>
    <span style="position: sticky; left:4px; top:2px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/unit.png" width=200 />
    </span>
    <span style="position: sticky; left:4px; top:2px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/unit.png" width=200 />
    </span>
    <span style="position: sticky; left:4px; top:2px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/unit.png" width=200 />
    </span>
    <span style="position: sticky; left:4px; top:2px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/unit.png" width=200 />
    </span>
    <span style="position: sticky; left:4px; top:2px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/unit.png" width=200 />
    </span>
    <span style="position: sticky; left:4px; top:2px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/unit.png" width=200 />
    </span>
    <span style="position: sticky; left:4px; top:2px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/unit.png" width=200 />
    </span>
  </div>

  <div id="Alberta" class="tile"
      style="position: absolute; height:170px; width:310px; left:320px; top:630px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>

  <div id="Ontario" class="tile"
      style="position: absolute; height:190px; width:250px; left:630px; top:630px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>

  <div id="Quebec" class="tile"
      style="position: absolute; height:240px; width:250px; left:880px; top:630px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>

  <div id="Western United States" class="tile"
      style="position: absolute; height:240px; width:250px; left:380px; top:810px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>

  <div id="Eastern United States" class="tile"
      style="position: absolute; height:280px; width:250px; left:680px; top:840px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>

  <div id="Central America" class="tile"
      style="position: absolute; height:180px; width:150px; left:410px; top:1090px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>

  <div id="Greenland" class="tile"
      style="position: absolute; height:380px; width:450px; left:1010px; top:210px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>
  <!-- america -->


</body>
</html>

<style>
body {
  height: 2400px;
  width: 3300px;
  background-image: url(board.jpg);
  background-size: auto;
}

.tile {
  display: flex;
  align-items: center;
  justify-content: center;
}

.clickable {
  cursor: pointer;
}

#setup_menu {
  position: fixed;
  width: 800px;
  top: 10%;
  right: 10%;
  background-color: #65c365;
  padding: 50px;
  text-align: center;
  z-index: 20;
}

.setup_input {
  background-color: aliceblue;
  min-width: 37px;
  max-width: 64px;
  text-align: center;
  font-size: 2em;
}

.star_error {
  color: #faffb5;
  font-size: 2em;
  font-weight: bolder;
}

.P2box {
  background-color: #ffcf95;
  margin-left:-50px;
  margin-right:-50px;
  opacity: 0.6;
  color: cornsilk;
  text-shadow: rgb(124 34 34 / 8%) 2px -1px 2px;
  margin-bottom: -21px;
}

.P2box input {
  color: #44002eb5;
}
</style>
