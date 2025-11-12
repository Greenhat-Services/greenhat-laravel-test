# Employee Registration - Quick Start Guide

Complete guide to run the full-stack employee registration application (Laravel backend + Nuxt frontend).

---

## 🚀 Quick Start (3 Terminals)

### Terminal 1: Laravel Backend
```bash
# In project root
php artisan serve
```
Runs on: `http://localhost:8000`

### Terminal 2: Queue Worker
```bash
# In project root
php artisan queue:work --verbose
```
Processes email jobs in background

### Terminal 3: Nuxt Frontend
```bash
# In project root
cd public/frontend
npm install    # First time only
npm dev
```
Runs on: `http://localhost:3000`

---

## 📋 Prerequisites

### Required Software
- ✅ PHP 8.2+
- ✅ Composer
- ✅ Node.js 22.11.0
- ✅ Yarn 4.5.1 (or npm)
- ✅ SQLite (or your database)

### Check Versions
```bash
php --version          # Should be 8.2+
composer --version     # Any recent version
node --version         # Should be 22.x
yarn --version         # Should be 4.x
```

---

## 🛠️ Initial Setup (One Time Only)

### Step 1: Install Backend Dependencies
```bash
# In project root
composer install --ignore-platform-req=ext-intl
```

### Step 2: Configure Environment
```bash
# Copy .env.example if not exists
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Setup Database
```bash
# Run migrations
php artisan migrate
```

### Step 4: Configure Email (Optional for Testing)
Edit `.env`:
```env
# Option 1: Log emails (easiest for testing)
MAIL_MAILER=log

# Option 2: Mailtrap (recommended)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

### Step 5: Setup Queue
```env
# In .env - already configured
QUEUE_CONNECTION=database
```

### Step 6: Install Frontend Dependencies
```bash
cd public/frontend
yarn install
# or
npm install
```

---

## ▶️ Running the Application

### Start All Services (3 Terminals)

**Terminal 1 - Backend API:**
```bash
php artisan serve
```
✅ Should show: `Server started on http://127.0.0.1:8000`

**Terminal 2 - Queue Worker:**
```bash
php artisan queue:work --verbose
```
✅ Should show: `Processing jobs from queue...`

**Terminal 3 - Frontend:**
```bash
cd public/frontend
yarn dev
```
✅ Should show: `Local: http://localhost:3000/`

### Verify Everything is Running

Open browser tabs:
- 🌐 Frontend: http://localhost:3000
- 🔌 Backend API: http://localhost:8000/v1/employee-registration (should show 405 Method Not Allowed - normal for GET)

---

## 🧪 Testing the Application

### Complete Test Flow

1. **Open Frontend**
   - Navigate to: http://localhost:3000
   - You should see the registration form

2. **Fill Out Form**
   ```
   Registration Code: ACME
   First Name: John
   Last Name: Doe
   Email: john.doe@example.com
   Role: Software Engineer
   Highest Qualification: Bachelor's Degree
   Desired Salary: 75000 (optional)
   Note: Excited to join the team! (optional)
   ```

3. **Submit Form**
   - Click "Register" button
   - Watch loading state
   - Should redirect to success page

4. **Verify Success Page**
   - URL should be: http://localhost:3000/success?name=John+Doe&email=...
   - Should show "Registration Successful!"
   - Should display your submitted details

5. **Verify Backend (Terminal 2)**
   - Queue worker terminal should show:
   ```
   Processing: Modules\Employee\Jobs\SendEmployeeRegistrationConfirmationEmail
   Processed:  Modules\Employee\Jobs\SendEmployeeRegistrationConfirmationEmail
   ```

6. **Verify Database**
   ```bash
   php artisan tinker
   ```
   ```php
   // Check employee created
   Employee::with('user')->latest()->first()

   // Check email sent timestamp
   Employee::latest()->first()->confirmation_email_sent_at
   // Should show a timestamp
   ```

7. **Verify Email (if using log driver)**
   ```bash
   tail -f storage/logs/Yiwei_Zhao-laravel.log
   # or
   type storage\logs\Yiwei_Zhao-laravel.log | findstr "Welcome"
   ```

---

## 🔍 Troubleshooting

### Problem: "SQLSTATE[HY000]: General error: 1 no such table: users"

**Solution:**
```bash
php artisan migrate
```

### Problem: "Class 'Modules\Employee\Models\Employee' not found"

**Solution:**
```bash
composer dump-autoload
```

### Problem: "Queue not processing"

**Check:**
1. Is Terminal 2 running `queue:work`?
2. Check jobs in database:
   ```bash
   php artisan tinker --execute="echo DB::table('jobs')->count();"
   ```
3. If jobs exist but not processing, restart queue worker

**Solution:**
```bash
# Stop queue worker (Ctrl+C)
# Restart it
php artisan queue:work --verbose
```

### Problem: "CORS Error" in browser

**Check:**
1. Is Laravel running on port 8000?
2. Is frontend running on port 3000?

**Verify CORS config:**
```bash
# Check config/cors.php has:
'allowed_origins' => ['*'],
```

### Problem: Frontend shows "Failed to fetch"

**Check:**
1. Backend running? Visit: http://localhost:8000
2. Route exists?
   ```bash
   php artisan route:list --name=EmployeeRegistration
   ```
3. Check `.env` in `public/frontend`:
   ```env
   NUXT_PUBLIC_API_BASE=http://localhost:8000
   ```

### Problem: "Port 3000 already in use"

**Solution:**
```bash
# Kill existing process or use different port
cd public/frontend
yarn dev --port 3001
```

### Problem: Dependencies won't install

**Backend:**
```bash
composer install --ignore-platform-req=ext-intl
```

