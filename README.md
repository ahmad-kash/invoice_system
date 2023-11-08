# Invoice System

## Description

This project is an Invoice System that allows users to create, update, and manage invoices. It uses Laravel as the backend.

## Features

-   **Create Invoices:** Users can create new invoices with customer details and product information.
-   **Update Invoices:** Existing invoices can be updated with new information.
-   **Manage Invoices:** Users can view all their invoices in one place and perform actions like delete, archive, etc.
-   **User Management:** Admins can create new users and assign roles to them.
-   **Role Management:** Customers can create new roles and assign permissions to them.
-   **Notifications:** All user actions are notified to the system admins.
-   **Payments:** Users can pay their invoices through the system.

## Installation

1. Clone the repository: `git clone https://github.com/ahmad-kash/invoice_system.git`
2. Navigate to the project directory: `cd invoice_system`
3. Install dependencies: `composer install`
4. Copy `.env.example` to `.env` and modify the environment variables as needed.
5. Generate an app key: `php artisan key:generate`
6. Run migrations: `php artisan migrate`

## Usage

To start the application, run: `php artisan serve`

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
