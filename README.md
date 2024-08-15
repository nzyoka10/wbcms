## Water Billing and Customer Management System

- This is a web-based application designed to streamline water usage `tracking`, `billing`, and `customer management` processes.
- It allows users to register for water connections, record monthly meter readings, generate bills, process payments, and generate detailed reports.

<details>
  <summary style="font-weight: bold; font-size: 1.2em; color: red;">Application Screenshots</summary>

  <h3 style="margin-top: 20px; font-size: 1.5em; color: #333;">Desktop View</h3>

  <!-- First Row -->
  <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
    <div style="flex: 1; margin-right: 10px;">
      <img src="img/login.png" alt="Login form" style="width: 100%; max-width: 250px; border: 1px solid #ddd; border-radius: 4px;">
      <p style="text-align: center; margin-top: 10px; font-size: 0.9em; color: #666;">Login Page</p>
    </div>
    <div style="flex: 1;">
      <img src="img/dashboard.png" alt="Dashboard" style="width: 100%; max-width: 250px; border: 1px solid #ddd; border-radius: 4px;">
      <p style="text-align: center; margin-top: 10px; font-size: 0.9em; color: #666;">Main Dashboard</p>
    </div>
  </div>

  <!-- Second Row -->
  <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
    <div style="flex: 1; margin-right: 10px;">
      <img src="img/clients.png" alt="Register" style="width: 100%; max-width: 250px; border: 1px solid #ddd; border-radius: 4px;">
      <p style="text-align: center; margin-top: 10px; font-size: 0.9em; color: #666;">Listing of Clients</p>
    </div>
    <div style="flex: 1;">
      <img src="img/cl-listing.png" alt="Client Listing" style="width: 100%; max-width: 250px; border: 1px solid #ddd; border-radius: 4px;">
      <p style="text-align: center; margin-top: 10px; font-size: 0.9em; color: #666;">Client Listing</p>
    </div>
  </div>

</details>



### Features

- **User Registration:** User create account to access the system, (``Admin``, ``Staff``).
- **Meter Management:** Tracks water meters, their installation dates, and statuses.
- **Billing:** Generates monthly bills for customers based on meter readings.
- **Payment Processing:** Accepts payments via various methods like M-Pesa.
- **Notification:** Sends billing receipts via SMS to customers.
- **Reporting:** Provides detailed reports on usage trends and financial performance.

### Technologies Used

- `Frontend`: **HTML**, **CSS**, **JavaScript**.
- `Backend`: **PHP**
- `Database`: **MySQL**
- `Payment Integration`: **M-Pesa API**
- `SMS Gateway`: **Africa’s Talking**
