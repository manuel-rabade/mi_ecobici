<?php

setlocale(LC_TIME, 'es_MX', 'es_ES');
date_default_timezone_set('America/Mexico_City');

class MiEcobici {
  /* ruta de acceso a la carpeta web */
  private $path;

  /* url base */
  private $base;

  /* seccion de la pagina */
  private $section = NULL;

  /* número de tarjeta */
  private $card = NULL;

  /* datos de csv */
  private $csv;

  /* resultado de la api */
  private $api;

  /* resultado del analisis */
  private $res;

  /* constructor de clase */
  function __construct ($srv) {
    $this->path = dirname(__FILE__) . '/..';
    $this->base = dirname($srv['PHP_SELF']);
    $this->section = basename($srv['REQUEST_URI']);
  }
  
  /* procesa request */
  function request ($req) {
    if (isset($req['card'])) {
      $this->card = trim($req['card']);
    } elseif ($this->section == 'ejemplo') {
      $this->card = 'example';
    } else {
      $this->card = NULL;
    }
    return;
  }
  
  /* devuelve url */
  function url ($path = NULL) {
    if (is_null($path)) {
      return $this->base;
    }
    if ($this->base == '/') {
      return $this->base . $path;
    }
    return implode('/', array($this->base, $path));
  }

  /* output de la clase */
  function html () {
    /* si hay seccion definida cargamos el php que corresponda */
    if ($this->section == 'acerca') {
      include($this->path . '/php/about.php');
    } elseif ($this->section == 'como-calculamos-resultados') {
      include($this->path . '/php/how.php');
    } else {
      /* si hay tarjeta analizamos respuesta e imprimimos resultado */
      if (!is_null($this->card) && $this->_analytics()) {
        include($this->path . '/php/results.php');
      } else {
        /* en caso de error o si no hay tarjeta imprimmos el home */
        include($this->path . '/php/home.php');
      }
    }
    return;
  }
  
