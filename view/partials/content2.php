<div class="justify-content-start home" style="padding-bottom:20px;" >
<input type="hidden" id="timin" data-url="<?php echo getUrl("Usuario","Ofertas","timeline",false,"ajax"); ?>">
<div style="width:80%;margin:auto;height:500px;" class="card text-center">
  <div class="titles card-header">
    ACTIVIDAD
  </div>
  <div style='overflow-y:scroll;' id="timeline" class="card-body">
    <!-- <h5 class="card-title">Special title treatment</h5>
    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
    <a href="#" class="btn btn-primary">Go somewhere</a> -->
  </div>

</div>
</div>
<script>
  function load(){
    var url=$("#timin").attr("data-url");
    console.log(url);
    $.ajax({
      url:url,
      type:"POST",
      success:function(datos){
        $("#timeline").html(datos);
      }
    });
  }
  window.onload = load;
</script>