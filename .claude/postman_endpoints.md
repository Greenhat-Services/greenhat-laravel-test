# Course Management System - Postman API Endpoints

## Authentication

⚠️ **Authentication has been disabled for testing purposes.**

All endpoints are now publicly accessible without authentication.

**Headers Required:**
```
Accept: application/json
Content-Type: application/json
```

**Base URL:** `http://localhost:8000/v1`

---

## Course Categories API

### 1. Create Course Category

**Endpoint:** `POST /v1/course-categories`

**Request Body:**
```json
{
  "name": "Cloud Computing",
  "description": "Learn cloud platforms like AWS, Azure, and Google Cloud",
  "isActive": true
}
```

**Response (201):**
```json
{
  "data": {
    "id": 11,
    "name": "Cloud Computing",
    "slug": "cloud-computing",
    "description": "Learn cloud platforms like AWS, Azure, and Google Cloud",
    "isActive": true,
    "coursesCount": 0,
    "createdAt": "2025-01-15T10:30:00.000000Z",
    "updatedAt": "2025-01-15T10:30:00.000000Z"
  },
  "meta": {
    "message": "Course category created successfully"
  }
}
```

---

### 2. List Course Categories

**Endpoint:** `GET /v1/course-categories`

**Query Parameters:**
```
is_active=true              # Filter by active status (true/false)
search=web                  # Search by name
sort_by=name                # Sort by: name, created_at
sort_order=asc              # Sort order: asc, desc
per_page=10                 # Items per page for pagination
```

**Example:** `GET /v1/course-categories?is_active=true&sort_by=name&per_page=10`

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Web Development",
      "slug": "web-development",
      "description": "Courses focused on building modern web applications",
      "isActive": true,
      "coursesCount": 3,
      "createdAt": "2025-01-15T10:00:00.000000Z",
      "updatedAt": "2025-01-15T10:00:00.000000Z"
    }
  ],
  "meta": {
    "pagination": {
      "total": 10,
      "count": 10,
      "per_page": 10,
      "current_page": 1,
      "total_pages": 1
    }
  }
}
```

---

### 3. Get Course Category by ID

**Endpoint:** `GET /v1/course-categories/{id}`

**Query Parameters:**
```
include=courses             # Include related courses
```

**Example:** `GET /v1/course-categories/1?include=courses`

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "name": "Web Development",
    "slug": "web-development",
    "description": "Courses focused on building modern web applications",
    "isActive": true,
    "coursesCount": 3,
    "createdAt": "2025-01-15T10:00:00.000000Z",
    "updatedAt": "2025-01-15T10:00:00.000000Z",
    "courses": {
      "data": [
        {
          "id": 1,
          "title": "Advanced Laravel Development",
          "slug": "advanced-laravel-development",
          "status": "published"
        }
      ]
    }
  }
}
```

---

### 4. Update Course Category

**Endpoint:** `PUT /v1/course-categories/{id}`

