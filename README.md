Mi Ecobici
==========

Sitio web para que usuarios de Ecobici:

- Conozcan sus estadísticas de uso del sistema

- Visualizen en un mapa interactivo sus viajes

Instalacion
-----------

1. Es necesario un servidor web con PHP.

2. La raíz del sitio web debe apuntar a `web/htdocs`

3. El servidor debe soportar URLs limpias en la raiz del sitio web. Por
   ejemplo la configuración para nginx sería:

   location / {
     try_files $uri $uri/ /index.php?$args;
   }
