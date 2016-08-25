# Game 2048 / Slim Framework 3 

2048 es un juego que consiste en mover casillas, las cuales contienen un número que es potencia de dos, hasta conseguir que una de ellas sume 2048. El tablero consiste de una malla de 4​×4 con una configuración inicial y cuyos movimientos posibles son: Izquierda, Derecha, Arriba, Abajo. Cada vez que se realiza un movimiento, todas las casillas se desplazan en esa dirección todo lo que sea posible y si dos casillas con el mismo número quedan juntas, se sumarán. 

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project slim/slim-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

That's it! Now go build something cool.
