# 4vGYM API REST Documentation

## Project Overview
This project implements a REST API for managing 4vGYM's activities, monitors, and activity types. The API is designed with a relational database backend and includes field validations for all POST and PUT operations. Below are the key functionalities and endpoints of the API.

---

## Endpoints

### **Activity Types**
Manage and retrieve types of activities.

- **GET /activity-types**
  - Returns a list of activity types.
  - Each type includes:
    - `id`: Unique identifier.
    - `name`: Name of the activity.
    - `requiredMonitors`: Number of monitors required for this activity.

---

### **Monitors**
Manage gym monitors.

- **GET /monitors**
  - Returns a list of monitors.
  - Each monitor includes:
    - `id`: Unique identifier.
    - `name`: Name of the monitor.
    - `email`: Email address.
    - `phone`: Phone number.
    - `photo`: Photo URL or file.

- **POST /monitors**
  - Adds a new monitor.
  - Requires a JSON payload with:
    - `name`: Monitor's name.
    - `email`: Monitor's email.
    - `phone`: Monitor's phone number.
    - `photo`: Monitor's photo URL or file.
  - Returns the created monitor's JSON.

- **PUT /monitors**
  - Updates an existing monitor.
  - Requires a JSON payload with the monitor's ID and updated fields.

- **DELETE /monitors**
  - Deletes a monitor by ID.

---

### **Activities**
Manage gym activities.

- **GET /activities**
  - Returns a list of activities.
  - Supports filtering by date (`dd-MM-yyyy` format).
  - Each activity includes:
    - `id`: Unique identifier.
    - `type`: Activity type information.
    - `monitors`: List of assigned monitors.
    - `date`: Scheduled date and time.
  
- **POST /activities**
  - Creates a new activity.
  - Requires a JSON payload with:
    - `type`: ID of the activity type.
    - `monitors`: List of monitor IDs.
    - `date`: Date in `dd-MM-yyyy` format.
  - Validations:
    - The number of assigned monitors must meet the requirement for the activity type.
    - Only 90-minute classes are allowed, starting at:
      - 09:00
      - 13:30
      - 17:30
  - Returns the created activity's JSON.

- **PUT /activities**
  - Updates an existing activity.
  - Requires a JSON payload with the activity's ID and updated fields.

- **DELETE /activities**
  - Deletes an activity by ID.

---

## Database Design

### Tables
1. **Monitors**
   - Stores monitor information.
2. **Activity Types**
   - Stores details of activity types.
3. **Activities**
   - Stores activities and references:
     - Activity Types (Foreign Key).
   - Fields include:
     - ID, Date, and other activity-specific details.
4. **Activities-Monitors**
   - Handles the many-to-many relationship between activities and monitors.