**Request Body:**
```json
{
  "name": "Web Development (Updated)",
  "description": "Updated description for web development courses",
  "isActive": false
}
```

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "name": "Web Development (Updated)",
    "slug": "web-development",
    "description": "Updated description for web development courses",
    "isActive": false,
    "coursesCount": 3,
    "createdAt": "2025-01-15T10:00:00.000000Z",
    "updatedAt": "2025-01-15T10:35:00.000000Z"
  },
  "meta": {
    "message": "Course category updated successfully"
  }
}
```

---

### 5. Delete Course Category

**Endpoint:** `DELETE /v1/course-categories/{id}`

**Response (204 No Content):**
No response body (successful deletion returns HTTP 204)

---

## Courses API

### 1. Create Course

**Endpoint:** `POST /v1/courses`

**Request Body:**
```json
{
  "title": "Microservices Architecture with Node.js",
  "description": "Learn to design and build scalable microservices using Node.js, Docker, and Kubernetes",
  "instructor": "John Doe",
  "durationHours": 25,
  "difficultyLevel": "advanced",
  "status": "published",
  "maxEnrollments": 20,
  "startDate": "2025-02-01",
  "endDate": "2025-03-01",
  "categoryIds": [1, 3]
}
```

**Response (201):**
```json
{
  "data": {
    "id": 13,
    "title": "Microservices Architecture with Node.js",
    "slug": "microservices-architecture-with-nodejs",
    "description": "Learn to design and build scalable microservices",
    "instructor": "John Doe",
    "durationHours": 25,
    "difficultyLevel": "advanced",
    "status": "published",
    "maxEnrollments": 20,
    "availableSlots": 20,
    "startDate": "2025-02-01",
    "endDate": "2025-03-01",
    "enrollmentsCount": 0,
    "isEnrollmentOpen": true,
    "createdAt": "2025-01-15T10:40:00.000000Z",
    "updatedAt": "2025-01-15T10:40:00.000000Z",
    "categories": {
      "data": [
        {
          "id": 1,
          "name": "Web Development",
          "slug": "web-development"
        },
        {
          "id": 3,
          "name": "DevOps",
          "slug": "devops"
        }
      ]
    }
  },
  "meta": {
    "message": "Course created successfully"
  }
}
```

---

### 2. List Courses

**Endpoint:** `GET /v1/courses`

**Query Parameters:**
```
status=published                    # Filter by status: draft, published, archived
difficulty_level=intermediate       # Filter by difficulty: beginner, intermediate, advanced
category_id=1                       # Filter by category ID
instructor=John                     # Filter by instructor name
search=laravel                      # Search in title and description
available_for_enrollment=true       # Only show courses with available slots
include=categories,enrollments      # Include relationships
sort_by=title                       # Sort by: title, start_date, created_at, enrollments_count
sort_order=asc                      # Sort order: asc, desc
per_page=10                         # Items per page
```

**Example:** `GET /v1/courses?status=published&difficulty_level=intermediate&include=categories&per_page=10`

**Response (200):**
```json
{
  "data": [
    {
      "id": 3,
      "title": "React and Next.js Masterclass",
      "slug": "react-and-nextjs-masterclass",
      "description": "Build production-ready applications with React 18 and Next.js 14",
      "instructor": "Michael Chen",
      "durationHours": 30,
      "difficultyLevel": "intermediate",
      "status": "published",
      "maxEnrollments": 40,
      "availableSlots": 40,
      "startDate": "2025-01-25",
      "endDate": "2025-03-05",
      "enrollmentsCount": 0,
      "isEnrollmentOpen": true,
      "createdAt": "2025-01-15T10:00:00.000000Z",
      "updatedAt": "2025-01-15T10:00:00.000000Z",
      "categories": {
        "data": [
          {
            "id": 1,
            "name": "Web Development",
            "slug": "web-development"
          }
        ]
      }
    }
  ],
  "meta": {
    "pagination": {
      "total": 5,
      "count": 5,
      "per_page": 10,
      "current_page": 1,
      "total_pages": 1
    }
  }
}
```

---

### 3. Get Course by ID

**Endpoint:** `GET /v1/courses/{id}`

**Query Parameters:**
```
include=categories,enrollments      # Include relationships
```

**Example:** `GET /v1/courses/1?include=categories`

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "title": "Advanced Laravel Development",
    "slug": "advanced-laravel-development",
    "description": "Master advanced Laravel concepts including queues, events, broadcasting",
    "instructor": "John Smith",
    "durationHours": 20,
    "difficultyLevel": "advanced",
    "status": "published",
    "maxEnrollments": 30,
    "availableSlots": 28,
    "startDate": "2025-01-22",
    "endDate": "2025-02-21",
    "enrollmentsCount": 2,
    "isEnrollmentOpen": true,
    "createdAt": "2025-01-15T10:00:00.000000Z",
    "updatedAt": "2025-01-15T10:00:00.000000Z",
    "categories": {
      "data": [
        {
          "id": 1,
          "name": "Web Development",
          "slug": "web-development",
          "isActive": true
        }
      ]
    }
  }
}
```

---

### 4. Update Course

**Endpoint:** `PUT /v1/courses/{id}`

