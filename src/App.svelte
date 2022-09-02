<script>
  import Dice from './Dice.svelte';
  import UnitPlacements from './UnitPlacements.svelte';
  import Pieces from './Board/Pieces.svelte';
  import { onMount } from 'svelte';
  onMount(() => {
    window.scrollTo(400,1000);
  })

  let turnCount = 3;
  let selectionTerritory = 'x';
  let selection = [];

  let localPlay = false;
  let territoriesSetStart = 14;
  let initialTerrertoriesSet = 0;
  let unitsSetStart = 40;
  let generalsStart = 1;
  let artilleryStart = 1;
  let infantryStart = 25;
  let generalsStartP2 = 1;
  let artilleryStartP2 = 1;
  let infantryStartP2 = 25;
  let setup_error = " "
  let setup_error_top = " "

  let australiaSComputers = false; // Australia^'s under computer rule
  let africaSComputers = false;
  let northAmericaSComputers = false;
  let southAmericaSComputers = false;
  let europeSComputers = false;
  let asiaSComputers = false;
  let ignoreAsia = false;

  $: totalStartingUnits = unitsSetStart -
    infantryStart - (artilleryStart * 5)
    - (generalsStart * 10);

  $: totalStartingUnitsP2 = infantryStartP2 + (artilleryStartP2 * 5)
    + (generalsStartP2 * 10);

  let gameLog = {
    settings: {},
    gameMap: [], // G,A,i,eG,eA,ei (G - general, eG - enemy general)
    diceRoll: 0,
    turn: 'player 1', // 'computer'
    Player1Card: null,
    Player1PrevCard: [],
    Player2Card: null,
    Player2PrevCard: [],
    ComputerCard: null,
    Deck: [{c:"Alaska", u:"i"},
           {c:"Northwest Territory", u:"A"},
           {c:"Alberta", u:"A"},
           {c:"Ontario", u:"G"},
           {c:"Quebec", u:"G"},
           {c:"Western United States", u:"i"},
           {c:"Eastern United States", u:"A"},
           {c:"Central America", u:"G"},
           {c:"Greenland", u:"G"},
           {c:"Afghanistan", u:"G"},
           {c:"Middle East", u:"A"},
           {c:"India", u:"i"},
           {c:"Siam", u:"A"},
           {c:"China", u:"G"},
           {c:"Mongolia", u:"A"},
           {c:"Irkutsk", u:"i"},
           {c:"Yakutsk", u:"G"},
           {c:"Kamchatka", u:"G"},
           {c:"Japan", u:"i"},
           {c:"Siberia", u:"A"},
           {c:"Ural", u:"G"},
           {c:"Iceland", u:"i"},
           {c:"Scandinavia", u:"A"},
           {c:"Britain", u:"G"},
           {c:"Western Europe", u:"i"},
           {c:"Northern Europe", u:"G"},
           {c:"Southern Europe", u:"G"},
           {c:"Ukraine", u:"A"},
           {c:"North Africa", u:"i"},
           {c:"Egypt", u:"i"},
           {c:"East Africa", u:"A"},
           {c:"Congo", u:"G"},
           {c:"South Africa", u:"A"},
           {c:"Madagascar", u:"i"},
           {c:"Venezuela", u:"A"},
           {c:"Peru", u:"G"},
           {c:"Brazil", u:"A"},
           {c:"Argentina", u:"i"},
           {c:"Indonesia", u:"G"},
           {c:"New Guinea", u:"G"},
           {c:"Eastern Australia", u:"i"},
           {c:"Western Australia", u:"A"},
           {c:"Wild", u:"W"},
           {c:"Wild", u:"W"}],
  }
  let saveFileName = null;
  let fileinput;

  function handleClick() {
    if ((selectionTerritory == 'x' || selectionTerritory == this.parentElement.id) &&
          infantryStart == 0 && generalsStart == 0 && artilleryStart == 0) {
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

  function saveGameMenu() {
    document.getElementById('game_save').style.display = 'inherit';
    document.getElementById('setup_menu').style.backgroundColor = "#65c36580";
  }

  // load gamefile
  const onFileSelected =(e)=>{
	  let file = e.target.files[0];
    let reader = new FileReader();
    reader.readAsText(file);
    reader.onload = function() {
      gameLog = JSON.parse(reader.result);
    };
  }

  function saveGameMenuDone() {
    document.getElementById('setup_menu').style.backgroundColor = "#65c365";
    document.getElementById('game_save').style.display = 'none';
  }

  function doneSetup() {
    let error = false;

    if (infantryStart < territoriesSetStart + 1) {
      setup_error = 'Not enough pieces to begin game, please add more';
      setup_error_top = true;
      error = true;
    } else {
      setup_error_top = ' ';
    }

    if (error == false) {
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

      // log setup
      gameLog.settings = {'localPlay':localPlay,
                          'territoriesSetStart': territoriesSetStart,
                          'unitsSetStart': unitsSetStart,
                          'generalsStart': generalsStart,
                          'artilleryStart': artilleryStart,
                          'infantryStart': infantryStart,
                          'totalStartingUnitsP2': totalStartingUnitsP2,
                          'generalsStartP2': generalsStartP2,
                          'artilleryStartP2': artilleryStartP2,
                          'infantryStartP2': infantryStartP2};
  }
  }

  function turnRole() {
    this.parentElement.style.display = 'none';
    document.getElementById('dice_roll').style.display = 'inherit';
  }

  function getRandomCompTerritory() {
    let compsTerritories = [];
    Object.keys(gameLog.gameMap).forEach(country => {
      if (gameLog.gameMap[country].includes("ei")) {
        compsTerritories.push(country);
      }
    });
    return compsTerritories[Math.floor(Math.random() * (compsTerritories.length + 1))]
  }

  function move() {

      if (territoriesSetStart != 0
      && document.getElementById('setup_menu').style.display == 'none'
      && this.children.length == 0) {
        var u = document.getElementById('UnitsInfantry');
        if (document.body.style.cursor.includes("unit")) {
          const x = u.children[0];
          this.appendChild(x);

          // log piece placement
          gameLog.gameMap[this.id] = 'i';
        }
        if (initialTerrertoriesSet == 0) {
          initialTerrertoriesSet = territoriesSetStart;
        }
        territoriesSetStart--;
        infantryStart--;

        // computer turn
        computerBoardCheck();

      }

      // place all remaining units
      else if ((infantryStart != 0 || generalsStart != 0 || artilleryStart != 0) && territoriesSetStart == 0) {
        document.getElementById('unitsRemaining').style.display = 'inherit';
        setTimeout(() => {
          document.getElementById('placementsDone').style.visibility = "hidden";
        }, 7000);

        if (document.body.style.cursor.includes("unit") && infantryStart != 0 &&
            this.children[0].children[0].src.includes('unit.png')) {

          // place enemy unit
          if (infantryStartP2 != 0) {
            // todo - place more computer units per turn eg 2 or 3 and finish earlier
            let v = document.getElementById('EnemyInfantry');
            let compTerritory = getRandomCompTerritory();
            document.getElementById(compTerritory).appendChild(v.children[0]);
            gameLog.gameMap[compTerritory] += '-ei'; // log
          }

          var u = document.getElementById('UnitsInfantry');
          const x = u.children[0];
          this.appendChild(x);
          gameLog.gameMap[this.id] += '-i'; // log
          infantryStart--;
          if (infantryStart == 0) {
            document.body.style.cursor = `url('pieces/artillery-c.png'), auto`;
          }
        }
        else if (document.body.style.cursor.includes("artillery") && infantryStart == 0 && artilleryStart != 0 &&
          this.children[0].children[0].src.includes('unit.png')) {

            // place enemy artillery
            if (infantryStartP2 != 0) {
              let v = document.getElementById('EnemyArtillery');
              let compTerritory = getRandomCompTerritory();
              document.getElementById(compTerritory).appendChild(v.children[0]);
              gameLog.gameMap[compTerritory] += '-eA'; // log
            }

            var u = document.getElementById('UnitsArtillery');
            const x = u.children[0];
            this.appendChild(x);
            gameLog.gameMap[this.id] += '-A'; // log
            artilleryStart--;
            if (artilleryStart == 0) {
              document.body.style.cursor = `url('pieces/general-c.png'), auto`;
            }
          }
        else if (document.body.style.cursor.includes("general") && artilleryStart == 0 && generalsStart != 0 &&
          this.children[0].children[0].src.includes('unit.png')) {

            // place enemy generals
            if (generalsStartP2 != 0) {
              let v = document.getElementById('EnemyGeneral');
              let compTerritory = getRandomCompTerritory();
              document.getElementById(compTerritory).appendChild(v.children[0]);
              gameLog.gameMap[compTerritory] += '-eG'; // log
            }

            var u = document.getElementById('UnitsGenerals');
            const x = u.children[0];
            this.appendChild(x);
            gameLog.gameMap[this.id] += '-G'; // log
            generalsStart--;
            if (generalsStart == 0) {
              document.body.style.cursor = `inherit`;
              // todo - select card
            }
          }
      }

      else {
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
  }

  // inital piece placements
  function computerBoardCheck() {

    // only place one unit per turn
    let unitsPlaced = 0;

    while (unitsPlaced == 0) {
      var u = document.getElementById('EnemyInfantry');
      const x = u.children[0];

      // place on capitals
      if (document.getElementById('Western Europe').children.length == 0) {
        document.getElementById('Western Europe').appendChild(x);
        gameLog.gameMap['Western Europe'] = 'ei'; // log
        unitsPlaced = 1;
      }

      else if (document.getElementById('Eastern United States').children.length == 0) {
        document.getElementById('Eastern United States').appendChild(x);
        gameLog.gameMap['Eastern United States'] = 'ei'; // log
        unitsPlaced = 1;
      }

      else if (document.getElementById('China').children.length == 0) {
        document.getElementById('China').appendChild(x);
        gameLog.gameMap['China'] = 'ei'; // log
        unitsPlaced = 1;
      }

      // check if asia has 4 pieces
      else if (continentHumanCount('Asia') > 4 && ignoreAsia == false) {
        if (document.getElementById('China').children.length == 0) {
          document.getElementById('China').appendChild(x);
          gameLog.gameMap['China'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Mongolia').children.length == 0) {
          document.getElementById('Mongolia').appendChild(x);
          gameLog.gameMap['Mongolia'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else {
          ignoreAsia = true;
          unitsPlaced = 'skipped';
        }
      }

      // check if australia has 0 pieces
      else if (continentHumanCount('Australia') == 0 && australiaSComputers == false) {
        if (document.getElementById('Indonesia').children.length == 0) {
          document.getElementById('Indonesia').appendChild(x);
          gameLog.gameMap['Indonesia'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('New Guinea').children.length == 0) {
          document.getElementById('New Guinea').appendChild(x);
          gameLog.gameMap['New Guinea'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Eastern Australia').children.length == 0) {
          document.getElementById('Eastern Australia').appendChild(x);
          gameLog.gameMap['Eastern Australia'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Western Australia').children.length == 0) {
          document.getElementById('Western Australia').appendChild(x);
          gameLog.gameMap['Western Australia'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else {
          australiaSComputers = true;
          unitsPlaced = 'skipped';
        }
      }

      // check if africa has 0 pieces
      else if (continentHumanCount('Africa') == 0 && africaSComputers == false) {
        if (document.getElementById('North Africa').children.length == 0) {
          document.getElementById('North Africa').appendChild(x);
          gameLog.gameMap['North Africa'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Egypt').children.length == 0) {
          document.getElementById('East Africa').appendChild(x);
          gameLog.gameMap['East Africa'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Congo').children.length == 0) {
          document.getElementById('Congo').appendChild(x);
          gameLog.gameMap['Congo'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('South Africa').children.length == 0) {
          document.getElementById('South Africa').appendChild(x);
          gameLog.gameMap['South Africa'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Madagascar').children.length == 0) {
          document.getElementById('Madagascar').appendChild(x);
          gameLog.gameMap['Madagascar'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else {
          africaSComputers = true;
          unitsPlaced = 'skipped';
        }
      }

      // check if south america has 0 pieces
      else if (continentHumanCount('South America') == 0 && southAmericaSComputers == false) {
        if (document.getElementById('Venezuela').children.length == 0) {
          document.getElementById('Venezuela').appendChild(x);
          gameLog.gameMap['Venezuela'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Peru').children.length == 0) {
          document.getElementById('Peru').appendChild(x);
          gameLog.gameMap['Peru'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Brazil').children.length == 0) {
          document.getElementById('Brazil').appendChild(x);
          gameLog.gameMap['Brazil'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Argentina').children.length == 0) {
          document.getElementById('Argentina').appendChild(x);
          gameLog.gameMap['Argentina'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else {
          southAmericaSComputers = true;
          unitsPlaced = 'skipped';
        }
      }

      // check if north america has 0 pieces
      else if (continentHumanCount('North America') == 0 && northAmericaSComputers == false) {
        if (document.getElementById('Alaska').children.length == 0) {
          document.getElementById('Alaska').appendChild(x);
          gameLog.gameMap['Alaska'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Northwest Territory').children.length == 0) {
          document.getElementById('Northwest Territory').appendChild(x);
          gameLog.gameMap['Northwest Territory'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Alberta').children.length == 0) {
          document.getElementById('Alberta').appendChild(x);
          gameLog.gameMap['Alberta'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Ontario').children.length == 0) {
          document.getElementById('Ontario').appendChild(x);
          gameLog.gameMap['Ontario'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Quebec').children.length == 0) {
          document.getElementById('Quebec').appendChild(x);
          gameLog.gameMap['Quebec'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Western United States').children.length == 0) {
          document.getElementById('Western United States').appendChild(x);
          gameLog.gameMap['Western United States'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Eastern United States').children.length == 0) {
          document.getElementById('Eastern United States').appendChild(x);
          gameLog.gameMap['Eastern United States'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Central America').children.length == 0) {
          document.getElementById('Central America').appendChild(x);
          gameLog.gameMap['Central America'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Greenland').children.length == 0) {
          document.getElementById('Greenland').appendChild(x);
          gameLog.gameMap['Greenland'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else {
          northAmericaSComputers = true;
          unitsPlaced = 'skipped';
        }
      }

      // check if europe has 0 pieces
      else if (continentHumanCount('Europe') == 0 && europeSComputers == false) {
        if (document.getElementById('Iceland').children.length == 0) {
          document.getElementById('Iceland').appendChild(x);
          gameLog.gameMap['Iceland'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Scandinavia').children.length == 0) {
          document.getElementById('Scandinavia').appendChild(x);
          gameLog.gameMap['Scandinavia'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Britain').children.length == 0) {
          document.getElementById('Britain').appendChild(x);
          gameLog.gameMap['Britain'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Western Europe').children.length == 0) {
          document.getElementById('Western Europe').appendChild(x);
          gameLog.gameMap['Western Europe'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Northern Europe').children.length == 0) {
          document.getElementById('Northern Europe').appendChild(x);
          gameLog.gameMap['Northern Europe'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Southern Europe').children.length == 0) {
          document.getElementById('Southern Europe').appendChild(x);
          gameLog.gameMap['Southern Europe'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else if (document.getElementById('Ukraine').children.length == 0) {
          document.getElementById('Ukraine').appendChild(x);
          gameLog.gameMap['Ukraine'] = 'ei'; // log
          unitsPlaced = 1;
        }
        else {
          europeSComputers = true;
          unitsPlaced = 'skipped';
        }
      }

      // fallback
      else if (document.getElementById('Kamchatka').children.length == 0) {
        document.getElementById('Kamchatka').appendChild(x);
        gameLog.gameMap['Kamchatka'] = 'ei'; // log
        unitsPlaced = 1;
      }

      else if (document.getElementById('Japan').children.length == 0) {
        document.getElementById('Japan').appendChild(x);
        gameLog.gameMap['Japan'] = 'ei'; // log
        unitsPlaced = 1;
      }
    }
    if (unitsPlaced == 'skipped') {
      computerBoardCheck();
    };
  }

  function continentHumanCount(continent) {
    let count = 0;
    let continentCountries = [];

    if (continent == 'Australia') {
      continentCountries = ['Indonesia', 'New Guinea', 'Eastern Australia',
                    'Western Australia'];
    }

    else if (continent == "Asia") {
      continentCountries = ['Afghanistan', 'Middle East', 'India', 'Siam',
                      'China', 'Mongolia', 'Irkutsk', 'Yakutsk',
                      'Kamchatka', 'Japan', 'Siberia', 'Ural'];
    }

    else if (continent == 'Africa') {
      continentCountries = ['North Africa', 'Egypt', 'East Africa',
                    'Congo', 'South Africa', 'Madagascar'];
    }

    else if (continent == "South America") {
      continentCountries = ['Venezuela', 'Peru', 'Brazil', 'Argentina'];
    }

    else if (continent == "North America") {
      continentCountries = ['Alaska', 'Northwest Territory', 'Alberta', 'Ontario',
                           'Quebec', 'Western United States', 'Eastern United States',
                           'Central America', 'Greenland'];
    }

    else if (continent == "Europe") {
      continentCountries = ['Iceland', 'Scandinavia', 'Britain', 'Western Europe',
                           'Northern Europe', 'Southern Europe', 'Ukraine'];
    }

    continentCountries.forEach(country => {
      // console.log(country);
      if (document.getElementById(country).children.length > 0) {
        if (document.getElementById(country).children[0].children[0].src.includes('unit.png')) {
          count++;
        }
      }
    });

    return count;
  }

  // Save gamelog to file
  function downloadSaveFile() {

    // Add date to file name
    var date = new Date();var dd = String(date.getDate()).padStart(2, '0');
    var mm = String(date.getMonth() + 1).padStart(2, '0');
    var yyyy = date.getFullYear(); date = mm + '/' + dd + '/' + yyyy;

    var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(gameLog));
    var dlAnchorElem = document.getElementById('downloadAnchorElem');
    dlAnchorElem.setAttribute("href", dataStr);
    dlAnchorElem.setAttribute("download", "risk_save_" + date + ".gamefile");
    dlAnchorElem.click();
	}

  // Save game online
  async function generateSaveFile () {
    var url = 'https://rebabre.com/app/risk/save.php';

    var formData = new FormData();
    formData.append('data', JSON.stringify(gameLog));

    fetch(url, { method: 'POST', body: formData })
    .then(function (response) {
      return response.text();
    })
    .then(function (body) {
      console.log(body);
      let dat = JSON.parse(body)
      saveFileName = dat.file
    });
	}
</script>

<svelte:head>
	<title>{saveFileName ? `Risk - ${saveFileName}` : 'Risk'}</title>
	<html lang="en" />
</svelte:head>
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
  <br>
  Starting territories
  <br>
  <input bind:value={territoriesSetStart} step="1" type="number" class="setup_input"
        min='1' max='21' >
  {#if setup_error_top != ' '}
    <span class="star_error">❊</span>
  {/if}
  <br>


  <br>
  Starting units - remaining units pts.
  <br>
  <input bind:value={unitsSetStart} step="2" type="number" class="setup_input"
        min='0' >
  <input style="background-color: #577ea1;" bind:value={totalStartingUnits} type="number" class="setup_input" disabled>
  {#if setup_error != ' ' && setup_error_top == ' '}
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
  <UnitPlacements bind:die={turnCount} computer='true' />
  <br />
</div>
<!-- Dice roll -->

<!-- Game save -->
<div id="game_save">
  <a href="#" on:click={generateSaveFile}>Save game online</a>
  <br /><br />

  <a href="#">
    <input style="width: 62px;
                  text-align: center;
                  color: darkcyan;"
           placeholder={saveFileName}/>
      &nbsp;load game
  </a>
  <br /><br />
  <hr style="width: 114px;
             margin: auto;">
  <br />

  <a href="#"
     style="display: inline;"
     on:click={downloadSaveFile}>
      Save gamefile
  </a><a id="downloadAnchorElem" style="display:none"></a>
  <br /><br />

  <button on:click={()=>{fileinput.click();}}>Load gamefile</button>
  <input style="display:none"
         type="file"
         accept=".gamefile"
         on:change={(e)=>onFileSelected(e)} bind:this={fileinput} >
  <br /><br /><br />

  <button class="button" on:click={saveGameMenuDone}>Done</button>
  <br />
</div>
<!-- game save -->

<!-- Units remaining -->
<div id="unitsRemaining" style="background-color: #3f8b18;position:fixed;bottom:0;left:30;margin-left: 1px; display: none;">
  <div id="placementsDone" style="background-color: #65c365;">
    You've placed all {initialTerrertoriesSet} territories.
  </div>
  <div>
    <div class="unitsRemainingSec">
      <img src="general.webp" width="20" />
      {generalsStart}
    </div>
    <div class="unitsRemainingSec">
      <img src="pieces/artillery.png" width="20"  />
      {artilleryStart}
    </div>
    <div class="unitsRemainingSec">
      <img src="pieces/unit.png" width="20"  />
      {infantryStart}
    </div>
  </div>
  <br />
</div>
<!-- units remaining -->


  <!-- <div style="position: absolute; left:200px; top:390px;">
  <img src="general.webp" width=100 />
  </div> -->

  <!-- turn count -->
  <div style="position: absolute; left:200px; top:690px;">
    <big><big style="font-size: 4em;">{turnCount}</big></big><br />
    <span on:click={deSelect} class="clickable">🚫 Deselect Units</span><br /><br /><br />
    <span class="clickable" on:click={saveGameMenu} style="margin-top: -24px;">
      <img src="https://raw.githubusercontent.com/Silbad/pixa/main/icons/world.svg"
      alt="save on web icon"
      style="display: inherit;
    width: 25px;
    background-color: papayawhip;
    opacity: 0.7;
    "/>
        Save or load
    </span>
  </div>

  <!-- Units -->
  <div id='Units' style="display: none">
    <span style="position:sticky; left:2px; top:3px;" on:click={handleClick}>
      <img src="general.webp" width=100 />
    </span>
    <span style="position: sticky; left:4px; top:1px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/artillery.png" width=100 />
    </span>
    <span style="position: sticky; left:4px; top:2px; flex: 19.33%;" on:click={handleClick}>
      <img src="pieces/unit.png" width=200 />
    </span>
  </div>

  <Pieces {handleClick} />

  <!-- Regions -->
  <!-- America -->
  <div id="Alaska" class="tile" style="position: absolute; height:200px; width:300px; left:0px; top:390px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
  </div>

  <div id="Northwest Territory" class="tile" style="position: absolute; height:200px; width:400px; left:300px; top:420px; background-color: rgba(250,20,20,0.2);"
      on:click={move}>
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
      style="position: absolute; height:340px; width:450px; left:1010px; top:210px; background-color: rgba(250,20,20,0.2);"
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
  <div id="Iceland" class="tile" style="position: absolute; height:90px; width:220px; left:1240px; top:580px; background-color: rgba(50,50,160,0.2);"
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
      style="position: absolute; height:400px; width:260px; left:1580px; top:1840px; background-color: rgba(50,100,60,0.2);"
      on:click={move}>
  </div>
  <div id="Madagascar" class="tile"
      style="position: absolute; height:290px; width:180px; left:1920px; top:1825px; background-color: rgba(50,100,60,0.2);"
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

#game_save {
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

.unitsRemainingSec {
  display: inline-block;
  width: 30%;
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
