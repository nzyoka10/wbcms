 <!-- 
  File: inc/sidebar.php
    - This file contains the sidebar navigation for the WBCMS dashboard.
    - It is included in the dashboard.php file.

 -->
 <!-- Sidebar -->
  <aside id="sidebar">
      <div class="sidebar-title">
        <div class="sidebar-brand">
        <span class="material-icons-outlined">water_drop</span>&nbsp;WBCMS
        </div>
        <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
      </div>

      <ul class="sidebar-list">
        <li class="sidebar-list-item">
          <a href="./dashboard.php">
            <span class="material-icons-outlined">speed</span>&nbsp;&nbsp;Dashboard
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="./client.php">
            <span class="material-icons-outlined">group_add</span>&nbsp;&nbsp;Clients
          </a>
        </li>

        <li class="sidebar-list-item">
          <a href="./billings.php">
            <span class="material-icons-outlined">payments</span>&nbsp;&nbsp;Billings
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="./report.php">
            <span class="material-icons-outlined">receipt_long</span>&nbsp;&nbsp;Report
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="./settings.php">
            <span class="material-icons-outlined">settings</span>&nbsp;&nbsp;Settings
          </a>
        </li>
        <li class="sidebar-list-item">
          <a href="./logout.php">
            <span class="material-icons-outlined">logout</span>&nbsp;&nbsp;Logout
          </a>
        </li>
   
      </ul>
    </aside>
    <!-- End Sidebar -->