# Bookify

**Bookify** es una aplicación web de gestión de una biblioteca digital desarrollada con **PHP, MariaDB, HTML, CSS y JavaScript**.

La aplicación permite consultar un catálogo de libros, gestionar usuarios y préstamos y diferenciar las funcionalidades disponibles para usuarios y administradores.

El proyecto integra **frontend, lógica de servidor y base de datos**, trabajando aspectos como autenticación, sesiones, roles, consultas SQL, relaciones entre tablas y gestión de estados.

---

## ✨ Funcionalidades

### 📖 Catálogo

* Visualización del catálogo de libros.
* Organización por categorías.
* Búsqueda y filtrado de libros.
* Ficha individual de cada libro.
* Información sobre autor, categoría y disponibilidad.
* Sistema de reservas.

### 👤 Área de usuario

Cada usuario dispone de un panel personal desde el que puede:

* Consultar sus datos.
* Ver sus préstamos activos.
* Consultar las próximas devoluciones.
* Identificar préstamos vencidos.
* Consultar su historial de préstamos.
* Comprobar si un préstamo fue devuelto a tiempo o fuera de plazo.

Los préstamos se clasifican según su situación:

* **Activo**
* **Próximo a vencer**
* **Vencido**
* **Devuelto**

### 🔐 Panel de administración

El sistema incorpora un área específica para usuarios con rol de administrador.

Desde el panel de administración se pueden:

* Consultar los préstamos activos.
* Ver qué usuario tiene cada libro.
* Consultar las fechas de préstamo y devolución.
* Identificar préstamos fuera de plazo.
* Identificar devoluciones previstas para el día actual.
* Registrar la devolución de un libro.

El acceso al panel está protegido mediante **autenticación y control de roles**.

---

## 🔄 Gestión de préstamos

Bookify controla el ciclo de vida de un préstamo:

                 ┌─────────────────┐
                 │     Activo      │
                 └────────┬────────┘
                          │
              ┌───────────┴───────────┐
              │                       │
       Dentro de plazo        Fecha próxima
              │                       │
              │               Próximo a vencer
              │
              └───────────┬───────────┘
                          │
                    Pasa la fecha
                          │
                          ▼
                    Fuera de plazo
                          │
                          │
              Admin registra devolución
                          │
                          ▼
                       Devuelto

Cuando el administrador registra una devolución, el sistema comprueba automáticamente si se ha superado la fecha límite.

El resultado se almacena en la base de datos mediante el campo `fuera_de_plazo`, permitiendo mostrar posteriormente esta información en el historial del usuario.

---

## 🗄️ Base de datos

La aplicación utiliza **MariaDB** como sistema de gestión de base de datos.

La estructura principal está formada por las siguientes tablas:

┌──────────────┐
│   usuarios   │
└──────┬───────┘
       │
       │ 1:N
       ▼
┌──────────────┐
│  prestamos   │
└──────┬───────┘
       │
       │ N:1
       ▼
┌──────────────┐
│    libros    │
└──────┬───────┘
       │
       │ N:1
       ▼
┌──────────────┐
│  categorias  │
└──────────────┘

### Principales tablas

#### `usuarios`

Almacena la información de los usuarios y su rol dentro de la aplicación.

#### `libros`

Contiene la información de los libros disponibles en el catálogo.

#### `categorias`

Permite organizar los libros por categorías.

#### `prestamos`

Relaciona usuarios y libros y almacena información como:

* Fecha de préstamo.
* Fecha límite de devolución.
* Estado del préstamo.
* Si la devolución se realizó fuera de plazo.

Para obtener información relacionada se utilizan consultas SQL con `INNER JOIN`.

---

## 🛠️ Tecnologías

### Frontend

* **HTML5**
* **CSS3**
* **JavaScript**

### Backend

* **PHP 8.4**
* **PDO**
* **Sesiones PHP**

### Base de datos

* **MariaDB**
* **SQL**

### Herramientas

* **Git**
* **GitHub**
* **GitHub Codespaces**

---

## 🔒 Seguridad

Durante el desarrollo se han aplicado diferentes medidas básicas de seguridad.

### Contraseñas

Las contraseñas no se almacenan directamente, sino utilizando las funciones de PHP:

```php
password_hash()
```

y:

```php
password_verify()
```

### Consultas preparadas

Las consultas que reciben datos del usuario utilizan **PDO y consultas preparadas**:

```php
$stmt = $pdo->prepare($sql);
$stmt->execute([$idusuario]);
```

Esto evita construir directamente las consultas SQL con los datos recibidos.

### Sesiones

Las sesiones de PHP se utilizan para mantener la autenticación del usuario y controlar el acceso a las diferentes áreas de la aplicación.

### Roles

Los usuarios tienen asociado un rol:

usuario
admin

El panel de administración comprueba el rol antes de permitir el acceso.

### Escape de contenido

