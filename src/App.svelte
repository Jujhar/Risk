<script>
  import { onMount } from 'svelte';
  onMount(() => {
  })

  let turnCount = 3;
  let selectionTerritory = 'x';
  let selection = [];

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
    this.parentElement.style.display = 'none';
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
  <input type="radio" id="age1" name="age" value="30" checked>
  <label for="age1">vs computer &nbsp;</label>

  <input type="radio" id="age2" name="age" value="60">
  <label for="age2">vs human</label><br>

  <br>
  Starting unit pts. - remaining units
  <br>

  <input value="40" step="2" type="number" class="setup_input">
  <input style="background-color: #577ea1;" value="0" type="number" class="setup_input" disabled>
  <br>
  <br>
  <br>

  <input value="1" type="number" class="setup_input">&nbsp;&nbsp;Starting generals (10 pts)
  <br />

  <input value="1" type="number" class="setup_input">&nbsp;&nbsp;Starting infantry (5 pts)
  <br />

  <input value="25" type="number" class="setup_input">&nbsp;&nbsp;Starting units (1 pt)
  <br>
  <br>

  <button
    style="border: 1px solid goldenrod; padding: 2px;margin: 4px; width: 80px"
    on:click={doneSetup}>
    Done
  </button>
</div>
<!-- start screen -->


  <!-- <div style="position: absolute; left:200px; top:390px;">
  <img src="general.webp" width=100 />
  </div> -->

  <!-- turn count -->
  <div style="position: absolute; left:200px; top:690px;">
  <big><big style="font-size: 4em;">{turnCount}</big></big><br />
  <span on:click={deSelect} class="clickable">🚫 Deselect Units</span>
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
  max-width: 60px;
  text-align: center;
  font-size: 2em;
}
</style>
