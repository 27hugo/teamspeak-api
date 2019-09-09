
## API TeamSpeak 3 

API para administrar y gestionar cuentas de usuarios e información de los canales del servidor. Con
esta aplicación se puede manejar la creación, edición y eliminación de canales de forma remota contando
con un usuario autorizado para esto.

### Configuraciones

Para realizar peticiones a la *API*, primero se deben configurar los parámetros de conexión a la base de datos,
modificando en el archivo `application/config/database.php` los siguientes campos:
```
    'hostname' => 'nombre_host',
	'username' => 'nombre_usuario',
	'password' => 'contraseña_usuario',
	'database' => 'nombre_bd',
```
Luego, se debe modificar el archivo `application/config/teamspeak.php` indicando los siguientes valores:
```
    $config['host'] = 'servidor_ts3';
    $config['username'] = 'usuario_serveradmin';
    $config['password'] = 'contraseña_serveradmin';
    $config['port'] = 'puerto_servidor_ts3';
    $config['queryport'] = 'puerto_query_ts3';
```
### Uso

Puede enviar solicitudes del tipo **GET**, **POST**, **PUT**, **DELETE**, que contengan datos en formato **JSON** a la dirección
donde se encuentre instalada la aplicación (Por defecto: [`http://localhost/api/index.php/`](http://localhost/api/index.php/))
e indicando el nombre del controlador.

### Ejemplos

#### Consulta de canales creados.

```
URL: http://localhost/api/index.php/channels/
TYPE: GET
```
#### Iniciar sesion con cuenta existente.

```
URL: http://localhost/api/index.php/login/
TYPE: POST
DATA:
    {
        "log_correo":"email@example.com",
        "log_contrasena":"123456"
    }
```
#### Crear nuevo canal.

```
URL: http://localhost/api/index.php/channels/
TYPE: POST
DATA:
    {
        "can_cli_id":"1",
        "can_nombre":"Canal de prueba",
        "can_contrasena":"1234"
    }
```