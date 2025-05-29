## Water Billing & Customer Management System

WBCMS is a web-based application designed to help water service providers manage customer data, automate billing processes, and generate reports efficiently.  
- It offers a user-friendly dashboard with full CRUD operations for clients, billing, and user accounts.

---

### ✨ Key Features

1. 👤 **User Account Management**  
   - Create new users with different access roles

2. 🧾 **Client Management**  
   - Add, update, view, and delete customer records

3. 💵 **Water Billing**  
   - Auto-generate monthly bills based on water usage

4. 📊 **Reporting System**  
   - Generate detailed billing summaries by month

---

### 📸 Screenshots

#### 💻 Desktop View

| Login | Dashboard |
|-------|-----------|
| ![Login](img/login.png) | ![Dashboard](img/dashboard.png) |

| Client List | Billing List |
|-------------|--------------|
| ![Clients](img/clients.png) | ![Billing](img/billing.png) |

#### 📱 Tablet View

| Dashboard | Clients |
|-----------|---------|
| ![Tablet Dashboard](img/dashboard-tablet.png) | ![Tablet Clients](img/clients-tablet.png) |

#### 📱 Mobile View

| Mobile Dashboard 1 | Mobile Dashboard 2 |
|--------------------|--------------------|
| ![Mobile1](img/dashboard-mobile.png) | ![Mobile2](img/dashboard-mobile2.png) |

---

### 🛠️ Tech Stack

| Layer      | Technology                  |
|------------|-----------------------------|
| Backend    | PHP (OOP), MySQL            |
| Frontend   | HTML, CSS (Bootstrap), JS   |
| Server     | XAMPP (Apache + MySQL)      |

---

### ⚙️ Setup Instructions

#### ✅ Prerequisites

- [XAMPP](https://www.apachefriends.org/index.html)
- [Git](https://git-scm.com/)
- [Web browser](https://www.google.com/chrome/?brand=JJTC&ds_kid=43700078658943277&gad_source=1&gbraid=0AAAAAoY3CA5GHUQqNEVj6lSdNG2dl9mfR&gclid=Cj0KCQjwqv2_BhC0ARIsAFb5Ac-Hc71PXrPPmd5Vmgfy1ROmeL0HeAI75YQeXlmgpikaSFs7lIMuKP8aAoj_EALw_wcB&gclsrc=aw.ds)

#### 🔧 Installation Steps

1. **Clone the Project**
   ```bash
   cd xampp/htdocs
   git clone https://github.com/Your-Username/wbcms.git
   ```

2. **Set Up the Database**
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Create a new database: `wbcms_db`
   - Import `wbcms_db.sql` from the `database` folder

3. **Run the Application**
   ```bash
   http://localhost/wbcms/
   ```

4. **Login Credentials**
   - Register account to login

---

### ✅ Project Status

- ✅ Functional core modules: user, client, billing, and reports  
- 🔜 Coming soon: SMS/email notifications and payment integration

---

### 📁 Folder Structure

```
wbcms/
├── config/       # Database configuration files
├── css/          # Stylesheets (CSS)
├── js/           # JavaScript files
├── database/     # SQL file(s) for setup
├── docs/         # Documentation (txt, pdf, word, drawings)
├── inc/          # PHP includes (classes, logic)
├── index.php     # Main entry point
├── README.md     # 

```