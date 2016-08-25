# Game 2048 / Slim Framework 3 

2048 es un juego que consiste en mover casillas, las cuales contienen un número que es potencia de dos, hasta conseguir que una de ellas sume 2048. El tablero consiste de una malla de 4​×4 con una configuración inicial y cuyos movimientos posibles son: Izquierda, Derecha, Arriba, Abajo. Cada vez que se realiza un movimiento, todas las casillas se desplazan en esa dirección todo lo que sea posible y si dos casillas con el mismo número quedan juntas, se sumarán. 

Slim es un framework PHP micro que le ayuda a escribir con rapidez aplicaciones web simples pero potentes y APIs.

## Instalar la Applicación

Corre este comando desde el directorio donde lo deseas instalar.

    php composer.phar install

## Instrucciones para la ejecución

  - Dirigirse desde la terminal a la ubicacion donde se establezca el proyecto
  - Ejecutarlo de esta forma:

```
$ php -S 0.0.0.0:8080 -t public public/index.php

Abrir ruta http://0.0.0.0:8080