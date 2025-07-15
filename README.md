# Appointment Test Application

Microservicio de gestión de citas médicas con Laravel y Vue 3

---

## Descripción

Esta aplicación permite crear, listar y eliminar citas entre pacientes y doctores. Incluye:

- **Backend**: Laravel 10 con endpoints RESTful (`/api/users`, `/api/appointments`, `/api/users/search`).
- **Frontend**: Vue 3 (Composition API) con sistema de toasts propio y selector de zona horaria.
- Validación de solapamiento de citas en backend (misma franja horaria para un doctor).
- Migración de ejemplo para crear usuarios iniciales (3 pacientes y 3 doctores).

---

## Requisitos

- PHP >= 8.1
- Composer
- Node.js >= 18
- NPM o Yarn
- PostgreSQL (u otra base de datos compatible)

---

## Instalación

1. Clona el repositorio:
   ```bash
   git clone git@github.com:TU_USUARIO/appointment-test-application.git
   cd appointment-test-application
   ```
2. Copia el entorno:
   ```bash
   cp .env.example .env
   ```
3. Genera la clave de aplicación:
   ```bash
   php artisan key:generate
   ```
4. Ajusta variables en `.env` (base de datos, correo, zona horaria, Mailtrap, Pusher, etc.).
5. Instala dependencias:
   ```bash
   composer install
   npm install  # o yarn
   ```
6. Ejecuta migraciones y seeds:
   ```bash
   php artisan migrate
   php artisan db:seed  # si tienes seeders
   ```
7. Compila assets:
   ```bash
   npm run dev    # desarrollo
   npm run build  # producción
   ```
8. Inicia el servidor:
   ```bash
   php artisan serve
   ```

Accede en `http://localhost:8000`.

---

## Estructura principal

```
app/
  Http/
    Controllers/AppointmentController.php
    Requests/StoreAppointmentRequest.php
  Models/
    Appointment.php
    User.php
database/
  migrations/
public/
resources/js/
  components/
  composables/
.env.example
README.md
```

---

## Endpoints API

### Usuarios

- `GET /api/users` → lista (paginado 15)
- `POST /api/users/search` → búsqueda con filtros `{ role, name, email }`
- `POST /api/users` → crea usuario
- `GET /api/users/{id}` → detalle
- `PUT /api/users/{id}` → actualiza
- `DELETE /api/users/{id}` → elimina

### Citas

- `GET /api/appointments` → todas las citas (con paciente y doctor)
- `POST /api/appointments` → crea cita (valida solapamiento)
- `DELETE /api/appointments/{id}` → elimina

---

## Configuración de correo (Mailtrap)

Para pruebas de correo local usa Mailtrap. En tu `.env` configura:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=example@example.com
MAIL_FROM_NAME="AppointmentApp"
```

Regístrate en [Mailtrap](https://mailtrap.io/) para obtener las credenciales.

---

## Configuración de broadcasting (Pusher)

Para notificaciones en tiempo real (Broadcasting) usa Pusher. En `.env`:

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=tu_app_id
PUSHER_APP_KEY=tu_app_key
PUSHER_APP_SECRET=tu_app_secret
PUSHER_APP_CLUSTER=tu_cluster
```

Y en el cliente (Vite) exporta estas variables:

```env
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

Regístrate en [Pusher](https://pusher.com/) y crea una app para obtener sus credenciales.

---

## Notas

- `start_at` y `end_at` se almacenan como datetime y se exponen en zona `America/New_York`.
- Sistema de toasts ligero sin librerías externas.

---

## Licencia

MIT © Erick Kaito Kikuchi Yamamoto
