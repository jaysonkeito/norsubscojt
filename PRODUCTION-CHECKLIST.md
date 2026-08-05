# OJT Tracker — Pre-Launch / Production Checklist

Keep this file in the project. Work through it once, right before you put
this in front of real students/staff on a real server (not just your dev
laptop).

## 1. Environment
- [ ] `.env`: set `APP_ENV=production`
- [ ] `.env`: set `APP_DEBUG=false` — **this is the most important one.**
      With debug on, any error shows a full stack trace including file
      paths and (sometimes) query values to anyone who triggers it.
- [ ] `.env`: set `APP_URL` to your real domain (not `http://localhost:8000`)
- [ ] Confirm `GOOGLE_REDIRECT_URI` in `.env` and the Authorized redirect
      URI in Google Cloud Console both point at your real production domain,
      not `127.0.0.1`
- [ ] Run `php artisan key:generate` fresh on the production server if this
      is a new deployment (don't reuse your dev `APP_KEY`)

## 2. Database
- [ ] Production MySQL credentials in `.env` are NOT the same as any
      shared/dev password — confirm a fresh, strong password was set
- [ ] Run `php artisan migrate --force` (the `--force` flag is required in
      production since Laravel blocks destructive commands there by default
      unless explicitly forced)
- [ ] Set up automated backups for the database — even a simple daily
      `mysqldump` cron job is far better than nothing
- [ ] Change the seeded default Admin/Coordinator credentials
      (`admin@norsu.edu.ph` / `password123`) if you haven't already —
      confirmed earlier in this project that you already did this via
      `tinker`, just re-confirm it's not still sitting on any fresh install

## 3. Mail
- [ ] Confirm real SMTP credentials work end-to-end in production (test by
      triggering a password reset and an approval email for real)
- [ ] Consider a dedicated transactional email provider (Mailgun, Postmark,
      SES) instead of a personal Gmail account for anything beyond small-
      scale testing — Gmail SMTP has sending limits and can get accounts
      flagged for automated mail at any real volume

## 4. Performance / caching
- [ ] Run `php artisan config:cache` (caches all config into one file —
      faster boot, but means `.env` changes need `config:clear` +
      `config:cache` again to take effect)
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Confirm `composer install --optimize-autoloader --no-dev` was used
      for the production install (skips dev-only packages like PHPUnit)

## 5. File storage
- [ ] Confirm `php artisan storage:link` has been run on the production
      server (needed for the `public` disk — general app assets, not the
      private uploads added in this update)
- [ ] Confirm the new `storage/app/private` directory is NOT web-accessible
      (it shouldn't be, since it's outside `public/`, but worth a quick
      direct-URL check after deploying)
- [ ] Set a reasonable max upload size in your web server config (Nginx/
      Apache) and PHP's `upload_max_filesize`/`post_max_size` — currently
      the app-level validation caps photos at 2MB and resumes at 5MB, but
      the web server needs to allow at least that much through first

## 6. HTTPS
- [ ] Serve the whole site over HTTPS (a real SSL certificate — Let's
      Encrypt is free) — right now sessions, passwords, and every form
      submission travel in plaintext over HTTP in your dev environment,
      which is fine for `127.0.0.1` but must not go to production as-is
- [ ] Once on HTTPS, set `SESSION_SECURE_COOKIE=true` in `.env`

## 7. Testing
- [ ] Run `php artisan test` and confirm everything passes before each
      deployment (see the new `tests/Feature/` files added in this update)
- [ ] Manually walk through: Student self-register → login; Non-Student
      self-register → Account Completion → pending → Admin/Dean approval →
      login; Time Log recording by Coordinator/Company; Evaluation scoping

## 8. Monitoring
- [ ] Set up basic error logging/alerting so you find out about production
      errors without a student having to report them to you first (even
      just checking `storage/logs/laravel.log` periodically is better than
      nothing; a service like Sentry or Flare is a bigger step up)
