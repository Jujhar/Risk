<script>
  import { onMount } from 'svelte';
  onMount(() => {
  })

  let turnCount = 3;

</script>

<svelte:window on:keydown={handleKeydown}/>

<html data-theme="retro" lang="en">
<body>


  <!-- start screen -->
  <!-- vs computer
  vs human

 starting units
 40

 starting generals
 starting infantry
 starting artillery -->




  <h1>Risk!</h1>

  <!-- <div style="position: absolute; left:200px; top:390px;">
  <img src="general.webp" width=100 />
  </div> -->

  <!-- turn count -->
  <div style="position: absolute; left:200px; top:690px;">
  <big><big style="font-size: 4em;">{turnCount}</big></big>
  </div>

  <!-- Regions -->
  <!-- America -->
  <div id="Alaska" style="position: absolute; height:200px; width:300px; left:0px; top:390px; background-color: rgba(250,20,20,0.2);">
  </div>

  <div id="Northwest Territory" style="position: absolute; height:200px; width:400px; left:300px; top:420px; background-color: rgba(250,20,20,0.2);">
    <div style="position: relative; left:2px; top:3px;">
      <img src="general.webp" width=100 />
    </div>

    <div style="position: relative; left:4px; top:1px;">
      <img src="pieces/artillery.png" width=100 />
    </div>

    <div style="position: relative; left:40px; top:-202px;">
      <img src="pieces/unit.png" width=200 />
    </div>


  </div>

  <div id="Alberta" style="position: absolute; height:170px; width:310px; left:320px; top:630px; background-color: rgba(250,20,20,0.2);">
  </div>

  <div id="Ontario" style="position: absolute; height:190px; width:250px; left:630px; top:630px; background-color: rgba(250,20,20,0.2);">
  </div>

  <div id="Quebec" style="position: absolute; height:240px; width:250px; left:880px; top:630px; background-color: rgba(250,20,20,0.2);">
  </div>

  <div id="Western United States" style="position: absolute; height:240px; width:250px; left:380px; top:810px; background-color: rgba(250,20,20,0.2);">
  </div>

  <div id="Eastern United States" style="position: absolute; height:280px; width:250px; left:680px; top:840px; background-color: rgba(250,20,20,0.2);">
  </div>

  <div id="Central America" style="position: absolute; height:180px; width:150px; left:410px; top:1090px; background-color: rgba(250,20,20,0.2);">
  </div>

  <div id="Greenland" style="position: absolute; height:380px; width:450px; left:1010px; top:210px; background-color: rgba(250,20,20,0.2);">
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
</style>
