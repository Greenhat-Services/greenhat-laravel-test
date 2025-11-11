# Employee Registration Frontend

A Nuxt 3 application with Nuxt UI for employee self-registration at Greenhat.

## Features

- ✅ Modern Nuxt 3 + Vue 3 Composition API
- ✅ Nuxt UI component library with Tailwind CSS
- ✅ TypeScript support
- ✅ Form validation using Zod
- ✅ Responsive design
- ✅ Real-time client-side validation
- ✅ Server-side error handling
- ✅ Success confirmation page
- ✅ Dark mode support (via Nuxt UI)

## Prerequisites

- Node.js 22.11.0 (or compatible version)
- Yarn 4.5.1 (or npm)
- Laravel backend running on `http://localhost:8000`

## Installation

1. Navigate to the frontend directory:
```bash
cd public/frontend
```

2. Install dependencies:
```bash
yarn install
# or
npm install
```

3. Configure environment variables (already set in `.env`):
```env
NUXT_PUBLIC_API_BASE=http://localhost:8000
```

## Development

### Start the Development Server

```bash
yarn dev
# or
npm run dev
```

The application will be available at `http://localhost:3000`

### Project Structure

```
public/frontend/
├── pages/
│   ├── index.vue              # Registration form page
│   └── success.vue            # Success confirmation page
├── composables/
│   └── useEmployeeRegistration.ts  # API integration composable
├── app.vue                    # Root component
├── nuxt.config.ts             # Nuxt configuration
├── package.json               # Dependencies
├── tsconfig.json              # TypeScript configuration
└── .env                       # Environment variables
```

## Usage

### Registration Form

Navigate to `http://localhost:3000` to access the employee registration form.

**Required Fields:**
- Registration Code (must be "ACME")
- First Name (min 2 characters)
- Last Name (min 2 characters)
- Email (valid email format)
- Role
- Highest Qualification

**Optional Fields:**
- Desired Salary (positive number)
- Additional Notes (max 5000 characters)

### Form Validation

The form includes both client-side and server-side validation:

**Client-side validation:**
- Real-time validation as you type
- Visual feedback for errors
- Submit button disabled when form is invalid

**Server-side validation:**
- Laravel API validates all data
- Errors displayed in alert box above submit button
- Field-specific errors shown in validation messages

### Success Flow

After successful registration:
1. User is redirected to `/success` page
2. Confirmation message displayed with registration details
3. Option to register another employee
4. Email confirmation notice

## API Integration

The frontend communicates with the Laravel backend API:

**Endpoint:** `POST /v1/employee-registration`

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

**Response (Success):**
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
    "message": "Registration successful! A confirmation email has been sent to your email address."
  }
}
```

## Production Build

### Build for Production

```bash
yarn build
# or
npm run build
```

### Preview Production Build

```bash
yarn preview
# or
npm run preview
```

### Generate Static Site

If you want to generate a static version:

```bash
yarn generate
# or
npm run generate
```

This creates a `.output/public` directory with static files.

## Testing

### Manual Testing Checklist

- [ ] Form loads without errors
- [ ] All fields render correctly
- [ ] Registration code validation (must be ACME)
- [ ] Email format validation
- [ ] Required field validation
- [ ] Form submits successfully with valid data
- [ ] Success page displays correct information
- [ ] Server errors display properly
- [ ] Duplicate email error handled
- [ ] Can register multiple employees
- [ ] Dark mode works
- [ ] Responsive on mobile/tablet/desktop

### Test with Backend

**Prerequisites:**
1. Laravel server running: `php artisan serve`
2. Queue worker running: `php artisan queue:work`
3. Database migrated and ready

**Test Flow:**
1. Open `http://localhost:3000`
2. Fill out registration form with valid data
3. Use registration code: `ACME`
4. Submit form
5. Verify redirect to success page
6. Check Laravel logs for job processing
7. Verify employee created in database

## Troubleshooting

### CORS Errors

If you see CORS errors in browser console:
- Ensure Laravel is running on `http://localhost:8000`
- Check `config/cors.php` allows your frontend origin
- Verify `NUXT_PUBLIC_API_BASE` in `.env` is correct

### API Connection Errors

- Verify Laravel server is running: `php artisan serve`
- Check API endpoint is accessible: `http://localhost:8000/v1/employee-registration`
- Test API with curl or Postman first

### Form Validation Errors

- Check browser console for client-side validation errors
- Verify Zod schema matches API requirements
- Check network tab for server response

### Build Errors

- Clear Nuxt cache: `rm -rf .nuxt`
- Reinstall dependencies: `rm -rf node_modules && yarn install`
- Check Node.js version matches project requirements

## Technologies Used

- **Nuxt 3** - The Vue framework for production
- **Vue 3** - Progressive JavaScript framework
- **Nuxt UI** - Beautiful and accessible UI components
- **Tailwind CSS** - Utility-first CSS framework
- **Zod** - TypeScript-first schema validation
- **TypeScript** - Type-safe JavaScript

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

MIT

## Contact

For questions or support, contact: hr@greenhat.com
