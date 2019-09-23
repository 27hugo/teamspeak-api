## API REQUEST GUIDE

A continuación se encuentran todos los métodos y direcciones de las cuales podemos realizar
peticiones, esto con fines de realizar pruebas para todos los posibles casos y asi debuguear
la aplicación en caso de fallos.
### Uso

#### 1 Canales

##### 1.1 Consulta de canales creados.
GET => http://localhost/api/index.php/channels

##### 1.2 Consulta de canal por id.
GET => http://localhost/api/index.php/channels/find/:channel_id

##### 1.3 Consulta de canales por cliente.
GET => http://localhost/api/index.php/channels/findbycliid/:client_id

##### 1.4 Consulta de canales entre dos fechas.
POST => http://localhost/api/index.php/channels/findbetween
```
{
    "first_date":"2019-01-01",
    "second_date":"2019-02-01"
}
```

##### 1.5 Cambiar nombre del canal.
PUT => http://localhost/api/index.php/channels/updatechannelname
```
{
    "can_id":"1",
    "can_nombre":"nombre_canal"
}
```

##### 1.6 Cambiar contraseña del canal.
PUT => http://localhost/api/index.php/channels/updatechannelpassword
```
{
    "can_id":"1",
    "can_contrasena":"12345"
}
```

##### 1.7 Crear nuevo canal.
POST => http://localhost/api/index.php/channels/create
```
{
    "can_cli_id":"1",
    "can_nombre":"Canal de prueba",
    "can_contrasena":"1234"
}
```
##### 1.8 Eliminar canal.
DELETE => http://localhost/api/index.php/channels/delete/:channel_id

#### 2 Clientes

##### 2.1 Consulta de clientes registrados.
GET => http://localhost/api/index.php/clients

##### 2.2 Consulta de clientes en linea.
GET => http://localhost/api/index.php/clients/online

##### 2.3 Consulta de cliente por id.
GET => http://localhost/api/index.php/clients/find/:client_id

##### 2.4 Actualizar datos cliente.
PUT => http://localhost/api/index.php/clients/update
```
{
    "cli_id":"1",
    "cli_nombre":"Juan",
    "cli_region":"Valparaíso",
    "cli_ciudad":"Valparaíso",
    "cli_nacimiento":"1994-04-01"
}
```
##### 2.5 Eliminar cliente.
DELETE => http://localhost/api/index.php/clients/delete/:client_id

#### 3 Login

##### 3.1 Iniciar sesion con cuenta existente.
POST => http://localhost/api/index.php/login
```
{
    "log_correo":"ejemplo@ejemplo.com",
    "log_contrasena":"123456"
}
```

##### 3.2 Registrar nueva cuenta.
POST => http://localhost/api/index.php/login/register
```
{
    "log_correo":"ejemplo@dominio.com",
    "log_contrasena":"123456",
    "cli_nombre":"Juan",
    "cli_alias":"JuanAlias",
    "cli_region":"Valparaíso",
    "cli_ciudad":"Viña del Mar",
    "cli_nacimiento":"1994-04-01"
}
```

##### 3.3 Cambiar contraseña cuenta.
PUT => http://localhost/api/index.php/login/changepassword
```
{
    "log_cli_id":"1",
    "log_contrasena":"nueva_contrasena"
}
```
