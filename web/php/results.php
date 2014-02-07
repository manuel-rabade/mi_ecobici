<!-- logo -->
<h1>Mi Ecobici</h1>
<div id="logo"></div>      

<!-- menu -->
<div id="menu">
  <div class="btn-wrapper">
    <button id="btn-stats" type="button"
            class="btn btn-default" disabled="true">Estadísticas</button>
  </div>
  <div class="btn-wrapper">
    <button id="btn-map" type="button"
            class="btn btn-default">Mapa de viajes</button>
  </div>
</div>

<!-- estadisticas -->
<div id="stats">
  <h2>Estadísticas</h2>
  <div class="group">
    <h3>En total</h3>
    <ul>
      <li>Has viajado <?=$this->res['stats']['trips']?> veces en
        Ecobici</li>
      <li>Te has trasladado <?=$this->res['stats']['km']?>
        kilómetros</li>
      <li>Has utilizado <?=$this->res['stats']['stations']?>
        estaciónes</li>
      <li>Has usado Ecobici X días</li>
    </ul>
    <h3>En promedio</h3>
    <ul>
      <li>Recorres <?=$this->res['stats']['avg']?> kilómetros por
        viaje</li>
      <li>Haces X viajes por día</li>
      <li>Tus viajes duran X minutos</li>
  </div>
  <div class="group">
    <h3>Por utilizar Ecobici en lugar de un auto</h3>
    <ul>
      <li>Evistaste liberar al medio ambiente
        <?=$this->res['stats']['co2']?> Kg de CO<sub>2</sub></li>
      <li>Ahorraste $
        <?=$this->res['stats']['money']?> 
        (<?=$this->res['stats']['liters']?> litros de
        gasolina)</li>
      <li>Quemaste X kcal</li>
    </ul>
    <h3>Interesante</h3>
    <ul>
      <li>Tu primer viaje fue el XX-XXX-XXXX y el último el
        XX-XXX-XXXX</li>
      <li>Has utilizado Ecobici X horas continuas en X bicicletas
        diferentes</li>
      <li>El día de la semana que más ocupas Ecobici es el XXXXX</li>
      <li>Tu semana más activa fue del XX-XXX-XXXX al X-XXX-XXXX con un
        total de X viajes</li>
    </ul>
  </div>
</div>

<!-- google maps -->
<div id="map">
  <h2>Mapa de viajes</h2>
</div>

<!-- pie de pagina -->
<div id="bottom">
  <p>Comparte tu perfil en <a id="share-fb" href="#">Facebook</a>
    o <a id="share-tw" href="#">Twitter</a></p>
  <p><a href="<?=$this->url('como-calculamos-resultados')?>"
        target="_blank">Como calculamos tus resultados</a></p>
  <p><a href="<?=$this->url()?>">Consulta otra tarjeta</a></p>
  <p><a href="<?=$this->url('acerca')?>" target="_blank">Acera de Mi
        Ecobici</a></p>
</div>

<!-- errores -->
<div id="narrow">
   <div id="error">
     <p>No se puede visualizar Mi Ecobici por que tu pantalla es muy
       angosta :(</p>
     <p>Intenta girar tu dispositivo o
       visita <strong>miecobici.mx</strong> desde tu computadora o
       tablet</p>
   </div>
</div>
<div id="short">
   <div id="error">
     <p>No se puede visualizar Mi Ecobici por que tu pantalla es muy
       corta :(</p>
     <p>Intenta girar tu dispositivo o
       visita <strong>miecobici.mx</strong> desde tu computadora o
       tablet</p>
   </div>
</div>

<!-- javascript -->
<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false"></script>
<script type="text/javascript">
  var data = <?=json_encode($this->res)?>;
</script>
<script src="js/results.js"></script>

<!-- google analytics -->
<script>
  <?php if ($this->card == 'example') { ?>
  ga('set', 'page', '/example');
  ga('set', 'title', 'Example');
  <?php } else { ?>
  ga('set', 'page', '/results');
  ga('set', 'title', 'Results');
  <?php } ?>
  ga('set', 'dimension1', '<?=$this->card?>');
  ga('send', 'pageview');
</script>
