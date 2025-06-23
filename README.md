# Project Management App – Fullstack (Laravel 12 API + React + Shadcn UI)

A fullstack task & project management app built using:
- **Laravel 12** backend API
- **Sanctum** for authentication
- **Role-based access control** (admin/user)
- **React + Vite + Tailwind CSS + Shadcn UI** frontend

---

## 🔧 Backend Setup (Laravel 12)

### Requirements
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js & npm

### Installation

```bash
git clone https://github.com/alaincodes24/project-management-app.git
cd project-management-app

# Install dependencies
composer install

# Copy .env and set your DB credentials
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate
```

### Sanctum

Make sure `auth:sanctum` middleware is applied to the protected routes in `routes/api.php`.

### Start Laravel API Server

```bash
php artisan serve
```

The API will run at: `http://127.0.0.1:8000`

---

## 📡 API Endpoints

### Authentication
- `POST /api/register` – Register
- `POST /api/login` – Login
- `POST /api/logout` – Logout
- `GET /api/user` – Get current user profile

### Projects
- `GET /api/projects` – List user projects
- `POST /api/projects` – Create new project
- `PATCH /api/projects/{id}` – Update project
- `DELETE /api/projects/{id}` – Delete project

### Tasks
- `GET /api/tasks` – List tasks
- `POST /api/tasks` – Create new task
- `PATCH /api/tasks/{id}` – Update task
- `DELETE /api/tasks/{id}` – Delete task

---

## 🧪 Testing

```bash
php artisan test
```

Covers login, registration, task/project CRUD, and authorization.

---

## 🎨 Frontend Setup (React + Vite + Shadcn UI)

### Requirements
- Node.js 18+
- npm

### Installation

```bash
git clone https://github.com/alaincodes24/project-management-app-frontend.git
cd project-management-app-frontend

# Install dependencies
npm install
```

### Tailwind & Shadcn Setup

Ensure Tailwind is configured via `tailwind.config.js`.

To initialize `shadcn/ui`:

```bash
npx shadcn@latest init
```

Add components (example):

```bash
npx shadcn@latest add button card dialog input
```

### Environment Variables

Create a `.env` file in the frontend directory:

```env
REACT_APP_API_URL=http://127.0.0.1:8000/api
```

### Start Frontend Dev Server

```bash
npm run dev
```

Frontend will run at: `http://localhost:5173`

---

## 🚀 Features

- **User Authentication** - Register, login, logout with Laravel Sanctum
- **Role-Based Access** - Admin and user roles with different permissions
- **Project Management** - Create, update, delete projects
- **Task Management** - Full CRUD operations for tasks
- **Modern UI** - Built with Shadcn UI components and Tailwind CSS
- **Responsive Design** - Works on desktop and mobile devices

---

## 🔒 Security

- Laravel Sanctum for API authentication
- CSRF protection
- Role-based authorization
- Input validation and sanitization

## 📱 Usage

1. Start the Laravel API server on port 8000
2. Start the React development server on port 5173
3. Register a new user account or login with existing credentials
4. Create projects and manage tasks within each project
5. Admin users can manage all projects and users

## 🛠️ Built With

- **Backend**: Laravel 12, PHP 8.2+, MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **Frontend**: React 18, Vite, TypeScript
- **Styling**: Tailwind CSS, Shadcn UI
- **Testing**: PHPUnit

---

## 📞 Support

If you encounter any issues or have questions, please open an issue on GitHub.
