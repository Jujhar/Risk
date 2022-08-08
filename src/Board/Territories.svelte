<script>
  export let handleClick
  export let move
</script>

<body>
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
