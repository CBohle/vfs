# Proyecto para empresa vfs -> Landing Page + Panel de Administración (PHP)

## Descripción

Este proyecto es una landing page con un formulario de contacto y un panel de administración donde los empleados pueden gestionar clientes (CRUD) y responder mensajes recibidos por formulario.

El proyecto está construido en PHP puro, HTML, CSS y MySQL, compatible con hosting tradicional como Bluehost (con cPanel).

---

## Equipo de desarrollo

| Nombre            | Rol                             | GitHub                         |
|------------------|----------------------------------|--------------------------------|
| Carolina Bohle    | Frontend & UI                   | [@CBohle](https://github.com/CBohle) |
| Paulina Olave     | Backend & Lógica del formulario | [@polaveh](https://github.com/polaveh) |
| Amanda Martínez   | Base de datos & Conexión        | [@belencode](https://github.com/belencode) |


---

## Estructura del proyecto

```plaintext
landing-page/
│
├── public/
│   ├── index.php              # Landing page
│   ├── contact.php            # Procesa el formulario
│   └── admin/
│       ├── login.php
│       ├── dashboard.php
│       ├── clientes.php
│       └── mensajes.php
│
├── includes/
│   ├── db.php                 # Conexión DB
│   ├── header.php             # HTML header
│   ├── footer.php             # HTML footer
│   └── auth.php               # Protege panel admin
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── sql/
│   └── init.sql               # Script para crear las tablas
│
└── README.md
