Mi Ecobici
==========

Sitio web para que usuarios de [Ecobici](http://www.ecobici.df.gob.mx/)
conozcan sus estadísticas de uso del sistema, como impactan
positivamente el medio ambiente y visualicen en un mapa interactivo sus
viajes.

Capturas de pantalla
--------------------

### Versión estándar

#### Página de inicio

![Página de inicio](/doc/desktop-1.png?raw=true "Página de inicio")

#### Mapa de viajes

![Mapa de viajes](/doc/desktop-2.png?raw=true "Mapa de viajes")

#### Estadísticas

![Estadísticas](/doc/desktop-3.png?raw=true "Estadísticas")

### Versión tableta

#### Página de inicio

![Página de inicio](/doc/tablet-1.png?raw=true "Página de inicio")

#### Mapa de viajes

![Mapa de viajes](/doc/tablet-2.png?raw=true "Mapa de viajes")

#### Estadísticas

![Estadísticas](/doc/tablet-3.png?raw=true "Estadísticas")

### Versión móvil

#### Página de inicio

![Página de inicio](/doc/mobile-1.png?raw=true "Página de inicio")

#### Mapa de viajes

![Mapa de viajes](/doc/mobile-2.png?raw=true "Mapa de viajes")

#### Estadísticas

![Estadísticas](/doc/mobile-3.png?raw=true "Estadísticas")

Datos Abiertos
--------------

Mi Ecobici es posible gracias a los datos y API del [Laboratorio de
Datos](http://datos.labplc.mx), especificamente:

- [API de Ecobici](http://datos.labplc.mx/movilidad/ecobici.info)
- [Geolocalización de estaciones de
  Ecobici](http://datos.labplc.mx/datasets/view/ecobiciestaciones)
- [Distancia entre estaciones de
  Ecobici](http://datos.labplc.mx/datasets/view/ecobici_distancias)

Agradecemos al **Sistema de Transporte Individual Ecobici** de la
[Secretaria de Medio Ambiente](http://www.sedema.df.gob.mx) por su
colaboración en la apertura de datos que hacen posible a este proyecto.

Instalación
-----------

1. Es necesario un servidor web con PHP.

2. La raíz del sitio web debe apuntar a `web/htdocs`

3. El servidor debe soportar URLs limpias en la raíz del sitio web. Por
   ejemplo la configuración para nginx sería:

        location / {
          try_files $uri $uri/ /index.php?$args;
        }

Autores
-------

- [Corina Olicon](http://twitter.com/c0rysi)
- [Virgilio Pasotti](http://twitter.com/pasotti_)
- [Manuel Rábade](http://twitter.com/manuelrabade)

Licencia
--------

Esta obra está bajo una [Licencia Pública General de GNU](LICENSE.txt).
