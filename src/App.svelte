<script>
  import Dice from './Dice.svelte';
  import DiceComputer from './DiceComputer.svelte';
  import { onMount } from 'svelte';
  onMount(() => {
    window.scrollTo(400,1000);
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
    if (selectionTerritory == 'x' || selectionTerritory == this.parentElement.id) {
      selectionTerritory = this.parentElement.id
      this.style.backgroundColor = 'green'
      selection.push(this);
    }
	}

  function deSelect() {
    selection.forEach(item=>{
      item.style.backgroundColor = '';
    })
    selection = [];
    selectionTerritory = 'x';
  }

  function doneSetup() {
    if (totalStartingUnits == 0) {
      setup_error = ' ';
      this.parentElement.style.display = 'none';
      document.getElementById('starting_turn').style.display = 'inherit';
    } else {
      if (totalStartingUnits > 0) {
        setup_error = 'Please use all available units';
      } else {
        setup_error = 'Please increase starting units';
      }
    }
  }

  function turnRole() {
    this.parentElement.style.display = 'none';
    document.getElementById('dice_roll').style.display = 'inherit';
  }

  function move() {
      if (turnCount > 0
        && selection.length > 0
        && (this.childNodes.length == 0 || (selectionTerritory != this.id || selectionTerritory == 'x'))) {
      selection.forEach(item=>{
        item.style.backgroundColor = '';
        this.appendChild(item);
      })
      turnCount--;
      selection = [];
      selectionTerritory = 'x';
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
    class="button"
    on:click={doneSetup}>
    Done
  </button>
  <br>
  <br>
  <em style="color:green; text-shadow: 2px -4px 10px #faffb5;">{setup_error}</em>
  <br>
</div>
<!-- start screen -->

<!-- Starting turn -->
<div id="starting_turn">
  <h1><b>
  {#if !localPlay}
    Roll dice to start
  {:else}
    Player 1 roll dice
  {/if}
  </b></h1>
  <br />
  <button class="button" on:click={turnRole}>Roll</button>
</div>
<!-- starting turn -->

<!-- Dice roll -->
<div id="dice_roll">
  <Dice bind:die={turnCount} />
  <br />
</div>
<!-- Dice roll -->

<!-- Dice roll -->
<div id="dice_roll_p2">
  <DiceComputer bind:die={turnCount} computer='true' />
  <br />
</div>
<!-- Dice roll -->


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

  <!-- Asia -->
  <div id="Afghanistan" class="tile" style="position: absolute; height:200px; width:300px; left:2020px; top:870px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Middle East" class="tile" style="position: absolute; height:200px; width:310px; left:1830px; top:1070px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="India" class="tile"
      style="position: absolute; height:380px; width:310px; left:2150px; top:1190px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Siam" class="tile"
      style="position: absolute; height:270px; width:250px; left:2480px; top:1280px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="China" class="tile"
      style="position: absolute; height:200px; width:500px; left:2280px; top:990px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Mongolia" class="tile"
      style="position: absolute; height:150px; width:440px; left:2400px; top:840px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Irkutsk" class="tile"
      style="position: absolute; height:200px; width:250px; left:2500px; top:640px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Yakutsk" class="tile"
      style="position: absolute; height:170px; width:290px; left:2570px; top:440px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Kamchatka" class="tile"
      style="position: absolute; height:170px; width:350px; left:2870px; top:460px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Japan" class="tile"
      style="position: absolute; height:220px; width:150px; left:2900px; top:880px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Siberia" class="tile"
      style="position: absolute; height:400px; width:190px; left:2300px; top:340px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <div id="Ural" class="tile"
      style="position: absolute; height:200px; width:150px; left:2140px; top:590px; background-color: rgba(220,230,20,0.2);"
      on:click={move}>
  </div>
  <!-- asia -->


  <!-- Europe -->
  <div id="Iceland" class="tile" style="position: absolute; height:60px; width:120px; left:1290px; top:590px; background-color: rgba(50,50,160,0.2);"
      on:click={move}>
  </div>
  <div id="Scandinavia" class="tile" style="position: absolute; height:300px; width:250px; left:1490px; top:470px; background-color: rgba(50,50,160,0.2);"
      on:click={move}>
  </div>
  <div id="Britain" class="tile"
      style="position: absolute; height:175px; width:150px; left:1330px; top:735px; background-color: rgba(50,50,160,0.2);"
      on:click={move}>
  </div>
  <div id="Western Europe" class="tile"
      style="position: absolute; height:230px; width:190px; left:1330px; top:910px; background-color: rgba(50,50,160,0.2);"
      on:click={move}>
  </div>
  <div id="Northern Europe" class="tile"
      style="position: absolute; height:170px; width:230px; left:1480px; top:770px; background-color: rgba(50,50,160,0.2);"
      on:click={move}>
  </div>
  <div id="Southern Europe" class="tile"
      style="position: absolute; height:210px; width:230px; left:1520px; top:940px; background-color: rgba(50,50,160,0.2);"
      on:click={move}>
  </div>
  <div id="Ukraine" class="tile"
      style="position: absolute; height:420px; width:300px; left:1740px; top:540px; background-color: rgba(50,50,160,0.2);"
      on:click={move}>
  </div>
  <!-- europe -->

  <!-- Africa -->
  <div id="North Africa" class="tile" style="position: absolute; height:440px; width:280px; left:1290px; top:1150px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Egypt" class="tile" style="position: absolute; height:190px; width:270px; left:1570px; top:1170px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="East Africa" class="tile"
      style="position: absolute; height:240px; width:180px; left:1730px; top:1365px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Congo" class="tile"
      style="position: absolute; height:235px; width:300px; left:1550px; top:1605px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="South Africa" class="tile"
      style="position: absolute; height:400px; width:330px; left:1580px; top:1840px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Madagascar" class="tile"
      style="position: absolute; height:180px; width:100px; left:1920px; top:1885px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <!-- africa -->

  <!-- South America -->
  <div id="Venezuela" class="tile" style="position: absolute; height:180px; width:340px; left:660px; top:1280px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Peru" class="tile" style="position: absolute; height:290px; width:220px; left:630px; top:1470px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Brazil" class="tile"
      style="position: absolute; height:390px; width:270px; left:930px; top:1465px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Argentina" class="tile"
      style="position: absolute; height:445px; width:230px; left:740px; top:1855px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <!-- south america -->

  <!-- Oceana -->
  <div id="Indonesia" class="tile" style="position: absolute; height:240px; width:330px; left:2590px; top:1550px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="New Guinea" class="tile" style="position: absolute; height:140px; width:270px; left:2926px; top:1650px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Eastern Australia" class="tile"
      style="position: absolute; height:450px; width:220px; left:3020px; top:1815px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Western Australia" class="tile"
      style="position: absolute; height:400px; width:270px; left:2650px; top:1815px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <!-- oceana -->
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

.button {
  border: 1px solid goldenrod;
  padding: 2px;
  margin: 4px;
  width: 80px
}

.button:active {
  padding: -20px;
  border-radius: 50px 20px;
  border: 1px solid white;
  color: silver;
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

#starting_turn {
  display: none;
  position: fixed;
  width: 800px;
  top: 22%;
  right: 10%;
  background-color: #65c365;
  padding: 50px;
  text-align: center;
  z-index: 20;
}

#dice_roll {
  display: none;
  position: fixed;
  width: 800px;
  top: 22%;
  right: 10%;
  background-color: #65c365de;
  padding: 50px;
  text-align: center;
  z-index: 20;
}

#dice_roll_p2 {
  display: none;
  position: fixed;
  width: 800px;
  top: 22%;
  right: 10%;
  background-color: #65c365de;
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
