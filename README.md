# Guestbook Application

This is a simple PHP guestbook application where users can register, log in, and leave messages. The application includes features such as user authentication, CRUD operations for messages, and basic error handling.

## Features

- **User Registration and Login**: Users can create an account, log in, and log out.
- **Add, Edit, Delete Messages**: Users can add messages to the guestbook. Users can also edit or delete their own messages.
- **Profile Page**: Users can view their profile information.
- **CSRF Protection**: Basic CSRF protection for form submissions.
- **Error Handling**: Try-catch blocks for database operations to provide informative error messages.

## Technologies Used

- **PHP**: Backend logic.
- **MySQL**: Database for storing user information and guestbook messages.
- **Bootstrap**: CSS framework for UI components.
- **Heroku**: Hosting the application.

## Setup Instructions

1. **Clone the repository**:
   ```bash
   git clone https://github.com/adityajanjanam/GuestbookProject.git
   cd GuestbookProject
   ```

2. **Install Composer dependencies**:
   Make sure Composer is installed, then run:
   ```bash
   composer update
   ```

3. **Database Setup**:
   - Create a MySQL database named `guestbook_db`.
   - Import the provided `schema.sql` file to create the necessary tables.

4. **Configure Environment Variables**:
   - Update `db.php` to use environment variables for database credentials.
   - If deploying to Heroku, set the environment variables using:
     ```bash
     heroku config:set DB_HOST=<your-db-host> DB_USER=<your-db-user> DB_PASS=<your-db-password> DB_NAME=<your-db-name> -a <your-heroku-app-name>
     ```

5. **Run Locally**:
   - Use XAMPP or any other local server environment to host the application locally.

6. **Deploy to Heroku**:
   - Create a Heroku app and add a database add-on (e.g., Heroku Postgres or ClearDB).
   - Push the code to Heroku:
     ```bash
     git push heroku main
     ```

## Environment Variables

The application requires the following environment variables:

- `DB_HOST`: Database host (e.g., `localhost` or Heroku database URL).
- `DB_USER`: Database username.
- `DB_PASS`: Database password.
- `DB_NAME`: Database name.

## Usage

- Navigate to the main page.
- Register or log in to add messages to the guestbook.
- Once logged in, users can add, edit, or delete their own messages.

## Troubleshooting

- **HTTP 500 Error**: Check Heroku logs using `heroku logs --tail -a <your-heroku-app-name>` to diagnose server issues.
- **Database Connection Issues**: Ensure your environment variables are set correctly.

## License

This project is licensed under the MIT License.