  /* analiza la respuesta de datos.labplc.mx */
  private function _analytics () {
    /* inicializamos analisis */
    $tmp = array('total' => array('km' => 0,
                                  'trips' => 0,
                                  'mins' => 0),
                 'dates' => array(),
                 'wdays' => array('1' => 0,
                                  '2' => 0,
                                  '3' => 0,
                                  '4' => 0,
                                  '5' => 0,
                                  '6' => 0,
                                  '7' => 0),
                 'bicycles' => array(),
                 'stations' => array('top' => array('count' => 0,
                                                    'stations' => array()),
                                     'bottom' => array('count' => 100000,
                                                       'stations' => array())),
                 'trips' => array('top' => array('count' => 0,
                                                 'trips' => array()),
                                  'bottom' => array('count' => 100000,
                                                    'trips' => array()),
                                  'longest' => array('km' => 0,
                                                     'trips' => array())));

    /* inicializamos respuesta */
    $this->res = array('status' => 'ok',
                       'stations' => array(),
                       'trips' => array(),
                       'stats' => array());
    
    /* verificamos numero de tarjeta */
    if (!ctype_alnum($this->card)) {
      $this->res['status'] = 'invalidCard';
      return FALSE;
    }

    /* cargamos datos locales */
    if (!$this->_csv()) {
      $this->res['status'] = 'csvError';
      return FALSE;
    }

    /* solicitud al laboratorio de datos o cargamos ejemplo */
    $url = "http://datos.labplc.mx/movilidad/ecobici/usuario/".$this->card.".json";
    if ($this->card == 'example') {
      $url = $this->path . "/json/example.json";
    }
    $json = @file_get_contents($url, true);
    if (!$json) {
      $this->res['status'] = 'apiError';
      return FALSE;
    }
    $this->api = json_decode($json, true);
    if (!$this->api) {
      $this->res['status'] = 'noInfo';
      return FALSE;
    }

    /* procesamos cada viaje */
    foreach ($this->api['ecobici']['viajes'] as $trip) {    
      $orig = $trip['station_removed'];
      $dest = $trip['station_arrived'];

      /* saltamos si el viaje fue a la misma estacion de origen */
      if ($orig == $dest) {
        continue;
      }
      /* saltamos si alguna de las estaciones del viaje no existe */
      if (!isset($this->csv['stations'][$orig]) || 
          !isset($this->csv['stations'][$dest])) {
        continue;
      }

      /* parsing de fechas */
      $origTimestamp = strtotime($trip['date_removed']);
      $destTimestamp = strtotime($trip['date_arrived']);

      /* contamos fecha del viaje */
      $date = date('Ymd', $origTimestamp);
      if (isset($tmp['dates'][$date])) {
        $tmp['dates'][$date]++;
      } else {
        $tmp['dates'][$date] = 1;
      }

      /* contamos dia del viaje */
      $day = date('N', $origTimestamp);
      $tmp['wdays'][$day]++;

      /* contamos minutos del viaje */
      $tmp['total']['mins'] += ($destTimestamp - $origTimestamp) / 60;

      /* contamos bicicleta */
      if (isset($tmp['bicycles'][$trip['bike']])) {
        $tmp['bicycles'][$trip['bike']]++;
      } else {
        $tmp['bicycles'][$trip['bike']] = 1;
      }
      
      /* renomabramos estaciones cercanas */
      if (isset($this->csv['rename'][$orig])) {
        $orig = $this->csv['rename'][$orig];
      }
      if (isset($this->csv['rename'][$dest])) {
        $dest = $this->csv['rename'][$dest];
      }

      /* contamos estacion de origen */
      if (isset($this->res['stations'][$orig])) {
        $this->res['stations'][$orig]['orig']++;
      } else {
        $this->res['stations'][$orig] = 
          array('name' => $this->csv['stations'][$orig]['name'],
                'lat' => $this->csv['stations'][$orig]['lat'],
                'lng' => $this->csv['stations'][$orig]['lng'],
                'orig' => 1,
                'dest' => 0);
      }
      
      /* contamos estacion de destino */
      if (isset($this->res['stations'][$dest])) {
        $this->res['stations'][$dest]['dest']++;
      } else {
        $this->res['stations'][$dest] =
          array('name' => $this->csv['stations'][$dest]['name'],
                'lat' => $this->csv['stations'][$dest]['lat'],
                'lng' => $this->csv['stations'][$dest]['lng'],
                'orig' => 0,
                'dest' => 1);
      }
      
      /* analizamos las estaciones del viaje y si no hay distancia saltamos */
      $stations = $orig < $dest ? array($orig, $dest) : array($dest, $orig);
      if (!isset($this->csv['distances'][$stations[0]][$stations[1]])) {
        continue;
      }
      
      /* calculamos codigo y distancia del viaje */
      $code = implode('-', $stations);
      $distance = $this->csv['distances'][$stations[0]][$stations[1]];
      
      /* contamos viaje */
      if (isset($this->res['trips'][$code])) {
        $this->res['trips'][$code]['count']++;
      } else {
        $label = implode(' a ',array($this->csv['stations'][$stations[0]]['name'], 
                                     $this->csv['stations'][$stations[1]]['name']));
        $this->res['trips'][$code] = 
          array('count' => 1,
                'label' => $label,
                'distance' => $distance,
                'a' => array('lat' => $this->csv['stations'][$stations[0]]['lat'],
                             'lng' => $this->csv['stations'][$stations[0]]['lng']),
                'b' => array('lat' => $this->csv['stations'][$stations[1]]['lat'],
                             'lng' => $this->csv['stations'][$stations[1]]['lng']));
      }
      
      /* totales */
      $tmp['total']['km'] += $this->csv['distances'][$stations[0]][$stations[1]];
      $tmp['total']['trips']++;
      
      /* revisamos si es el viaje mas largo */
      if ($distance > $tmp['trips']['longest']['km']) {
        $tmp['trips']['longest']['km'] = $distance;
        $tmp['trips']['longest']['trips'] = array($code);
      } elseif ($distance == $tmp['trips']['longest']['km']) {
        $tmp['trips']['longest']['trips'][] = $code;
      }
    }

    /* encontramos estacion mas y menos usada */
    foreach ($this->res['stations'] as $id => $station) {
      $count = $this->res['stations'][$id]['orig'] + 
        $this->res['stations'][$id]['dest'];
      $this->res['stations'][$id]['count'] = $count;
      if ($count > $tmp['stations']['top']['count']) {
        $tmp['stations']['top']['count'] = $count;
        $tmp['stations']['top']['stations'] = array($id);
      } elseif ($count == $tmp['stations']['top']['count']) {
        $tmp['stations']['top']['stations'][] = $id;
      }
      if ($count < $tmp['stations']['bottom']['count']) {
        $tmp['stations']['bottom']['count'] = $count;
        $tmp['stations']['bottom']['stations'] = array($id);
      } elseif ($count == $tmp['stations']['bottom']['count']) {
        $tmp['stations']['bottom']['stations'][] = $id;
      }
    }

    /* marcamos peso de estacion y mas usada */
    $mStations = (2.0 - 0.5) / ($tmp['stations']['top']['count'] - 
                                $tmp['stations']['bottom']['count']);
    $bStations = 2.0 - $mStations * $tmp['stations']['top']['count'];
    foreach ($this->res['stations'] as $id => $station) {
      if (in_array($id, $tmp['stations']['top']['stations'])) {
        $this->res['stations'][$id]['frecuent'] = 1;
      }
      $this->res['stations'][$id]['weight'] = $mStations * 
        $this->res['stations'][$id]['count'] + $bStations;
    }

    /* encontramos viaje mas y menos frecuente */
    foreach ($this->res['trips'] as $code => $trip) {
      if ($trip['count'] > $tmp['trips']['top']['count']) {
        $tmp['trips']['top']['count'] = $trip['count'];
        $tmp['trips']['top']['trips'] = array($code);
      } elseif ($trip['count'] == $tmp['trips']['top']['count']) {
        $tmp['trips']['top']['trips'][] = $code;
      }
      if ($trip['count'] < $tmp['trips']['bottom']['count']) {
        $tmp['trips']['bottom']['count'] = $trip['count'];
        $tmp['trips']['bottom']['trips'] = array($code);
      } elseif ($trip['count'] == $tmp['trips']['bottom']['count']) {
        $tmp['trips']['bottom']['trips'][] = $code;
      }
    }
    
    /* marcamos peso de viaje, mas frecuentes y mas largos */
    $mTrips = (2.0 - 0.5) / ($tmp['trips']['top']['count'] - 
                             $tmp['trips']['bottom']['count']);
    $bTrips = 2.0 - $mTrips * $tmp['trips']['top']['count'];
    foreach ($this->res['trips'] as $code => $trip) {
      if (in_array($code, $tmp['trips']['top']['trips'])) {
        $this->res['trips'][$code]['frecuent'] = 1;
      }
      if (in_array($code, $tmp['trips']['longest']['trips'])) {
        $this->res['trips'][$code]['longest'] = 1;
      }
      $this->res['trips'][$code]['weight'] = $mTrips * 
        $this->res['trips'][$code]['count'] + $bTrips;
    }
    
    /* estadisticas */
    $this->res['stats']['trips'] = $tmp['total']['trips'];
    $this->res['stats']['km'] = sprintf('%.1f', $tmp['total']['km']);
    $this->res['stats']['stations'] = count($this->res['stations']);

    $avg_km = $tmp['total']['km'] / $tmp['total']['trips'];
    $this->res['stats']['avg_km'] = sprintf('%.1f', $avg_km);

    $co2 = $tmp['total']['km'] * 0.214; /* gCO2/Km */
    $this->res['stats']['co2'] = sprintf('%.1f', $co2);

    $liters = $tmp['total']['km'] / 10.91; /* Km/L */
    $this->res['stats']['liters'] = sprintf('%.0f', $liters);

    $money = 12.22 * $liters; /* pesos por litro ene/2014 */
    $this->res['stats']['money'] = sprintf('%.0f', $money);
    
    $this->res['stats']['days'] = count($tmp['dates']);

    $avg_trips = $tmp['total']['trips'] / count($tmp['dates']);
    $this->res['stats']['avg_trips'] = sprintf('%.1f', $avg_trips);

    $avg_min = $tmp['total']['mins'] / $tmp['total']['trips'];
    $this->res['stats']['avg_mins'] = sprintf('%.0f', $avg_min);

    $hrs = $tmp['total']['mins'] / 60;
    $this->res['stats']['hrs'] = sprintf('%.0f', $hrs);

    $this->res['stats']['bicycles'] = count($tmp['bicycles']);
    
    $this->res['stats']['wdays'] = $tmp['wdays'];

    $dates = array_keys($tmp['dates']);
    sort($dates);
    $start = strftime('%e de %B de %G', strtotime($dates[0]));
    $this->res['dates']['start'] = $start;
    $end = strftime('%e de %B de %G', strtotime($dates[count($dates) - 1]));
    $this->res['dates']['end'] = $end;

    //print_r($this->res);
    return TRUE;
  }
  
