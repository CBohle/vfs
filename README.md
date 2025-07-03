# Proyecto VFS - Landing Page + Panel de Administración (PHP)

## 📌 Descripción

Este proyecto fue desarrollado para la empresa VFS e incluye:

- Una **Landing Page** con diseño responsive y formulario de contacto.
- Una sección de **postulación laboral** con formulario con validaciones persoanlizadas.
- Un **Panel de Administración** en PHP donde los usuarios autorizados pueden:
  - Gestionar clientes (CRUD).
  - Visualizar y responder mensajes del formulario de contacto.
  - Revisar postulaciones laborales.
  - Controlar accesos mediante un sistema de roles y autenticación.

El proyecto está construido en **PHP puro**, con **HTML, CSS, JavaScript** y base de datos **MySQL**, y es totalmente compatible con servicios de hosting tradicional como **Bluehost (cPanel)**.

---

## 👩‍💻 Equipo de Desarrollo

| Nombre             | Rol                                                                                                                 | GitHub                                      |
|-------------------|----------------------------------------------------------------------------------------------------------------------|---------------------------------------------|
| **Carolina Bohle** | Frontend: estructura visual, diseño responsivo, validaciones en el cliente. Backend: recuperación de contraseña     | [@CBohle](https://github.com/CBohle)        |
| **Paulina Olave**  | Backend: lógica del formulario de contacto, autenticación, lógica general del panel de administración, envío de notificaciones (mensajes y postulaciones) | [@polaveh](https://github.com/polaveh)      |
| **Amanda Martínez**| Backend: base de datos, gestión de roles, conexión PHP-MySQL, lógica general del panel                               | [@belencode](https://github.com/belencode)  |


---

## 📁 Estructura del Proyecto

```plaintext
VFS/
│
├── index.php                  # Landing page principal
├── contact.php                # Formulario de contacto
├── postular.php               # Formulario de postulación laboral
├── logout.php                 # Cierre de sesión
│
├── admin/
│   ├── login.php              # Inicio de sesión
│   ├── dashboard.php          # Panel principal
│   ├── clientes.php           # Gestión de clientes
│   ├── mensajes.php           # Gestión de mensajes
│   ├── postulaciones.php      # Gestión de postulaciones laborales
│   ├── includes/              # Header/footer del panel
│   └── adminlte/              # Recursos visuales del panel (template AdminLTE)
│
├── includes/
│   ├── db.php                 # Conexión a la base de datos
│   ├── config.php             # Configuración global (ej. BASE_URL)
│   ├── header.php             # Header común del sitio
│   ├── footer.php             # Footer común del sitio
│   ├── auth.php               # Protección de rutas del panel
│   ├── mailer.php             # Lógica para envío de correos (notificaciones y formularios)
│   └── controller/            # Lógica de procesamiento (formulario, login, etc.)
│
├── assets/
│   ├── css/                   # Estilos personalizados
│   ├── js/                    # Validaciones y scripts de interacción
│   └── images/                # Imágenes del sitio (logo, fondo, secciones)
│
├── sql/
│   └── init.sql               # Script de creación y carga inicial de la base
│
└── README.md
