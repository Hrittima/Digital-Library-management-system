# Open Shelf Digital Library

A PHP and MySQL library management system with a book catalog, member and staff accounts, and an installable offline catalog shell.

## Local development

1. Install PHP 8.2 or newer with `mysqli`, and MySQL/MariaDB.
2. Create an empty database named `martdevelopers_iLibrary`.
3. Import the schema and account-request table:

   ```sh
   mysql -u root -p martdevelopers_iLibrary < Db/schema.sql
   mysql -u root -p martdevelopers_iLibrary < Db/account_requests.sql
   ```

4. Configure `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` in your environment. The defaults support a local XAMPP development database. See `.env.example`.
5. From the project root, run `php -S 127.0.0.1:8000` and open <http://127.0.0.1:8000/>.

The schema intentionally contains no demo accounts or personal data. Before using the system, import your own catalog and securely provision the first administrator. With the database environment configured, set `ADMIN_NAME`, `ADMIN_EMAIL`, and a strong `ADMIN_PASSWORD` (at least 12 characters), then run `php scripts/create-admin.php` from a trusted CLI. `ADMIN_PHONE` is optional. The script refuses web requests.

## Deploying to Vercel

This project uses PHP and MySQL. Vercel's **Container Runtime** (currently in Beta) can run the included root `Dockerfile`; it is not a static-site deployment.

1. Push this repository to GitHub and import it as a new Vercel project. If Vercel does not select it automatically, choose the **Container** framework preset.
2. Provision an externally hosted MySQL-compatible database. Vercel containers do not run the project's local XAMPP/MariaDB server.
3. Import `Db/schema.sql` and `Db/account_requests.sql` into that database. Add the database connection details to Vercel's project environment variables: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD`. Never commit production credentials.
4. Deploy, then run `php scripts/create-admin.php` from a trusted machine with the Vercel database connection variables and `ADMIN_NAME`, `ADMIN_EMAIL`, and `ADMIN_PASSWORD` set. Add your catalog data.

**Production notes:** PHP's default file-backed sessions and local file uploads are not durable across Vercel container instances. For dependable sign-in, use a shared session store; store uploaded book covers in durable object storage. The existing app must be adapted to those services before treating it as production-ready. A conventional PHP host or VPS with persistent storage is the simpler option if you want to keep the current session and upload behavior.

## Offline use

The site can be installed from a browser as a PWA after it is deployed over HTTPS (or opened on localhost). The service worker caches the app shell and previously visited **public** catalog pages. Visit a book or search result while online to make that exact page available offline.

Offline mode is read-only: login, registration, borrowing, admin tools, and database updates require a network connection. Install the PWA on each device that needs offline access.

## Account registration

Students can create accounts at `register.php`. Librarian and administrator sign-ups create pending requests that an existing administrator must approve from **Librarians → Account Requests**. Create the first administrator securely during setup; public sign-up cannot grant administrator access.

## License

See the upstream project license and third-party asset licenses before redistributing this application or its bundled content.