**Frontend:**
```bash
cd public/frontend
rm -rf node_modules
rm yarn.lock
yarn install
```

---

## 📊 Verification Checklist

Use this checklist to verify everything is working:

### Backend
- [ ] `php artisan serve` starts without errors
- [ ] Database has users and employees tables
- [ ] Route `/v1/employee-registration` exists
- [ ] Queue worker starts and shows "Processing jobs..."
- [ ] CORS configured to allow all origins

### Frontend
- [ ] `yarn install` completes successfully
- [ ] `yarn dev` starts on port 3000
- [ ] Registration form loads in browser
- [ ] All form fields render correctly
- [ ] No console errors in browser DevTools

### Integration
- [ ] Form submission creates employee record
- [ ] Queue job processes email sending
- [ ] `confirmation_email_sent_at` timestamp updates
- [ ] Success page displays correct information
- [ ] Can register multiple employees with different emails

---

## 📁 Project Structure Overview

```
greenhat-laravel-test/
├── Modules/Employee/              # Employee module (backend)
│   ├── app/
│   │   ├── Actions/              # Business logic
│   │   ├── Data/                 # DTOs
│   │   ├── Emails/               # Email templates
│   │   ├── Jobs/                 # Queue jobs
│   │   ├── Models/               # Eloquent models
│   │   ├── Observers/            # Model observers
│   │   └── Transformers/         # API responses
│   ├── database/migrations/      # Database migrations
│   ├── routes/                   # API routes
│   └── resources/views/emails/   # Email views
│
├── public/frontend/              # Nuxt 3 frontend
│   ├── pages/                    # Vue pages
│   │   ├── index.vue            # Registration form
│   │   └── success.vue          # Success page
│   ├── composables/             # Reusable logic
│   ├── app.vue                  # Root component
│   └── nuxt.config.ts           # Nuxt configuration
│
├── config/cors.php              # CORS configuration
├── database/database.sqlite     # SQLite database
│
└── Documentation
    ├── IMPLEMENTATION_SUMMARY.md          # Backend docs
    ├── FRONTEND_IMPLEMENTATION_SUMMARY.md # Frontend docs
    ├── QUICK_START_GUIDE.md              # This file
    └── .claude/01-employee-registration-PRD.md
```

---

## 🎯 API Endpoint Details

### Registration Endpoint

**URL:** `POST http://localhost:8000/v1/employee-registration`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "registrationCode": "ACME",
  "firstName": "John",
  "lastName": "Doe",
  "email": "john.doe@example.com",
  "role": "Software Engineer",
  "highestQualification": "Bachelor's Degree",
  "desiredSalary": 75000,
  "note": "Optional note"
}
```

**Success Response (200):**
```json
{
  "data": {
    "id": 1,
    "role": "Software Engineer",
    "highestQualification": "Bachelor's Degree",
    "desiredSalary": 75000,
    "note": "Optional note",
    "confirmationEmailSentAt": null,
    "createdAt": "2025-11-11T...",
    "user": {
      "id": 1,
      "email": "john.doe@example.com",
      "firstName": "John",
      "lastName": "Doe",
      "name": "John Doe",
      ...
    }
  },
  "meta": {
    "success": true,
    "message": "Registration successful! A confirmation email has been sent..."
  }
}
```

**Error Response (422):**
```json
{
  "message": "The registration code field must be ACME.",
  "errors": {
    "registrationCode": [
      "The registration code field must be ACME."
    ]
  }
}
```

---

## 🎓 What This Demonstrates

### Essential Skills ✅
- Database migrations
- Laravel validation (via DTOs)
- Data storage in database

### Intermediate Skills ✅
- Error handling with HTTP status codes
- Fractal transformers for API responses

### Proficient Skills ✅
- Observer pattern for side effects
- Queue jobs for async processing
- Spatie Laravel Data DTOs
- Custom exception handling

### Advanced Skills ✅
- Laravel Modules architecture
- Laravel Actions pattern
- **Nuxt 3 + Nuxt UI frontend**

---

## 💡 Tips for Success

### Development Workflow
1. Keep all 3 terminals open and visible
2. Watch Terminal 2 for queue job processing
3. Use browser DevTools Network tab to debug API calls
4. Check `storage/logs/` for Laravel errors
5. Use `php artisan tinker` to inspect database

### Testing Different Scenarios
```bash
# Test duplicate email (should fail)
# Use same email twice

# Test invalid registration code (should fail)
# Use code other than "ACME"

# Test missing required fields (should fail)
# Leave required fields empty

# Test successful registration (should succeed)
# Fill all required fields correctly
```

### Monitoring Queue
```bash
# Watch queue processing in real-time
php artisan queue:work --verbose

# Process one job at a time (for testing)
php artisan queue:work --once

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

---

## 📞 Support

If you encounter issues:

1. **Check the logs:**
   - Laravel: `storage/logs/Yiwei_Zhao-laravel.log`
   - Browser: DevTools Console tab
   - Network: DevTools Network tab

2. **Verify services are running:**
   ```bash
   # Backend
   curl http://localhost:8000

   # Frontend
   curl http://localhost:3000
   ```

3. **Test API directly:**
   ```bash
   curl -X POST http://localhost:8000/v1/employee-registration \
     -H "Content-Type: application/json" \
     -d '{"registrationCode":"ACME","firstName":"Test","lastName":"User","email":"test@test.com","role":"Developer","highestQualification":"Degree"}'
   ```

---

## ✅ Ready to Test!

You now have everything needed to run the complete employee registration system. Follow the Quick Start section at the top to get started, then use the Testing section to verify everything works.

**Good luck with your implementation!** 🚀