Para mostrar datos procedentes de la base de datos se utiliza `htmlspecialchars()` para evitar la interpretación de contenido HTML introducido como texto.

---

## 💻 Algunos aspectos técnicos

### Conexión con MariaDB

La aplicación utiliza PDO para establecer la conexión con la base de datos y ejecutar consultas.

### Relaciones entre tablas

Los datos necesarios para mostrar préstamos se obtienen relacionando las tablas mediante SQL.

Por ejemplo, para mostrar un préstamo junto con el usuario y el libro correspondiente:

```sql
SELECT
    prestamos.id,
    usuarios.nombre,
    libros.titulo
FROM prestamos
INNER JOIN usuarios
    ON prestamos.id_usuario = usuarios.id
INNER JOIN libros
    ON prestamos.id_libro = libros.id;
```

### Gestión de fechas

PHP se utiliza para trabajar con las fechas de préstamo y devolución y determinar si un préstamo está:

* Dentro de plazo.
* Próximo a vencer.
* Vencido.

### JavaScript

JavaScript se utiliza para mejorar la interacción de la interfaz.

Por ejemplo, el registro de devolución utiliza un **modal de confirmación**, evitando realizar directamente una acción que modifica el estado del préstamo.

---

## 🎨 Diseño e interfaz

Bookify cuenta con una identidad visual propia basada en una combinación de:

* Azul oscuro.
* Burdeos.
* Beige.
* Rosa.
* Blanco.
* Verde para estados positivos.

Se han creado estilos específicos para las diferentes áreas de la aplicación, manteniendo una identidad visual común entre:

* Catálogo.
* Fichas de libros.
* Panel de usuario.
* Panel de administración.
* Formularios.
* Modales.

Los diferentes estados de los préstamos también utilizan elementos visuales diferenciados para facilitar su identificación.

---

## 🎯 Qué demuestra este proyecto

Bookify reúne diferentes conocimientos de desarrollo web en una misma aplicación:

* Desarrollo backend con PHP.
* Programación estructurada.
* Conexión PHP-MariaDB.
* Diseño y consultas SQL.
* Relaciones entre tablas.
* `INNER JOIN`.
* PDO y consultas preparadas.
* Formularios y métodos `GET` y `POST`.
* Sesiones y autenticación.
* Gestión de roles y permisos.
* Gestión de fechas y estados.
* JavaScript para interacción con la interfaz.
* Manipulación del DOM.
* Modales.
* HTML y CSS.
* Organización del código.
* Control de versiones con Git y GitHub.

---

## 🚀 Posibles ampliaciones

La arquitectura actual permite continuar ampliando la aplicación. Algunas mejoras planteadas para futuras versiones serían:

### 📚 Gestión de ejemplares

Actualmente cada registro de la tabla `libros` representa un libro disponible para préstamo.

Una futura ampliación permitiría diferenciar entre **título y ejemplar físico**, de forma que un mismo libro pueda disponer de varias copias.

Por ejemplo:

El Hobbit
│
├── Ejemplar 1 → Prestado
├── Ejemplar 2 → Disponible
└── Ejemplar 3 → Disponible

Esto permitiría gestionar de forma independiente la disponibilidad de cada ejemplar.

### 🔔 Notificaciones

* Avisos próximos a la fecha de devolución.
* Notificaciones de préstamos vencidos.
* Confirmaciones de reserva.

### 📊 Estadísticas de administración

* Número de préstamos.
* Libros más prestados.
* Usuarios con más préstamos.
* Libros disponibles y prestados.
* Evolución de los préstamos.

### 🔎 Mejoras del catálogo

* Nuevos filtros y opciones de búsqueda.
* Ordenación por diferentes criterios.
* Mejoras en la gestión de disponibilidad.
* Paginación del catálogo.

### 🌐 API y datos

* Desarrollo de una API REST.
* Intercambio de información mediante JSON.
* Separación progresiva entre frontend y backend.

### 👤 Gestión de usuarios

* Edición de datos personales.
* Gestión más avanzada de permisos.
* Mejoras en la administración de usuarios.

Estas funcionalidades se plantean como posibles evoluciones de la aplicación y no forman parte del alcance de la versión actual.

---

## 👩‍💻 Sobre el proyecto

Bookify forma parte de mi **portfolio de desarrollo web** y representa un proyecto en el que he trabajado de forma práctica diferentes tecnologías y conceptos aprendidos durante mi formación en **Desarrollo de Aplicaciones Web (DAW)**.

El objetivo ha sido construir una aplicación funcional desde la interfaz hasta la base de datos, conectando:

```text
Interfaz
   ↓
PHP
   ↓
Lógica de aplicación
   ↓
MariaDB
```

El proyecto me ha permitido trabajar especialmente con **PHP, SQL, MariaDB, sesiones, autenticación, roles y JavaScript**, además de aplicar criterios de organización, usabilidad y diseño a una aplicación web completa.

---