**Request Body:**
```json
{
  "title": "Advanced Laravel Development (2025 Edition)",
  "description": "Updated course with Laravel 12 features",
  "durationHours": 25,
  "maxEnrollments": 35,
  "status": "published",
  "categoryIds": [1, 3, 4]
}
```

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "title": "Advanced Laravel Development (2025 Edition)",
    "slug": "advanced-laravel-development",
    "description": "Updated course with Laravel 12 features",
    "instructor": "John Smith",
    "durationHours": 25,
    "difficultyLevel": "advanced",
    "status": "published",
    "maxEnrollments": 35,
    "availableSlots": 33,
    "startDate": "2025-01-22",
    "endDate": "2025-02-21",
    "enrollmentsCount": 2,
    "isEnrollmentOpen": true,
    "createdAt": "2025-01-15T10:00:00.000000Z",
    "updatedAt": "2025-01-15T10:45:00.000000Z",
    "categories": {
      "data": [
        {
          "id": 1,
          "name": "Web Development"
        },
        {
          "id": 3,
          "name": "DevOps"
        },
        {
          "id": 4,
          "name": "Security"
        }
      ]
    }
  },
  "meta": {
    "message": "Course updated successfully"
  }
}
```

---

### 5. Delete Course

**Endpoint:** `DELETE /v1/courses/{id}`

**Note:** Cannot delete courses with active enrollments (status: enrolled or in_progress)

**Response (204 No Content):**
No response body (successful deletion returns HTTP 204)

**Error Response (422) - Has Active Enrollments:**
```json
{
  "error": "Cannot delete course with active enrollments"
}
```

---

## Course Enrollments API

### 1. Enroll Employee in Course

**Endpoint:** `POST /v1/course-enrollments`

**Request Body:**
```json
{
  "courseId": 1,
  "employeeId": 2,
  "notes": "Excited to learn advanced Laravel concepts!"
}
```

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "courseId": 1,
    "employeeId": 2,
    "status": "enrolled",
    "enrolledAt": "2025-01-15T11:00:00.000000Z",
    "completedAt": null,
    "progressPercentage": 0,
    "notes": "Excited to learn advanced Laravel concepts!",
    "createdAt": "2025-01-15T11:00:00.000000Z",
    "updatedAt": "2025-01-15T11:00:00.000000Z",
    "course": {
      "data": {
        "id": 1,
        "title": "Advanced Laravel Development",
        "slug": "advanced-laravel-development",
        "instructor": "John Smith",
        "durationHours": 20,
        "availableSlots": 27
      }
    },
    "employee": {
      "data": {
        "id": 2,
        "userId": 5,
        "role": "Software Engineer"
      }
    }
  },
  "meta": {
    "message": "Successfully enrolled in course"
  }
}
```

**Error Responses:**

**Course Not Published (422):**
```json
{
  "error": "Cannot enroll in a course that is not published"
}
```

**Course at Max Capacity (422):**
```json
{
  "error": "Course is at maximum capacity"
}
```

**Already Enrolled (422):**
```json
{
  "error": "Employee is already enrolled in this course"
}
```

**Enrollment Not Open (422):**
```json
{
  "error": "Enrollment is not currently open for this course"
}
```

---

### 2. List Course Enrollments

**Endpoint:** `GET /v1/course-enrollments`

**Query Parameters:**
```
employee_id=2                   # Filter by employee ID
course_id=1                     # Filter by course ID
status=enrolled                 # Filter by status: enrolled, in_progress, completed, dropped
include=course,employee         # Include relationships
sort_by=enrolled_at             # Sort by: enrolled_at, completed_at
sort_order=desc                 # Sort order: asc, desc
per_page=10                     # Items per page
```

