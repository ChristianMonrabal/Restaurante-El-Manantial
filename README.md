# El Manantial - Proyecto de Reservas y Administración de Recursos

## Objetivo

Mejorar las funcionalidades del Proyecto 01 para permitir la reserva anticipada de mesas y recursos, así como ofrecer opciones de administración de datos (usuarios, recursos, etc.) desde la aplicación. Además, se debe incorporar el uso de nuevas técnicas de programación, como el acceso a bases de datos con PDO.

**Tiempo estimado**: 35 horas (31 horas en el Centro).

## Descripción de la Actividad

A partir del Proyecto 01 realizado con el grupo, se solicita añadir las siguientes funcionalidades:

- **Reservas de recursos**: Permitir la reserva de un recurso (por ejemplo, cambreros) en un día y franja horaria específicos.
- **CRUD de usuarios**: Crear, leer, actualizar y eliminar usuarios (como cambreros, gerentes, personal de mantenimiento) por parte del administrador de la web.
- **CRUD de recursos**: Crear, leer, actualizar y eliminar recursos (como salas, mesas, sillas) por parte del administrador de la web.

### Módulos y Técnicas Involucradas:

- **M2 - Bases de Datos**: Ampliar la base de datos según sea necesario para soportar las nuevas funcionalidades.
- **M6 - Desarrollo Web en Entorno Cliente**: Uso de JavaScript para realizar acciones dinámicas sobre la misma página, como la validación de formularios y la implementación de SweetAlerts.
- **M7 - Desarrollo Web en Entorno Servidor**: Utilización de PDO para la conexión a la base de datos y la ejecución de consultas SQL.
  - Los administradores deben poder asociar imágenes a las salas y modificarlas según sea necesario.
  - El administrador debe poder realizar el mantenimiento de recursos y usuarios.
- **M8 - Despliegue de Aplicaciones Web**: Generar un nuevo repositorio en GitHub y mantenerlo sincronizado con el repositorio local.
  - Se debe incluir un archivo `README.md` en la raíz del proyecto, donde se explique el funcionamiento de la aplicación, los usuarios para realizar pruebas y otros detalles necesarios.
- **M9 - Diseño de Interfaces**: Mantener un aspecto homogéneo tanto en la parte de producción (reservas) como en la parte administrativa (CRUDs).

## Avaluación de la Actividad

La evaluación del proyecto se basa en los siguientes criterios:

- **Seguimiento**: 10%
- **Producto Final**: 55%
- **Aspecto Homogéneo**: 10%
- **Base de Datos**: 10%
- **JS y Validaciones**: 10%
- **Reserva con Fecha/Hora**: 30%
- **Ver Reservas**: 15%
- **CRUD de Administración**: 25%
- **Validación**: 35%

## Requisitos

1. **Base de Datos**: Asegúrate de que la base de datos esté correctamente ampliada para gestionar las reservas, los usuarios y los recursos de forma eficiente.
2. **Interfaz de Usuario**: La interfaz debe ser fácil de usar y mantener un diseño consistente. Debe haber un acceso claro para los usuarios y administradores.
3. **Funcionalidad de Reservas**: Los usuarios deben poder realizar reservas de recursos con fechas y horas específicas. Además, el administrador debe poder ver todas las reservas realizadas.
4. **CRUD de Recursos y Usuarios**: El administrador debe poder gestionar los recursos (mesas, salas, etc.) y los usuarios (cambreros, gerentes, etc.) mediante interfaces de creación, actualización y eliminación.

## Instrucciones de Uso

1. **Instalación**:
   - Clona el repositorio en tu máquina local utilizando:
     ```
     git clone https://github.com/tu-usuario/el-manantial.git
     ```
   - Configura la base de datos en tu servidor.
   - Asegúrate de tener las credenciales de conexión correctas en el archivo `conexion.php`.

2. **Usuarios de prueba**:
   - **Administrador**: Accede con el usuario "admin" y la contraseña "admin123" para gestionar los recursos y usuarios.
   - **Usuario común**: Accede con el usuario "usuario" y la contraseña "usuario123" para realizar reservas.

3. **Funcionalidades**:
   - **Reservas**: Los usuarios pueden reservar mesas, cambreros y otros recursos en fechas y franjas horarias específicas.
   - **Administración**: Los administradores pueden crear, editar y eliminar usuarios y recursos.
   - **Validaciones**: Se realiza una validación adecuada de los formularios, incluyendo validación de campos requeridos y formatos, junto con notificaciones de SweetAlert.

## Tecnologías Utilizadas

- **PHP**: Lenguaje de servidor para gestionar la lógica de la aplicación.
- **PDO**: Para la conexión y ejecución de consultas SQL de manera segura.
- **JavaScript**: Para la validación dinámica de formularios y notificaciones con SweetAlert.
- **MySQL**: Base de datos para almacenar los usuarios, recursos y reservas.
- **Bootstrap**: Framework para la creación de interfaces responsivas.
