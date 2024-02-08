<script>
  import Dice from './Dice.svelte';
  import UnitPlacements from './UnitPlacements.svelte';
  import Pieces from './Board/Pieces.svelte';
  import { NEIGHBOURING_STATES, COUNTRY_CONTINENT } from './Board/MapMovement.svelte';

  import { onMount } from 'svelte';
  onMount(() => {
    window.scrollTo(400,1000);
  })

  let turnCount = 3;
  let selectionTerritory = 'x';
  let selection = [];
  let alert = '';
  let disableSelect = 0;

  let computerMove = '';

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
  
  $: drawnCardHistoryIndex = 0;
  let needCard = true;
  let needCardP2 = true;
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
    drawnCardHistory: [{c:"", u:""}],
    drawnCardHistoryP2: [{c:"", u:""}],
    /**
      gameMap permitted values legend
      "G,A,i,eG,eA,ei"
       (G - General,
        eG - enemy General,
        A - cannon)
    **/
    gameMap: {'Alaska':'',
              'Northwest Territory':'',
              'Alberta':'',
              'Ontario':'',
              'Quebec':'',
              'Western United States':'',
              'Eastern United States':'',
              'Central America':'',
              'Greenland':'',
              'Afghanistan':'',
              'Middle East':'',
              'India':'',
              'Siam':'',
              'China':'',
              'Mongolia':'',
              'Irkutsk':'',
              'Yakutsk':'',
              'Kamchatka':'',
              'Japan':'',
              'Siberia':'',
              'Ural':'',
              'Iceland':'',
              'Scandinavia':'',
              'Britain':'',
              'Western Europe':'',
              'Northern Europe':'',
              'Southern Europe':'',
              'Ukraine':'',
              'North Africa':'',
              'Egypt':'',
              'East Africa':'',
              'Congo':'',
              'South Africa':'',
              'Madagascar':'',
              'Venezuela':'',
              'Peru':'',
              'Brazil':'',
              'Argentina':'',
              'Indonesia':'',
              'New Guinea':'',
              'Eastern Australia':'',
              'Western Australia':''},
    diceRoll: 0,
    turn: 'player 1', // 'computer'
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

  let x1 = 1 // run only once

  // init
  gameSetup('dev');

  // display alert message
  function pubAlert(x) {
    alert = x;
    setTimeout(() => {
      alert = '';
    }, 2000);
  }

  // click-movin
  function handleClick() {
    if ((selectionTerritory == 'x' || selectionTerritory == this.parentElement.id) &&
          infantryStart == 0 && generalsStart == 0 && artilleryStart == 0) {
      selectionTerritory = this.parentElement.id
      this.style.backgroundColor = '#007d80'
      selection.push(this);

      // is treasure 
      if (selectionTerritory == gameLog.drawnCardHistory[drawnCardHistoryIndex].c){
        console.log('youvemovedyertreasure' + gameLog.drawnCardHistory[drawnCardHistoryIndex].c)
        let x = Math.floor(Math.random() * gameLog.Deck.length);
        gameLog.drawnCardHistory.push(gameLog.Deck[x]);
        gameLog.Deck.splice(x, 1);
        needCard = false;
        drawnCardHistoryIndex = drawnCardHistoryIndex+1
        pubAlert("You drew "+ gameLog.drawnCardHistory[drawnCardHistoryIndex].c + ": " +
          gameLog.drawnCardHistory[drawnCardHistoryIndex].u)
        if (gameLog.drawnCardHistory[drawnCardHistoryIndex-1].u == 'i'){
          document.body.style.cursor = `url('pieces/unit-c.png'), auto`;
          disableSelect = 1;
          infantryStart = 1;
        }
        if (gameLog.drawnCardHistory[drawnCardHistoryIndex-1].u == 'G'){
          document.body.style.cursor = `url('pieces/general-c.png'), auto`;
          disableSelect = 1;
          generalsStart = 1;
        }
        if (gameLog.drawnCardHistory[drawnCardHistoryIndex-1].u == 'A'){
          document.body.style.cursor = `url('pieces/artillery-c.png'), auto`;
          disableSelect = 1;
          artilleryStart = 1;
        }
        if (gameLog.drawnCardHistory[drawnCardHistoryIndex-1].u == 'W'){
          document.body.style.cursor = `url('pieces/unit-c.png'), auto`;
          disableSelect = 1;
          generalsStart = 1;
          artilleryStart = 1;
          infantryStart = 1;
        }
      }
    }
	}

  function landOnTreasure(){

  }

  let toAttackState = '';
  let inProximity = [];
  function lookAroundProx(state){
    Object.keys(gameLog.gameMap).forEach(country => {
      if (state == country) {
        if (!gameLog.gameMap[state].includes("eG")) {
          if (gameLog.gameMap[state].includes("G")) {
            toAttackState = state;
            inProximity = [];
          }
        }
        inProximity.push(NEIGHBOURING_STATES[country]);
      }
    });
  }

  function deSelect() {
    selection.forEach(item=>{
      item.style.backgroundColor = '';
    })
    selection = [];
    selectionTerritory = 'x';
  }

  function gameSetup(game){
    if (game === 'dev') {
      territoriesSetStart = 3;
      unitsSetStart = 20;
      infantryStart = 5;
      generalsStart = 1;
      artilleryStart = 1;
    }
    else if (game === 'quick') {
      territoriesSetStart = 5;
      unitsSetStart = 13;
      infantryStart = 13;
      generalsStart = 0;
      artilleryStart = 0;
    }
    else {
      territoriesSetStart = 14;
      unitsSetStart = 40;
      infantryStart = 25;
      generalsStart = 1;
      artilleryStart = 1;
    }
  }

  function turnStart() {
    pubAlert("Ready to Go!")
    if (needCard === true) {
        let x = Math.floor(Math.random() * gameLog.Deck.length);
        gameLog.drawnCardHistory.push(gameLog.Deck[x]);
        gameLog.Deck.splice(x, 1);
        needCard = false;
        console.log(gameLog.drawnCardHistoryP2);
        drawnCardHistoryIndex = drawnCardHistoryIndex+1
    }

    if (needCardP2 === true) {
        let x = Math.floor(Math.random() * gameLog.Deck.length);
        gameLog.drawnCardHistoryP2.push(gameLog.Deck[x]);
        gameLog.Deck.splice(x, 1);
        needCardP2 = false;
    }

    if (turnCount == 0){
      pubAlert("Computer moved so and so");
      // Do computer turn
      // TODO skip first time if you go first
      computerTurn();

      // TODO?? interactive roll dice
      turnCount = Math.floor(Math.random() * 7);
      if (turnCount == 0){
        turnCount = 1;
      }

      setTimeout(() => {
        pubAlert("You rolled " + turnCount);
      }, 2000);
    }
  }

  let computerPrevTurn = '';
  let computerPrevTurnDest = '';
  function computerTurn(){
    let computerRoll = Math.floor(Math.random() * 7);

    // preact - if on process to a conquest 1/2
    // check if item changed
    // {result - proceed}
    if (computerPrevTurn != '') {
      // TODO
      //
      //{result could be}
      //return
      //return computerTurn()
    }

    // #act 0 - attack general - 65/100-
    computerMove = 'act-0';

    /*
    // get computer stronghold, echo out highest value
    let strongestC = {name: '', value: 0}; // C = country
    Object.keys(gameLog.gameMap).forEach(c => {
      if (gameLog.gameMap[c].includes("e")) {
        if (strongestC.name == '') {
          strongestC.name = c;
          strongestC.value = calculateStrength(gameLog.gameMap[c]);
        } else if (strongestC.value < calculateStrength(gameLog.gameMap[c])) {
          strongestC.name = c;
          strongestC.value = calculateStrength(gameLog.gameMap[c]);
        }
      }
    });

    // Compare countries to see if a general is 7 away
    inProximity = [];
    NEIGHBOURING_STATES[strongestC.name].forEach(state => {
      for (let i = 0; i <= 7; i++) {
        lookAroundProx(state)
        inProximity.forEach(x=>{
          x.forEach(y=>{
            lookAroundProx(y);
          })
        });
      }
    });
    */

    // TODO Attack General -toAttackState
    //   calculate strength else go to next step
    //  or move towards and save remaining moves for next turn -checking if general is still there

    
    if (shouldGeneralAttack() == 1){
        move(locateHumanGeneral(), enemeyStrongholdCountry(), "All");
    } else {
      comp_strongheld_continent();
    };

    function shouldGeneralAttack() {
      let stronghold = 0;
      let current_stronghold = 0;
      let enemyStronghold = 0;
      let current_enemyStronghold = 0;

      Object.keys(gameLog.gameMap).forEach(country => {
        if (gameLog.gameMap[country] !== '') {
          let units = gameLog.gameMap[country];
          units = units.split("-");
          current_stronghold = 0;
          current_enemyStronghold = 0;

          units.forEach(unit => {
            switch(unit) {
              case 'i':
                current_stronghold += 1;
                break;
              case 'ei':
                current_enemyStronghold += 1;
                break;
              case 'G':
                current_stronghold += 10;
                break;
              case 'eG':
                current_enemyStronghold += 10;
                break;
              default:
            }
          });

          if (enemyStronghold < current_enemyStronghold) {
            enemyStronghold = current_enemyStronghold;
          }

          if (stronghold < current_stronghold) {
            stronghold = current_stronghold;
          }
        }
      });
      if (enemyStronghold > stronghold) {
          return 1;
      } else {
          return 0;
      }
    }

    function enemeyStrongholdCountry() {
      let enemyStronghold = 0;
      let current_enemyStronghold = 0;
      let return_country = '';


      Object.keys(gameLog.gameMap).forEach(country => {
        if (gameLog.gameMap[country] !== '') {
          let units = gameLog.gameMap[country];
          units = units.split("-");
          current_enemyStronghold = 0;
          units.forEach(unit => {
            switch(unit) {
              case 'ei':
                current_enemyStronghold += 1;
                break;
              case 'eG':
                current_enemyStronghold += 10;
                break;
              default:
            }
          });
          if (enemyStronghold < current_enemyStronghold) {
            enemyStronghold = current_enemyStronghold;
            return_country = country;
          }
        }
      });
      return return_country;
    }

    // Returns highest area to attack
    function locateHumanGeneral() {
      let return_country = '';
      let enemyStronghold = 0;
      let current_enemyStronghold = 0;

      Object.keys(gameLog.gameMap).forEach(country => {
        if (gameLog.gameMap[country] !== '') {
          let units = gameLog.gameMap[country];
          units = units.split("-");
          current_enemyStronghold = 0;

          units.forEach(unit => {
            switch(unit) {
              case 'G':
                current_enemyStronghold += 1;
                break;
            }
          });
        }
        if (enemyStronghold < current_enemyStronghold) {
            enemyStronghold = current_enemyStronghold;
            return_country = country;
          }
      });
      if (enemyStronghold == 0) {
            Object.keys(gameLog.gameMap).forEach(country => {
          if (gameLog.gameMap[country] !== '') {
            let units = gameLog.gameMap[country];
            units = units.split("-");
            current_enemyStronghold = 0;

            units.forEach(unit => {
              switch(unit) {
                case 'i':
                  current_enemyStronghold += 1;
                  break;
              }
            });
          }
          if (enemyStronghold < current_enemyStronghold) {
              enemyStronghold = current_enemyStronghold;
              return_country = country;
            }
        });
        }

      return return_country;
    }

    // act 2
    // TODO

    function comp_strongheld_continent(){
      let dat = comp_continent_data();
      let highestContinent = '';
      let highestContinentVal = 0;
      let nContinents = [];
      
      Object.keys(dat).forEach(cont => {
        if (dat[cont] >= highestContinentVal){
          if (highestContinent != ''){
            nContinents.push(highestContinent)
          }
          highestContinent = cont;
          highestContinentVal = dat[cont];
        }
      });

      return [highestContinent].concat(nContinents);
    }

    function comp_continent_data(){
      let dat = {
        'Australia':0,
        'S. America':0,
        'Africa':0,
        'N. America':0,
        'Europe':0,
        'Asia':0,
      }
      Object.keys(gameLog.gameMap).forEach(country => {
        let add = 0; // Any unit occupies space in territory count
        let units = gameLog.gameMap[country];
          units = units.split("-");
          units.forEach(unit => {
            if (unit.includes("e")){
              add = 1;
            }
          });
        if (add == 1){
          dat[COUNTRY_CONTINENT[country]] += 1
        }
      });
      return dat
    }

    function move(to, from, units) {
      let x = getDistance(to, from);
      if (computerRoll >= x.length) {
        pubAlert("Enemy took over " + to + "!")
        computerRoll = computerRoll - x.length;

          // replace units
          document.getElementById(to).innerHTML = '';
          document.getElementById(from).innerHTML = '';
          gameLog.gameMap[to] = gameLog.gameMap[from];

          // add / render enemy units
          let country = to;
            if (gameLog.gameMap[country] !== '') {
              let units = gameLog.gameMap[country];
              units = units.split("-");

              let ei = document.getElementById('EnemyInfantry');
              let ei_child = 0;
              let i = document.getElementById('UnitsInfantry');
              let i_child = 0;
              let a = document.getElementById('UnitsArtillery');
              let a_child = 0;
              let ea = document.getElementById('EnemyArtillery');
              let ea_child = 0;
              let eg = document.getElementById('EnemyGeneral');
              let eg_child = 0;
              let g = document.getElementById('UnitsGenerals');
              let g_child = 0;
              
              // loop through units and add it
              units.forEach(unit => {
                switch(unit) {
                  case 'i':
                    document.getElementById(country).appendChild(i.children[i_child]);
                    i_child++;
                    break;
                  case 'ei':
                    document.getElementById(country).appendChild(ei.children[ei_child]);
                    ei_child++;
                    break;
                  case 'A':
                    document.getElementById(country).appendChild(a.children[a_child]);
                    a_child++;
                    break;
                  case 'eA':
                    document.getElementById(country).appendChild(ea.children[ea_child]);
                    ea_child++;
                    break;
                  case 'G':
                    document.getElementById(country).appendChild(g.children[g_child]);
                    g_child++;
                    break;
                  case 'eG':
                    document.getElementById(country).appendChild(eg.children[eg_child]);
                    eg_child++
                    break;
                  default:
                }
                
              });
            }
      }
      else {
        // TODONOW
        // MOVE SOME
      }
    }

    // default example = getDistance("Afganistan", "China", 1));
    //                   > Ural,Ukraine
    function getDistance(to, from) {
      let level = 0;
      let path = [];
      let d = 1;
      NEIGHBOURING_STATES[from].forEach(state => {
        if (state == to) {
          d = 1
          level = 1
          path=[state];
        } else {
          if (level != 1) {
            NEIGHBOURING_STATES[state].forEach(state2 => {
              if (state2 == to) {
                d = 2
                level = 2
                path = [state, state2];
              } else {
                if (level != 2) {
                  NEIGHBOURING_STATES[state2].forEach(state3 => {
                    if (state3 == to) {
                      d = 3
                      level = 3
                      path = [state, state2, state3];
                    } else {
                        if (level != 3) {
                          NEIGHBOURING_STATES[state3].forEach(state4 => {
                            if (state4 == to) {
                              d = 4
                              level = 4
                              path = [state, state2, state3, state4];
                            } else {
                              if (level != 4) {
                                NEIGHBOURING_STATES[state4].forEach(state5 => {
                                  if (state5 == to) {
                                    d = 5
                                    level = 5
                                    path = [state, state2, state3, state4, state5];
                                  } else {
                                  if (level != 5) {
                                    NEIGHBOURING_STATES[state5].forEach(state6 => {
                                      if (state6 == to) {
                                        d = 6
                                        level = 6
                                        path = [state, state2, state3, state4, state5, state6];
                                      } else {
                                  if (level != 6) {
                                    if (typeof NEIGHBOURING_STATES[state6] !== 'undefined' && NEIGHBOURING_STATES[state6] !== null) {
                                    NEIGHBOURING_STATES[state6].forEach(state7 => {
                                      if (state7 == to) {
                                        d = 7
                                        level = 7
                                        path = [state, state2, state3, state4, state5, state6, state7];
                                      } else {
                                  if (level != 7) {
                                    if (typeof NEIGHBOURING_STATES[state7] !== 'undefined' && NEIGHBOURING_STATES[state7] !== null) {
                                    NEIGHBOURING_STATES[state7].forEach(state8 => {
                                      if (state8 == to) {
                                        d = 8
                                        level = 8
                                        path = [state, state2, state3, state4, state5, state6, state7, state8];
                                      } else {
                                  if (level != 8) {
                                    if (typeof NEIGHBOURING_STATES[state8] !== 'undefined' && NEIGHBOURING_STATES[state8] !== null) {
                                    NEIGHBOURING_STATES[state8].forEach(state9 => {
                                      if (state9 == to) {
                                        d = 9
                                        level = 9
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9];
                                      } else {
                                  if (level != 9) {
                                    if (typeof NEIGHBOURING_STATES[state9] !== 'undefined' && NEIGHBOURING_STATES[state9] !== null) {
                                    NEIGHBOURING_STATES[state9].forEach(state10 => {
                                      if (state10 == to) {
                                        d = 10
                                        level = 10
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10];
                                      } else {
                                  if (level != 10) {
                                    if (typeof NEIGHBOURING_STATES[state10] !== 'undefined' && NEIGHBOURING_STATES[state10] !== null) {
                                    NEIGHBOURING_STATES[state10].forEach(state11 => {
                                      if (state11 == to) {
                                        d = 11
                                        level = 11
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10, state11];
                                      } else {
                                  if (level != 11) {
                                    if (typeof NEIGHBOURING_STATES[state11] !== 'undefined' && NEIGHBOURING_STATES[state11] !== null) {
                                    NEIGHBOURING_STATES[state11].forEach(state12 => {
                                      if (state12 == to) {
                                        d = 12
                                        level = 12
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10, state11, state12];
                                      } else {
                                  if (level != 12) {
                                    if (typeof NEIGHBOURING_STATES[state12] !== 'undefined' && NEIGHBOURING_STATES[state12] !== null) {
                                    NEIGHBOURING_STATES[state12].forEach(state13 => {
                                      if (state13 == to) {
                                        d = 13
                                        level = 13
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10, state11, state12, state13];
                                      } else {
                                  if (level != 13) {
                                    if (typeof NEIGHBOURING_STATES[state13] !== 'undefined' && NEIGHBOURING_STATES[state13] !== null) {
                                    NEIGHBOURING_STATES[state13].forEach(state14 => {
                                      if (state14 == to) {
                                        d = 14
                                        level = 14
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10, state11, state12, state13, state14];
                                      } else {
                                  if (level != 14) {
                                    if (typeof NEIGHBOURING_STATES[state14] !== 'undefined' && NEIGHBOURING_STATES[state14] !== null) {
                                    NEIGHBOURING_STATES[state14].forEach(state15 => {
                                      if (state15 == to) {
                                        d = 15
                                        level = 15
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10, state11, state12, state13, state14, state15];
                                      } else {
                                  if (level != 15) {
                                    if (typeof NEIGHBOURING_STATES[state15] !== 'undefined' && NEIGHBOURING_STATES[state15] !== null) {
                                    NEIGHBOURING_STATES[state15].forEach(state16 => {
                                      if (state16 == to) {
                                        d = 16
                                        level = 16
                                        path = [state, state2, state3, state4, state5, state6,state7, state8, state9, state10, state11, state12, state13, state14, state15, state16];
                                      } else {
                                  if (level != 16) {
                                    if (typeof NEIGHBOURING_STATES[state16] !== 'undefined' && NEIGHBOURING_STATES[state16] !== null) {
                                    NEIGHBOURING_STATES[state16].forEach(state17 => {
                                      if (state17 == to) {
                                        d = 17
                                        level = 17
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10, state11, state12, state13, state14, state15,state16,state17];
                                      } else {
                                  if (level != 17) {
                                    if (typeof NEIGHBOURING_STATES[state17] !== 'undefined' && NEIGHBOURING_STATES[state17] !== null) {
                                    NEIGHBOURING_STATES[state17].forEach(state18 => {
                                      if (state18 == to) {
                                        d = 18
                                        level = 18
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10, state11, state12, state13, state14, state15,state16,state17, state18];
                                      } else {
                                  if (level != 18) {
                                    if (typeof NEIGHBOURING_STATES[state18] !== 'undefined' && NEIGHBOURING_STATES[state18] !== null) {
                                    NEIGHBOURING_STATES[state18].forEach(state19 => {
                                      if (state19 == to) {
                                        d = 19
                                        level = 19
                                        path = [state, state2, state3, state4, state5, state6, state7, state8, state9, state10, state11, state12, state13, state14, state15,state16,state17, state18, state19];
                                      } else {
                                  if (level != 19) {
                                    if (typeof NEIGHBOURING_STATES[state19] !== 'undefined' && NEIGHBOURING_STATES[state19] !== null) {
                                    NEIGHBOURING_STATES[state19].forEach(state20 => {
                                      if (state20 == to) {
                                        d = 20
                                        level = 20
                                        path = [state, state2, state3, state4, state5, state6, , state7, state8, state9, state10, state11, state12, state13, state14, state15,state16,state17, state18, state19, state20];
                                      } else {
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }
                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }


                                    }); }
                                  }
                                }

                                    }); }
                                  }
                                }


                                    });
                                  }
                                } 
                                });
                              } 
                            } 
                          });
                        }
                      } 
                  });
                }
              } 
            });
          }
        }
      });
      return path
    }

  
    // #act 2 - see nearest element and attack it with nearest stronger element 20/100

    // act 1.2 - scan nearby 2nd strength-
    //  7 away - shuffle order for randomness, have a not travel to path

    // act 11 - move randomly


    // TODO logic if computer wins

    // TODO fire cannons if any one nearby, on turn end
  }

  // calc the amount of units on a tile
  function calculateStrength(inhabitants) {
    let x = (((inhabitants.match(new RegExp("eG", "g")) || []).length * 10) +
    ((inhabitants.match(new RegExp("ei", "g")) || []).length));
    return x;
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
      console.log(gameLog); // pre
      gameLog = JSON.parse(reader.result);

      // load states
      drawnCardHistoryIndex = gameLog.drawnCardHistory.length - 1
      needCard = false;
      needCardP2 = false;
      console.log(gameLog); // post

      // rerender map
      console.log("loaded");
      console.log(gameLog); // after load

      // add items
      Object.keys(gameLog.gameMap).forEach(country => {
        if (gameLog.gameMap[country] !== '') {
          let units = gameLog.gameMap[country];
          units = units.split("-");
          console.log(units);

          let ei = document.getElementById('EnemyInfantry');
          let ei_child = 0;
          let i = document.getElementById('UnitsInfantry');
          let i_child = 0;
          let a = document.getElementById('UnitsArtillery');
          let a_child = 0;
          let ea = document.getElementById('EnemyArtillery');
          let ea_child = 0;
          let eg = document.getElementById('EnemyGeneral');
          let eg_child = 0;
          let g = document.getElementById('UnitsGenerals');
          let g_child = 0;
          
          // loop through units and add it
          units.forEach(unit => {
            switch(unit) {
              case 'i':
                document.getElementById(country).appendChild(i.children[i_child]);
                i_child++;
                break;
              case 'ei':
                document.getElementById(country).appendChild(ei.children[ei_child]);
                ei_child++;
                break;
              case 'A':
                document.getElementById(country).appendChild(a.children[a_child]);
                a_child++;
                break;
              case 'eA':
                document.getElementById(country).appendChild(ea.children[ea_child]);
                ea_child++;
                break;
              case 'G':
                document.getElementById(country).appendChild(g.children[g_child]);
                g_child++;
                break;
              case 'eG':
                document.getElementById(country).appendChild(eg.children[eg_child]);
                eg_child++
                break;
              default:
            }
            
          });
        }
      });

      infantryStart = 0; 
      generalsStart = 0;
      artilleryStart = 0;
      territoriesSetStart = 0;
      document.getElementById('setup_menu').style.display = 'none';
      document.getElementById('game_save').style.display = 'none';
      document.body.style.cursor = `inherit`;

      pubAlert('Game loaded');
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
        this.parentElement.parentElement.style.display = 'none';
        document.getElementById('starting_turn').style.display = 'inherit';
        pubAlert("Press button to roll");
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

  function turnRoll() {
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
        if (x1 == 1) {
        pubAlert('Territories set, now place units');
          x1 = 0
        }
        //pubAlert('Place all units to start');
        setTimeout(() => {
          document.getElementById('placementsDone').style.visibility = "hidden";
        }, 7000);
        if (document.body.style.cursor.includes("unit") && infantryStart != 0 &&
            (typeof(this.children[0]) != 'undefined') &&
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
            if (artilleryStart != 0) {
              document.body.style.cursor = `url('pieces/artillery-c.png'), auto`;
            }
            else if (generalsStart != 0) {
              document.body.style.cursor = `url('pieces/artillery-c.png'), auto`;
            }
            else {
              document.body.style.cursor = `inherit`;
              disableSelect = 0;
              turnStart();
            }
          }
        }
        else if (document.body.style.cursor.includes("artillery") && infantryStart == 0 && artilleryStart != 0 &&
          (typeof(this.children[0]) != 'undefined') &&
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
              if (generalsStart != 0) {
                document.body.style.cursor = `url('pieces/general-c.png'), auto`;
              }
              else {
                document.body.style.cursor = `inherit`;
                disableSelect = 0;
                turnStart();
              }
            }
          }
        else if (document.body.style.cursor.includes("general") && artilleryStart == 0 && generalsStart != 0 &&
          (typeof(this.children[0]) != 'undefined') &&
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
              disableSelect = 0;
              turnStart();
            }
          }
      }

      else {
        if (turnCount > 0
            && selection.length > 0
            && (this.childNodes.length == 0 || (selectionTerritory != this.id || selectionTerritory == 'x')) && disableSelect != 1) {
          let won = 0;
          let battleloc = gameLog.gameMap[this.id];

          // check if valid move
          let strength = 0;
          let opposition = 0;
          let cannonCount = 0;
          selection.forEach((piece)=> {
            if (piece.innerHTML.includes("/unit.png")) {
              strength++;
            }
            else if (piece.innerHTML.includes("s/general.webp")) {
              strength = strength + 10;
            }
            opposition = (battleloc.match(/ei/g) || []).length
            + ((battleloc.match(/eG/g) || []).length * 10);
            cannonCount = (battleloc.match(/eA/g) || []).length

            if (strength > opposition) {
              won = 1;
            }
          })

          // Restrict dropping a piece to a far away territory
          if (!NEIGHBOURING_STATES[selectionTerritory].includes(this.id)){
            won == 0;
            return;
          }

          if (won == 1) {
            if (opposition > 0) {
              this.innerHTML = '';

              // Retrieve enemy cannons
              for (let i = 0; i < cannonCount; i++) {
                var u = document.getElementById('UnitsArtillery');
                const x = u.children[0];
                this.appendChild(x);
                battleloc += '-A'; // log
              }
            }
          }

          selection.forEach(item=>{
            item.style.backgroundColor = '';
            if (won == 1) {
              this.appendChild(item);

              // Add to log
              if (item.innerHTML.includes("/unit.png")) {
                if (gameLog.gameMap[this.id] == '') {
                  gameLog.gameMap[this.id] = gameLog.gameMap[this.id] + 'i';
                } else {
                  gameLog.gameMap[this.id] = gameLog.gameMap[this.id] + '-i';
                }
              }
              else if (item.innerHTML.includes("artillery.png")) {
                if (gameLog.gameMap[this.id] == '') {
                  gameLog.gameMap[this.id] = gameLog.gameMap[this.id] + 'A';
                } else {
                  gameLog.gameMap[this.id] = gameLog.gameMap[this.id] + '-A';
                }
              }
              else if (item.innerHTML.includes("s/general.webp")) {
                if (gameLog.gameMap[this.id] == '') {
                  gameLog.gameMap[this.id] = gameLog.gameMap[this.id] + 'G';
                } else {
                  gameLog.gameMap[this.id] = gameLog.gameMap[this.id] + '-G';
                }
              }
            }
          })
          if (won == 1) {
            turnCount--;

            // Remove fom log
            selection.forEach(item => {
              if (item.innerHTML.includes("/unit.png")) {
                if (gameLog.gameMap[selectionTerritory].includes("-i")) {
                  gameLog.gameMap[selectionTerritory] =
                  gameLog.gameMap[selectionTerritory].replace('-i','');
                } else {
                  gameLog.gameMap[selectionTerritory] =
                  gameLog.gameMap[selectionTerritory].replace('i','');
                }
              }
              else if (item.innerHTML.includes("artillery.png")) {
                if (gameLog.gameMap[selectionTerritory].includes("-A")) {
                  gameLog.gameMap[selectionTerritory] =
                  gameLog.gameMap[selectionTerritory].replace('-A','');
                } else {
                  gameLog.gameMap[selectionTerritory] =
                  gameLog.gameMap[selectionTerritory].replace('A','');
                }
              }
              else if (item.innerHTML.includes("s/general.webp")) {
                if (gameLog.gameMap[selectionTerritory].includes("-G")) {
                  gameLog.gameMap[selectionTerritory] =
                  gameLog.gameMap[selectionTerritory].replace('-G','');
                } else {
                  gameLog.gameMap[selectionTerritory] =
                  gameLog.gameMap[selectionTerritory].replace('G','');
                }
              }
            });

            selection = [];
            selectionTerritory = 'x';
          }
        }
      }

      if (turnCount == 0) {
        turnStart();
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

    let dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(gameLog));
    var dlAnchorElem = document.getElementById('downloadAnchorElem');
    dlAnchorElem.setAttribute("href", dataStr);
    dlAnchorElem.setAttribute("download", "risk_save_" + date + ".gamefile");
    dlAnchorElem.click();
	}

    function downloadSaveFileOff() {
    pubAlert("Save functionality is not available game setup is underway.")
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
<div id="setup_menu" style="background-color: #88dc88; opacity: 0.9">
  <h1><b>Game Setup</b></h1>

  <section id="setup_menu_qck_button">
  <span on:click={()=>{gameSetup('dev')}}><i>Dev</i></span>&nbsp;&nbsp;
  <span on:click={()=>{gameSetup('quick')}}><i>Quick game</i></span>&nbsp;&nbsp;
  <span on:click={()=>{gameSetup('normal')}}><i>Normal game</i></span>
  </section>
  <br>

  <input bind:group={localPlay} value={false} type="radio" name="age" checked>
  <label for="age1">vs computer &nbsp;</label>

  <input bind:group={localPlay} name="scoops" value={true} type="radio">
  <label for="age2">vs local</label><br>

  <br>
  <br>
  <section id="an1">
  Starting territories
  <br>
  <input bind:value={territoriesSetStart} step="1" type="number" class="setup_input"
        min='1' max='21' >
  {#if setup_error_top != ' '}
    <span class="star_error">❊</span>
  {/if}
  <br>
  </section>

  <section id="an2">
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
  </section>
  <br>

  <section id="an3">
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
</section>
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
  <button class="button" on:click={turnRoll}>Roll</button>
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
  <a href="#" on:click={document.getElementById('setup_menu').style.display == 'none' ?   downloadSaveFileOff : generateSaveFile}>Save game online</a>
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
     on:click={document.getElementById('setup_menu').style.display == 'none' ? downloadSaveFileOff : downloadSaveFile}>
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


<div style="color: white;
text-shadow: -11px 20px 20px black;
margin: 0 auto;
position: fixed;
top: 40%;
text-align: center;
z-index: 300000;
margin-left: 19%;
font-size: 2em;">
  {alert}
</div>

<!-- Units remaining -->
<div id="unitsRemaining" style="background-color: rgb(139 24 24 / 66%);position:fixed;bottom:0;left:30;margin-left: 1px; display: none;">
  <div id="placementsDone" style="background-color: rgb(139 24 24);">
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
    <span on:click={deSelect} class="clickable">🚫 Deselect Units</span><br />
    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gameLog.drawnCardHistory[drawnCardHistoryIndex].c + ' ' + gameLog.drawnCardHistory[drawnCardHistoryIndex].u}</span><br />
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
  <div id="Venezuela" class="tile" style="position: absolute; height:280px; width:340px; left:660px; top:1180px; background-color: rgba(50,100,60,0.2);"
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
  background-image: url('https://jujhar.github.io/Risk/pieces/board.jpg');
  background-size: auto;
  font-family: 'Public Pixel';
  src: url('https://rebabre.com/images/PublicPixel.woff2') format('woff2'),
              url('https://rebabre.com/images/PublicPixel.woff') format('woff');
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

#setup_menu_qck_button {
  opacity: 0.3;
  margin:-20px;
  padding:-20px;
  margin-top: -6px;
  cursor:pointer
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
  min-width: 84px;
  max-width: 85px;
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

/* SplitText */
#an1 {
  animation: fadeIn 0.1s ease-in both;
  color:chocolate;
} 

#an2 {
  animation: fadeIn 0.4s ease-in both;
  color:aquamarine;
} 

#an3 {
  animation: fadeIn 0.9s ease-in both;
  color: #888b60;
} 

@keyframes fadeIn {
	from {
		opacity: 0;
		transform: translate3d(0, -20%, 0);
	}
	to {
		opacity: 1;
		transform: translate3d(0, 0, 0);
	}
}
</style>
