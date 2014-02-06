<div id="page">
  <div id="valign">
    <div id="home">
      <h1>Mi Ecobici</h1>
      <div id="welcome"></div>      
      <?php if (is_null($this->card)) { ?> 
      <p class="lead">Ingresa tu número de tarjeta Ecobici para
        visualizar tus viajes y conocer tus estadisticas</p>
      <?php } else { ?> 
      <?php if ($this->res['status'] == 'invalidCard') { ?>
      <p class="lead error">El número de tarjeta es incorrecto (debe
        llevar solamente letras y números)</p>
      <?php } elseif ($this->res['status'] == 'noInfo') { ?>
      <p class="lead error">El número de tarjeta es incorrecto,
         no tiene viajes o no esta en la base de datos abiertos</p>
      <?php } else { ?>
      <p class="lead error"><?=$this->res['status']?> :(</p>
      <?php } ?>
      <?php } ?>
      <form id="form-home" method="post">
        <label for="input-email"
               class="sr-only">Número de tarjeta Ecobici</label>
        <input id="input-card" class="form-control" type="text"
               placeholder="Número de tarjeta"
               value="<?=$this->card?>" name="card">
        <input id="btn-continue" class="btn btn-default" type="submit"
               value="Continuar">
      </form>
      <p id="help" class="help-block">Si no eres usuario de Ecobici o
        no tienes tu tarjeta puedes ver un 
        <a href="<?=$this->url('ejemplo')?>">ejemplo de
          resultados</a> y cualquier comentario o
        sugerencia  <a id="mail-contact"
                       href="mailto:contacto@miecobici.mx">escríbenos</a></p>
    </div>
  </div>
</div>

<!-- google analytics -->
<script>
  ga('set', 'page', '/');
  ga('set', 'title', 'Home');
  ga('send', 'pageview');
  <?php if (!is_null($this->card)) { ?>
  ga('send', {
  'hitType': 'event',
  'eventCategory': 'message',
  'eventAction': 'error',
  'eventLabel': '<?=$this->res['status']?>',
  'nonInteraction': 1 });
  <?php } ?>
  $('#mail-contact').click(function () {
  ga('send', 'event', 'link', 'click', 'contact');
  });
</script>
