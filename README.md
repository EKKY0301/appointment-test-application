# Appointment Test Application

Microservicio de gestión de citas médicas con Laravel y Vue 3

---

## 💡 Descripción

Esta aplicación permite crear, listar y eliminar citas entre pacientes y doctores. Incluye:

* **Backend**: Laravel 10 con endpoints RESTful (`/api/users`, `/api/appointments`, `/api/users/search`).
* **Frontend**: Vue 3 (Composition API) con un sistema ligero de toasts y selector de zona horaria.
* Validación de solapamiento de citas en backend (misma franja horaria para un doctor).
* Ejemplo de migración para crear usuarios iniciales (3 pacientes y 3 doctores).

---

## ⚙️ Requisitos

* PHP ≥ 8.1
* Composer
* Node.js ≥ 18
* NPM o Yarn
* Base de datos PostgreSQL (u otro soportado)

---

## 🚀 Instalación

1. **Clona el repositorio**

   ```bash
   git clone git@github.com:TU_USUARIO/appointment-test-application.git
   cd appointment-test-application
   ```

2. **Copia el entorno**

   ```bash
   cp .env.example .env
   ```

3. **Configura `.env`**

   * Genera `APP_KEY`:

     ```bash
     php artisan key:generate
     ```
   * Completa variables de BD (`DB_...`), mail (`MAIL_...`), Pusher o tokens si aplica.

4. **Instala dependencias**

   ```bash
   composer install
   npm install
   # o yarn
   ```

5. **Migra y seed**

   ```bash
   php artisan migrate
   php artisan db:seed  # si tienes seeders o usa migrate:refresh --seed
   ```

6. **Compila assets**

   ```bash
   npm run dev  # para desarrollo con Vite
   npm run build  # para producción
   ```

7. **Corre el servidor**

   ```bash
   php artisan serve
   ```

Accede en `http://localhost:8000` (o en el dominio configurado).

---

## 🗂 Estructura principal

```
app/            # Código de Laravel
  Http/Controllers/AppointmentController.php
  Http/Requests/StoreAppointmentRequest.php
  Models/Appointment.php
  Models/User.php
database/       # migraciones y seeds
public/         # assets compilados
resources/js/   # código Vue (components, composables)
.env.example    # config de entorno
README.md       # este archivo
```

---

## 📦 Endpoints API

### Users

* `GET  /api/users` → lista usuarios (paginado 15)
* `POST /api/users/search` → búsqueda con filtros `{ role, name, email }`
* `POST   /api/users` → crea usuario
* `GET    /api/users/{user}` → detalle
* `PUT    /api/users/{user}` → actualiza
* `DELETE /api/users/{user}` → elimina

### Appointments

* `GET  /api/appointments` → obtiene todas las citas (incluye relaciones)
* `POST /api/appointments` → crea cita (valida solapamiento)
* `DELETE /api/appointments/{appointment}` → elimina cita

---

## 🔧 Componente Toast & Timezone

* **ToastContainer.vue**: muestra toasts desde `useToast.js` (reactive)
* **useToast.js**: composable de toasts (`toastSuccess`, `toastError`, `toastInfo`)
* **TimezoneSelect.vue**: select de zonas horarias usando Intl o lista estática.

---

## 📝 Notas

* Los campos `start_at` y `end_at` se almacenan como datetime y en el modelo se exponen como Carbon.
* En el modelo `Appointment` hay atributos virtuales:

  * `start_at_for_api` → ISO8601 en zone America/New\_York
  * `end_at_for_api`   → idem
* Asegurate de configurar correctamente la zona en `.env` (`APP_TIMEZONE`).

---

## 📄 Licencia

MIT © TU\_NOMBRE
