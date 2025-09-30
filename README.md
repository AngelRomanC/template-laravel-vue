# 🛠️ Proyecto Base - Laravel + Vue + Inertia

Este repositorio es un **template base** para iniciar proyectos en **Laravel** con integración de **Vue 3** e **Inertia.js**.  
La idea es contar con una estructura lista para trabajar, con autenticación, roles y permisos, y un diseño inicial.

---

## 🚀 Tecnologías principales

- [Laravel 10](https://laravel.com/) – Backend (API y lógica de negocio).
- [Inertia.js](https://inertiajs.com/) – Conexión sin necesidad de una API REST tradicional.
- [Vue 3](https://vuejs.org/) – Frontend reactivo y moderno.
- [Tailwind CSS](https://tailwindcss.com/) – Estilos rápidos y personalizables.
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) – Gestión de roles y permisos.
- [Vite](https://vitejs.dev/) – Bundler para desarrollo frontend rápido.

---

## ⚙️ Requisitos previos

- PHP >= 8.1  
- Composer  
- Node.js >= 18  
- MySQL / MariaDB o PostgreSQL  
- Git  

---

## 📦 Instalación y configuración

1. Clonar el repositorio  
   ```bash
   git clone https://github.com/tu-usuario/tu-repo.git
   cd tu-repo

2. Instalar dependencias PHP
    composer install

3. Instalar dependencias Node
    npm install
    
4. Copiar archivo de entorno y configurar las variables
    cp .env.example .env
    php artisan key:generate

5. Migrar y seedear la dase de datos
    php artisan migrate --seed

6. Levantar el proyecto
    php artisan serve
    npm run dev

##  👤 Roles y permisos

El proyecto incluye integración con Spatie Permission.
Por defecto se crean algunos roles iniciales con los seeders.

##  🤝 Contribuciones

¡Las contribuciones son bienvenidas! 🚀
Para contribuir:

Haz un fork del repositorio.

Crea una rama (git checkout -b feature/nueva-funcionalidad).

Haz commit de tus cambios.

Envía un pull request para revisión.

##  📜 Licencia

Este proyecto está bajo la licencia MIT
.
Puedes usarlo y modificarlo libremente en tus propios proyectos.