**Example:** `GET /v1/course-enrollments?employee_id=2&status=enrolled&include=course,employee`

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "courseId": 1,
      "employeeId": 2,
      "status": "enrolled",
      "enrolledAt": "2025-01-15T11:00:00.000000Z",
      "completedAt": null,
      "progressPercentage": 0,
      "notes": "Excited to learn!",
      "createdAt": "2025-01-15T11:00:00.000000Z",
      "updatedAt": "2025-01-15T11:00:00.000000Z",
      "course": {
        "data": {
          "id": 1,
          "title": "Advanced Laravel Development",
          "instructor": "John Smith",
          "status": "published"
        }
      },
      "employee": {
        "data": {
          "id": 2,
          "role": "Software Engineer",
          "user": {
            "data": {
              "firstName": "Jane",
              "lastName": "Doe",
              "email": "jane.doe@example.com"
            }
          }
        }
      }
    }
  ]
}
```

---

### 3. Unenroll Employee from Course

**Endpoint:** `DELETE /v1/course-enrollments/{id}`

**Note:** Updates enrollment status to 'dropped' instead of deleting

**Response (204 No Content):**
No response body (successful unenrollment returns HTTP 204)

---

## Common Response Codes

- **200 OK** - Successful GET, PUT
- **201 Created** - Successful POST (resource created)
- **204 No Content** - Successful DELETE (no response body)
- **401 Unauthorized** - Missing or invalid token
- **403 Forbidden** - Insufficient permissions
- **404 Not Found** - Resource not found
- **422 Unprocessable Entity** - Validation errors or business logic errors
- **500 Internal Server Error** - Server error

---

## Validation Error Response Format

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": [
      "The title field is required."
    ],
    "email": [
      "The email has already been taken."
    ]
  }
}
```

---

## Postman Collection Tips

### Setting Up Environment Variables

Create a Postman environment with these variables:

```
base_url = http://localhost:8000
api_version = v1
```

### Pre-request Script for Headers

Add this to your collection's pre-request script:

```javascript
pm.request.headers.add({
    key: 'Accept',
    value: 'application/json'
});
pm.request.headers.add({
    key: 'Content-Type',
    value: 'application/json'
});
```

### Example Collection Structure

```
Course Management System
├── Course Categories
│   ├── Create Category
│   ├── List Categories
│   ├── Get Category by ID
│   ├── Update Category
│   └── Delete Category
├── Courses
│   ├── Create Course
│   ├── List Courses
│   ├── List Courses (Filtered)
│   ├── Get Course by ID
│   ├── Update Course
│   └── Delete Course
└── Course Enrollments
    ├── Enroll Employee
    ├── List Enrollments
    ├── List My Enrollments
    └── Unenroll
```

---

## Testing Workflow

### 1. Test Course Categories

```
1. Create 2-3 categories
2. List all categories
3. Get one category by ID
4. Update a category
5. Try to delete a category
```

### 2. Test Courses

```
1. Create a course with categories
2. List courses with filters
3. Get course by ID with includes
4. Update course (change categories)
5. Try to delete course
```

### 3. Test Enrollments

```
1. Enroll employee in a published course
2. Try to enroll same employee again (should fail)
3. List enrollments for employee
4. Try to enroll in draft course (should fail)
5. Try to enroll in full course (should fail)
6. Unenroll from course
```

### 4. Test Edge Cases

```
1. Enroll in course with max capacity
2. Enroll outside date range
3. Update course status to draft (with enrollments)
4. Delete course with active enrollments (should fail)
```

---

## Sample Test Scenarios

### Scenario 1: Complete Course Creation and Enrollment

```
Step 1: Create Category
POST /v1/course-categories
Body: { "name": "Testing", "description": "Test courses", "isActive": true }

Step 2: Create Course
POST /v1/courses
Body: { "title": "API Testing", "categoryIds": [11], "status": "published", "maxEnrollments": 5 }

Step 3: Enroll Employee
POST /v1/course-enrollments
Body: { "courseId": 13, "employeeId": 1 }

Step 4: Verify Enrollment
GET /v1/course-enrollments?employee_id=1
```

### Scenario 2: Test Capacity Limits

```
Step 1: Create course with maxEnrollments: 2
Step 2: Enroll employee 1 (success)
Step 3: Enroll employee 2 (success)
Step 4: Enroll employee 3 (should fail - at capacity)
Step 5: Unenroll employee 1
Step 6: Enroll employee 3 (should succeed now)
```

---

## Notes

- ⚠️ Authentication has been disabled for testing - all endpoints are publicly accessible
- Enrollment notifications are sent asynchronously via queue
- Available slots are calculated in real-time
- Dates should be in YYYY-MM-DD format
- Timestamps are returned in ISO 8601 format
