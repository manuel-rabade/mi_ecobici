/* globales */
var meMap;
var meWindow;
var meInfo = { };

/* colores */
var colors = { red : '#DF151A',
               green : '#00DA3C',
               blue : '#0069FF',
               gray : '#ADB39B', 
               stell : '#494E54' };

/* eventos de la pagina */
$(document).ready(function() {
    $('#btn-map').click(showMap);
    $('#btn-stats').click(showStats);
    $('#share-fb').click(shareFB);
    $('#share-tw').click(shareTW);
    drawMap();
});

/* mostrar mapa o estadisticas */
function showStats (event) {
    $('#stats').css('display', 'block');
    $('#map').css('display', 'none');
    $('#btn-map').css('display', 'inline-block');
    $('#btn-stats').css('display', 'none');
    ga('send', {
        'hitType': 'event',
        'eventCategory': 'button',
        'eventAction': 'click',
        'eventLabel': 'showStats' });
}
function showMap (event) {
    $('#stats').css('display', 'none');
    $('#map').css('display', 'block');
    $('#btn-map').css('display', 'none');
    $('#btn-stats').css('display', 'inline-block');
    if (meMap) {
        google.maps.event.trigger(meMap, 'resize');
    } else {
        drawMap();
    }
    ga('send', {
        'hitType': 'event',
        'eventCategory': 'button',
        'eventAction': 'click',
        'eventLabel': 'showMap' });
}

/* mapa */
function drawMap () {
    var mapOpts = { zoom: 14, 
                    center: new google.maps.LatLng(19.4328,
                                                   -99.1333) };
    meMap = new google.maps.Map(document.getElementById('map'), mapOpts);
    var bounds = new google.maps.LatLngBounds();

    /* estaciones */
    for (id in data.stations) {
        /* marcadores */
        var color, scale;
        if (data.stations[id].orig > 0 || data.stations[id].dest > 0) {
            /* por ahora solo marcamos las estaciones usadas */
            color = colors.stell;
            scale = data.stations[id].weight * 5;
        } else {
            /* no aplica */
            color = colors.gray;
            scale = 2;
        }
        var station = new google.maps.Marker({
            stationId: id,
            map: meMap,
            position: new google.maps.LatLng(data.stations[id].lat,
                                             data.stations[id].lng),
            icon: {
                fillColor: color,
                fillOpacity: 0.75,
                strokeWeight: 0,
                path: google.maps.SymbolPath.CIRCLE,
                scale: scale,
                zIndex: 10
            }
        });
        bounds.extend(new google.maps.LatLng(data.stations[id].lat,
                                             data.stations[id].lng));

        /* ventana de informacion */
        var info = '<div class="infoWindow">';
        info += '<p><strong>' + data.stations[id].name + '</strong></p>';
        if (data.stations[id].orig + data.stations[id].dest > 0) {
            info += '<p>En esta estación has iniciado ';
            info += viajes(data.stations[id].orig) + ' y terminado ';
            info += viajes(data.stations[id].dest) + '</p>';
        } else {
            info += '<p>Nunca has usado esta estación</p>';
        }
        //info += '<p>Id: ' + id + '</p>';
        info += '</div>';
        meInfo[id] = info;

        /* click en estacion */
        google.maps.event.addListener(station, 'click', function (event) {
            if (meWindow) {
                meWindow.close();
            }
            meWindow = new google.maps.InfoWindow({
                content: meInfo[this.stationId],
                maxWidth: 200
            });
            meWindow.open(meMap, this);
            ga('send', {
                'hitType': 'event',
                'eventCategory': 'marker',
                'eventAction': 'click',
                'eventLabel': 'showStationInfo' });
        });
    }

    /* viajes */
    for (id in data.trips) {
        /* ruta */
        var color, weight;
        color = colors.green;
        weight = data.trips[id].weight * 5;
        var trip = new google.maps.Polyline({
            tripId: id,
            path: [ new google.maps.LatLng(data.trips[id].a.lat,
                                           data.trips[id].a.lng),
                    new google.maps.LatLng(data.trips[id].b.lat,
                                           data.trips[id].b.lng) ],
            geodesic: false,
            strokeColor: color,
            strokeOpacity: 0.5,
            strokeWeight: weight,
            zIndex: 20
        });
        trip.setMap(meMap);

        /* ventana de informacion */
        var info = '<div class="infoWindow">';
        info += '<p><strong>' + data.trips[id].label + '</strong></p>';
        info += '<p>Has recorrido esta ruta ';
        info += veces(data.trips[id].count) + '</p>';
        info += '<p>La distancia de esta ruta es de ';
        info += data.trips[id].distance + '&nbsp;Kilómetros</p>';
        info += '</div>';
        //info += '<p>Code: ' + id + '</p>';
        meInfo[id] = info;
        
        /* click en viaje */
        google.maps.event.addListener(trip, 'click', function (event) {
            if (meWindow) {
                meWindow.close();
            }
            meWindow = new google.maps.InfoWindow({
                content: meInfo[this.tripId],
                maxWidth: 200
            });
            meWindow.setPosition(event.latLng);
            meWindow.open(meMap);
            ga('send', {
                'hitType': 'event',
                'eventCategory': 'polyline',
                'eventAction': 'click',
                'eventLabel': 'showTripInfo' });
        });
    }

    /* centramos y hacemos zoom en el mapa */
    meMap.fitBounds(bounds);
    meMap.panToBounds(bounds);
}

/* compartir */
function shareFB (event) {
    ga('send', {
        'hitType': 'event',
        'eventCategory': 'link',
        'eventAction': 'click',
        'eventLabel': 'shareFB' });    
    return false;
}
function shareTW (event) {
    ga('send', {
        'hitType': 'event',
        'eventCategory': 'link',
        'eventAction': 'click',
        'eventLabel': 'shareTW' });    
    return false;
}

/* redaccion */
function viajes (count) {
    if (count == 1) {
        return count + '&nbsp;viaje';
    } else {
        return count + '&nbsp;viajes';
    }
}
function veces (count) {
    if (count == 1) {
        return count + '&nbsp;vez';
    } else {
        return count + '&nbsp;veces';
    }
}
