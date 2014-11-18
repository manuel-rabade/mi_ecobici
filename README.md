Mi Ecobici
==========

Sitio web para que usuarios de Ecobici conozcan sus estadísticas de uso
del sistema y visualicen en un mapa interactivo sus viajes.

Instalacion
-----------

1. Es necesario un servidor web con PHP.

2. La raíz del sitio web debe apuntar a `web/htdocs`

3. El servidor debe soportar URLs limpias en la raiz del sitio web. Por
   ejemplo la configuración para nginx sería:

        location / {
          try_files $uri $uri/ /index.php?$args;
        }

Capturas de pantalla
--------------------

### Versión estándar

#### Página de inicio

![Página de inicio](/doc/img/desktop-1.png?raw=true "Página de inicio")

#### Mapa de viajes

![Mapa de viajes](/doc/img/desktop-2.png?raw=true "Mapa de viajes")

#### Estadísticas

![Estadísticas](/doc/img/desktop-3.png?raw=true "Estadísticas")

### Versión tableta

#### Página de inicio

![Página de inicio](/doc/img/tablet-1.png?raw=true "Página de inicio")

#### Mapa de viajes

![Mapa de viajes](/doc/img/tablet-2.png?raw=true "Mapa de viajes")

#### Estadísticas

![Estadísticas](/doc/img/tablet-3.png?raw=true "Estadísticas")

### Versión móvil

#### Página de inicio

![Página de inicio](/doc/img/mobile-1.png?raw=true "Página de inicio")

#### Mapa de viajes

![Mapa de viajes](/doc/img/mobile-2.png?raw=true "Mapa de viajes")

#### Estadísticas

![Estadísticas](/doc/img/mobile-3.png?raw=true "Estadísticas")

Autores
-------

[Corina Olicon](http://twitter.com/c0rysi), [Virgilio
Pasotti](http://twitter.com/pasotti_), [Manuel
Rábade](http://twitter.com/manuelrabade).

Licencia
--------

Esta obra está bajo una [Licencia Pública General de GNU](LICENSE.txt).
