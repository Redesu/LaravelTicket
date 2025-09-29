# [LaravelTicket](https://laravelticket-production.up.railway.app/)

![Main page](https://i.imgur.com/NhiSsPj.png) A full-stack ticket management application built with **Laravel**, **Vite**, **MySQL**, and integrating with the **Cloudinary CDN** to store images.

---

## You can find a live version [HERE](https://laravelticket-production.up.railway.app/)

## Features

-   **Ticket Management:**  Users can create, view, edit, and delete support tickets.
    ![Ticket Management](https://i.imgur.com/Rv9pEXE.png) -   **User Management:** Includes user registration and login. Authenticated users can manage their profiles and tickets.
    ![User Management](https://i.imgur.com/s9zxsgj.gif)-   **Dashboard:** An administrative dashboard provides statistics and an overview of the ticketing system.
    ![Dashboard](https://i.imgur.com/NhiSsPj.png) -   **Department and Category Management::** Tickets can be organized by department and category, which are manageable.
    ![Department and Category Management](https://i.imgur.com/LMbhdLG.gif)

---

## Project Structure

```

.
├── app/                      # Core application code
│   ├── Console/Commands/     # Custom Artisan commands
│   ├── DTOs/                 # Data Transfer Objects for structuring data
│   ├── Http/                 # Controllers, middleware, and requests
│   │   ├── Controllers/      # Handles application logic for requests
│   │   ├── Middleware/       # Filters incoming requests
│   │   └── Requests/         # Contains form request validation
│   ├── Models/               # Eloquent models for database interaction
│   ├── Providers/            # Service providers for bootstrapping features
│   └── Services/             # Business logic services
├── bootstrap/                # Scripts for bootstrapping the application
├── config/                   # Application configuration files
├── database/                 # Database migrations, seeders, and factories
│   ├── factories/            # Model factories for generating test data
│   ├── migrations/           # Version control for your database schema
│   └── seeders/              # Files to seed the database with initial data
├── public/                   # Web server's document root
│   ├── vendor/               # Front-end assets (AdminLTE, Bootstrap, etc.)
│   └── index.php             # Entry point for the application
├── resources/                # Front-end assets and views
│   ├── css/                  # Uncompiled CSS files
│   ├── js/                   # Uncompiled JavaScript files
│   └── views/                # Blade templates for the user interface
│       ├── admin/            # Views for the admin dashboard
│       ├── auth/             # Authentication-related views (login, register)
│       └── layouts/          # Blade layout templates
├── routes/                   # Route definitions for the application
│   ├── console.php           # Console-based Artisan command routes
│   └── web.php               # Web interface routes
├── storage/                  # Compiled assets, caches, and logs
├── tests/                    # Application tests (feature and unit)
├── vendor/                   # Composer dependencies
├── .env.example              # Example environment configuration file
├── artisan                   # Command-line interface for Laravel
├── composer.json             # Project dependencies for Composer
└── README.md                 # Project README file

````

---

## Prerequisites

-   Node.js (v18+ recommended)
-   npm, yarn, pnpm, or bun
-   Composer (2.8+)
-   Php (8.2+)
-   MySQL Instance
-   Cloudinary Account (OPTIONAL)

---

## Getting Started

### 1. Clone the repository

```sh
git clone [https://github.com/Redesu/LaravelTicket.git](https://github.com/Redesu/LaravelTicket.git) # Adjust if the repository name is different
cd LaravelTicket
````

### 2\. Install dependencies

```sh
npm install
# or
yarn install
# or
pnpm install
# or
bun install

# Then
composer install
```

### 3\. Configure Environment Variables

Create a `.env` file in the root of the project by copying `.env.example` (if available, otherwise create it manually):

```sh
cp .env.example .env
```

**Important Environment Variables (`.env`):**

```
CLOUDINARY_URL=your_cloudinary_url # Optional
CLOUDINARY_UPLOAD_PRESET=your_cloudinary_upload_preset # Optional
APP_ENV=production # This can be local or production
```

### 4\. Run the development server

```sh
php artisan serve
# And to run vite
npm run dev
```

-----

## Usage

  - Open [http://localhost:8000](http://localhost:8000) with your browser to see the application.
  - Register and login to create your tickets and manage them.

-----

## API Endpoints

The application interacts with Laravel backend's API. Custom API routes are implemented in the project to handle authentication and ticket management.

See `routes/web.php` for more details on each endpoint.

**Authentication Endpoints:**

  - `POST /auth/login` – Logins the user
  - `POST /auth/register` – Register the user
  - `POST /auth/logout` – Logouts the user

**Tickets**

  - `GET /api/chamados/data-tables` – Retrieves ticket data for display in DataTables.
  - `POST /api/chamados` – Creates a new ticket.
  - `POST /api/chamados/{id}/comment` – Adds a comment to a specific ticket.
  - `POST /api/chamados/{id}/solution` – Adds a solution to a specific ticket.
  - `PUT /api/chamados/{id}` – Updates a specific ticket.
  - `DELETE /api/chamados` – Deletes a ticket.
  - `GET /api/chamados/stats/overview` – Retrieves an overview of ticket statistics.

  **Categories**
  - `GET /api/categorias/data-tables` - Retrieves category data for DataTables.
  - `POST /api/categorias` - Creates a new category.
  - `PUT /api/categorias/{id}` - Updates a specific category.
  - `DELETE /api/categorias` - Deletes a category.

  **Departments**
  - `GET /api/departamentos/data-tables` - Retrieves department data for DataTables.
  - `POST /api/departamentos` - Creates a new department.
  - `PUT /api/departamentos/{id}` - Updates a specific department.
  - `DELETE /api/departamentos` - Deletes a department.

  **Users**
  - `POST /api/users/{id}` - Updates the settings for a specific user.

  **Attachments**
  - `GET /anexos/{id}/download` - Downloads a specific attachment.

-----

## Contributing

Pull requests are welcome\! For major changes, please open an issue first to discuss what you would like to change.

-----

## License

[MIT](https://mit-license.org/)