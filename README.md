
## API TeamSpeak 3 

API para administrar y gestionar cuentas de usuarios e información de los canales del servidor

### Configuraciones

Para realizar peticiones a la *API*, primero se deben configurar lo parámetros de conexión a la base de datos,
modificando en el archivo `application/config/database.php` los siguientes campos:
```
    'hostname' => 'nombre_host',
	'username' => 'nombre_usuario',
	'password' => 'contraseña_usuario',
	'database' => 'nombre_bd',
```
Luego, se debe modificar el archivo `application/libraries/Teamspeak.php` indicando los siguientes valores:
```
    private $host = 'host_ts3';
    private $username = 'serveradmin_query_name';
    private $password = 'serveradmin_query_pass';
```
### Uso

Puede enviar solicitudes del tipo *GET*, *POST*, *PUT*, *DELETE*, que contengan datos en formato *JSON* a la dirección
donde se encuentre instalada la aplicación (Por defecto: [`http://localhost/api/index.php/`](http://localhost/api/index.php/))
e indicando el nombre del controlador.

### Ejemplos

#### Consulta de canales creados.

```
URL: `http://localhost/api/index.php/channels/`
TYPE: GET
```
#### Crear nuevo canal.

```
URL: `http://localhost/api/index.php/channels/`
TYPE: POST
DATA:
    {
        "can_cli_id":"1",
        "can_nombre":"Canal de prueba",
        "can_contrasena":"1234"
    }
```