  /* procesa los archivos csv */
  private function _csv () {
    $this->csv = array('stations' => array(),
                       'distances' => array());
    
    $h = @fopen($this->path . "/csv/stations.csv", "r");
    if (!$h) {
      return FALSE;
    }
    fgetcsv($h, 1000, ",");
    while (($d = fgetcsv($h, 1000, ",")) !== FALSE) {
      $name = implode(' esquina con ', array($d[1], $d[2]));
      $this->csv['stations'][$d[0]] = array('lat' => $d[7],
                                            'lng' => $d[6],
                                            'name' => $name);
    }
    fclose($h);

    $h = @fopen($this->path . "/csv/distances.csv", "r");
    if (!$h) {
      return FALSE;
    }
    fgetcsv($h, 1000, ",");
    while (($d = fgetcsv($h, 1000, ",")) !== FALSE) {
      $this->csv['distances'][$d[0]][$d[1]] = $d[2];
    }
    fclose($h);

    $h = @fopen($this->path . "/csv/rename.csv", "r");
    if (!$h) {
      return FALSE;
    }
    fgetcsv($h, 1000, ",");
    while (($d = fgetcsv($h, 1000, ",")) !== FALSE) {
      $this->csv['rename'][$d[0]] = $d[1];
    }
    fclose($h);
    
    return TRUE;
  }
}

?>
