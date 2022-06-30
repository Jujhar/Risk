<script>
  import { onMount } from 'svelte';
  onMount(() => {
  })

</script>

<svelte:window on:keydown={handleKeydown}/>

<html data-theme="retro" lang="en">
<body>

  <h1>Risk!</h1>

  <div style="position: absolute; left:200px; top:390px;">
  <img src="general.webp" width=100 />
  </div>

  <!-- Regions -->
  <div id="Alaska" style="position: absolute; height:200px; width:300px; left:0px; top:390px; background-color: red; opacity:0.2;">
  </div>

  <div id="Northwest Territory" style="position: absolute; height:200px; width:400px; left:300px; top:420px; background-color: red; opacity:0.2;">
  </div>

  <div id="Alberta" style="position: absolute; height:170px; width:310px; left:320px; top:630px; background-color: red; opacity:0.2;">
  </div>


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
