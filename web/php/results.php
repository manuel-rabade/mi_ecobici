<!-- logo -->
<h1>Mi Ecobici</h1>
<div id="logo"></div>      

<!-- menu -->
<div id="menu">
  <div class="btn-wrapper">
    <button id="btn-map" type="button"
            class="btn btn-default">Mi mapa de viajes</button>
  </div>
  <div class="btn-wrapper">
    <button id="btn-stats" type="button"
            class="btn btn-default">Mis estadísticas</button>
  </div>
</div>

<!-- estadisticas -->
<div id="stats">
  <h2>Estadísticas</h2>
  <!-- por usar ecobici en lugar de un auto -->
  <div class="well well-sm">
    <span class="text">Evistaste liberar al ambiente</span>
    <span class="data"><?=$this->res['stats']['co2']?></span>
    <span class="text">Kilogramos de CO<sub>2</sub></span>
  </div>
  <div class="well well-sm">
    <span class="text">Ahorraste</span>
    <span class="data">$&nbsp;<?=$this->res['stats']['money']?></span>
    <span class="text"><?=$this->res['stats']['liters']?> litros de
        gasolina</span>    
  </div>
  <!-- promedios -->
  <div class="well well-sm">
    <span class="text">En promedio haces</span>
    <span class="data"><?=$this->res['stats']['avg_trips']?></span>
    <span class="text">viajes por día</span>
  </div>
  <div class="well well-sm">
    <span class="text">Tu viaje dura</span>
    <span class="data"><?=$this->res['stats']['avg_mins']?></span>
    <span class="text">minutos en promedio</span>
  </div>
  <div class="well well-sm">
    <span class="text">En promedio recorres</span>
    <span class="data"><?=$this->res['stats']['avg_km']?></span>
    <span class="text">kilómetros por viaje</span>
  </div>
  <!-- totales -->
  <div class="well well-sm">
    <span class="text">Has viajado</span>
    <span class="data"><?=$this->res['stats']['trips']?></span>
    <span class="text">veces en Ecobici</span>
  </div>
  <div class="well well-sm">
    <span class="text">Te has trasladado</span>
    <span class="data"><?=$this->res['stats']['km']?></span>
    <span class="text">kilómetros en total</span>
  </div>
  <div class="well well-sm">
    <span class="text">Has utilizado</span>
    <span class="data"><?=$this->res['stats']['stations']?></span>
    <span class="text">estaciones diferentes</span>
  </div>
  <div class="well well-sm">
    <span class="text">Has usado Ecobici</span>
    <span class="data"><?=$this->res['stats']['days']?></span>
    <span class="text">días diferentes</span>
  </div>
  <div class="well well-sm">
    <span class="text">Tus viajes suman</span>
    <span class="data"><?=$this->res['stats']['hrs']?></span>
    <span class="text">horas en Ecobici</span>
  </div>
  <div class="well well-sm">
    <span class="text">Has usado</span>
    <span class="data"><?=$this->res['stats']['bicycles']?></span>
    <span class="text">bicicletas diferentes</span>
  </div>
  <div class="well well-sm">
    <span class="text">Tu primer viaje fue el</span>
    <span class="date"><?=$this->res['dates']['start']?></span>
    <span class="text">Y tu último viaje el</span>
    <span class="date"><?=$this->res['dates']['end']?></span>
  </div>
  <!-- Quemaste X kcal -->
  <!-- Tu semana más activa fue del XX-XXX-XXXX al X-XXX-XXXX
       con un total de X viajes -->
  <!-- Lunes: <?=$this->res['stats']['wdays'][1]?>
       Martes: <?=$this->res['stats']['wdays'][2]?>
       Miércoles: <?=$this->res['stats']['wdays'][3]?>
       Jueves: <?=$this->res['stats']['wdays'][4]?>
       Viernes: <?=$this->res['stats']['wdays'][5]?>
       Sábado: <?=$this->res['stats']['wdays'][6]?>
       Domingo: <?=$this->res['stats']['wdays'][7]?> -->
  <!--<pre><?php print_r($this->res) ?></pre> -->
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
