<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    header("Location: ../login.php");
    exit();
}
require_once('../classes/database.php');

$con = new database();

if (isset($_POST['delete']) && isset($_POST['id']) && isset($_POST['table'])) {
    switch ($_POST['table']) {
        case 'menu':
            $con->archiveMenu($_POST['id']);
            break;
        // case 'employee':
        //     $con->archiveEmployee($_POST['id']);
        //     break;
        // case 'customer':
        //     $con->archiveCustomer($_POST['id']);
        //     break;
        // case 'orders':
        //     $con->archiveOrder($_POST['id']);
        //     break;
        // case 'payment':
        //     $con->archivePayment($_POST['id']);
        //     break;
    }
    echo "<meta http-equiv='refresh' content='0'>";
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sandok ni Binggay Admin Dashboard</title>

  <link rel="stylesheet" href="../css/admin_homepage.css">
  <link rel="icon" type="image/jpeg" href="../images/logo1.png">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Boxicons for icons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

  <!-- Sidebar -->
 <nav class="sidebar d-flex flex-column justify-content-between" id="sidebar">
  <div>
    <div class="logo-section">
      <i class="icon icon-lg"><img src="../images/logo1.png" alt=" "></i>
      <h5 class="mt-2">Sandok ni Binggay</h5>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="#" data-section="food"><i class='bx bx-food-menu'></i><span>Food Menu</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" data-section="orders"><i class='bx bx-cart'></i><span>Orders</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" data-section="catering"><i class='bx bx-gift'></i><span>Catering Packages</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" data-section="events"><i class='bx bx-calendar-event'></i><span>Event Bookings</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" data-section="category"><i class='bx bx-category'></i><span>Food Category</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" data-section="employees"><i class='bx bx-user'></i><span>Employees</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../logout.php" data-section="logout" id="logoutBtn"><i class='bx bx-log-out'></i><span>Logout</span></a>
      </li>
    </ul>
  </div>
  <!-- Bottom section with toggle -->
  <div class="bottom-section text-center">
    <div class="toggle-btn-wrapper">
      <button class="btn btn-outline-black w-20" id="toggleSidebar">
        <i class='bx bx-chevrons-left'></i>
      </button>
    </div>
  </div>
</nav>

   
<main class="content">
  <div class="container-fluid">

    <!-- Food Menu Section -->
    <section id="foodSection"  >
      <div class="row mb-4">
        <div class="col text-center">
          <h2 class="mt-2">Food Menu</h2>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col text-end">
          <a class="btn btn-success" href="add_menu.php" id="addFood">
            <i class='bx bx-plus'></i> Add New Food
          </a>
        </div>
      </div>
      <!-- Food Menu Filter Form -->
      <form class="row g-2 mb-3" method="get" id="foodFilterForm">
        <div class="col-md-3">
          <select class="form-select" name="food_category">
            <option value="">All Categories</option>
            <?php
            $categories = $con->getAllCategories();
            foreach ($categories as $cat) {
              $selected = ($foodFilters['category'] == $cat['category_id']) ? 'selected' : '';
              echo "<option value='{$cat['category_id']}' $selected>" . htmlspecialchars($cat['category_name']) . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-2">
  <select class="form-select" name="food_pax">
    <option value="">All Pax/Pieces</option>
    <option value="6-8" <?= ($foodFilters['pax'] == '6-8') ? 'selected' : '' ?>>6-8 Pax</option>
    <option value="10-15" <?= ($foodFilters['pax'] == '10-15') ? 'selected' : '' ?>>10-15 Pax</option>
    <option value="20-30" <?= ($foodFilters['pax'] == '20-30') ? 'selected' : '' ?>>20-30 Pax</option>
    <option value="50-100" <?= ($foodFilters['pax'] == '50-100') ? 'selected' : '' ?>>50-100 Pax</option>
    <option value="piece" <?= ($foodFilters['pax'] == 'piece') ? 'selected' : '' ?>>Piece (pcs)</option>
  </select>
</div>
        <div class="col-md-2">
          <select class="form-select" name="food_avail">
            <option value="">All Availability</option>
            <option value="1" <?= ($foodFilters['avail'] === '1') ? 'selected' : '' ?>>Available</option>
            <option value="0" <?= ($foodFilters['avail'] === '0') ? 'selected' : '' ?>>Not Available</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select" name="food_sort">
            <option value="">Normal (View All)</option>
            <option value="price_asc" <?= ($foodFilters['sort'] == 'price_asc') ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_desc" <?= ($foodFilters['sort'] == 'price_desc') ? 'selected' : '' ?>>Price: High to Low</option>
            <option value="alpha_asc" <?= ($foodFilters['sort'] == 'alpha_asc') ? 'selected' : '' ?>>A-Z</option>
            <option value="alpha_desc" <?= ($foodFilters['sort'] == 'alpha_desc') ? 'selected' : '' ?>>Z-A</option>
            <option value="pax_asc" <?= ($foodFilters['sort'] == 'pax_asc') ? 'selected' : '' ?>>Pax: Low to High</option>
            <option value="pax_desc" <?= ($foodFilters['sort'] == 'pax_desc') ? 'selected' : '' ?>>Pax: High to Low</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">Apply</button>
        </div>
      </form>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>Potahe ID</th>
              <th>Potahe Name</th>
              <th>Potahe Description</th>
              <th>Potahe Pax</th>
              <th>Potahe Price (₱)</th>
              <th>Potahe Availability</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php
          if (empty($data)) {
          ?>
            <tr>
              <td colspan="7" class="text-center">No Food Menu to display.</td>
            </tr>
          <?php
          } else {
            foreach ($data as $rows) {
          ?>
            <tr>
              <td><?php echo $rows['menu_id']?></td>
              <td><?php echo $rows['menu_name']?></td>
              <td><?php echo $rows['menu_desc']?></td>
              <td><?php echo $rows['menu_pax']?></td>
              <td><?php echo $rows['menu_price']?></td>
              <td><?php echo $rows['menu_avail']?></td>
              <td>
                <div class="btn-group" role="group">
                  <form action="update_food.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $rows['menu_id']; ?>">  
                    <button type="submit" class="btn btn-warning btn-sm">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                  </form>
                  <form method="POST" class="mx-1">
                    <input type="hidden" name="id" value="<?php echo $rows['menu_id']; ?>">
                    <input type="hidden" name="table" value="menu">
                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">
                      <i class="bi bi-x-square"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php
            }
          }
          ?>
          </tbody>
        </table>
      </div>
    </section>

      <!-- Orders Section -->
      <section id="ordersSection" class="dashboard-section d-none">
        <div class="row mb-4">
          <div class="col text-center">
            <h2>Orders</h2>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col text-end">
           
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Order Items</th>
                <th>Order Address</th>
                <th>Order Date</th>
                <th>Order Status</th>
                <th>Order Amount (₱)</th>
                <th>Order Needed</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
$orders = $con->viewOrders();
if (empty($orders)) {
?>
  <tr>
    <td colspan="11" class="text-center">No orders to display.</td>
  </tr>
<?php
} else {
  foreach ($orders as $order) {
    $userDisplay = $order['user_id'];
    if (!empty($order['user_fn']) || !empty($order['user_ln'])) {
      $userDisplay = htmlspecialchars(trim($order['user_fn'] . ' ' . $order['user_ln']));
    }
    // Fetch order items
    $orderItems = $con->getOrderItems($order['order_id']);
    $itemList = [];
    foreach ($orderItems as $item) {
      $qty = isset($item['oi_quantity']) ? $item['oi_quantity'] : 1;
      $price = isset($item['oi_price']) ? $item['oi_price'] : 0;
      $itemList[] = htmlspecialchars($item['menu_name']) . " (x" . $qty . ") - ₱" . number_format($price, 2);
    }
    // Fetch order address
    $address = $con->getOrderAddress($order['order_id']);
    $addressDisplay = $address
      ? htmlspecialchars($address['oa_street']) . ', ' . htmlspecialchars($address['oa_city']) . ', ' . htmlspecialchars($address['oa_province'])
      : '<span class="text-muted">No address</span>';
?>
  <tr>
    <td><?= $order['order_id'] ?></td>
    <td><?= $userDisplay ?></td>
    <td><?= implode('<br>', $itemList) ?></td>
    <td><?= $addressDisplay ?></td>
    <td><?= htmlspecialchars($order['order_date']) ?></td>
    <td><?= htmlspecialchars($order['order_status']) ?></td>
    <td><?= htmlspecialchars(number_format($order['order_amount'], 2)) ?></td>
    <td><?= htmlspecialchars($order['order_needed']) ?></td>
    <td><?= htmlspecialchars($order['created_at']) ?></td>
    <td><?= htmlspecialchars($order['updated_at']) ?></td>
    <td>
      <!-- Actions (edit/delete/view) can go here -->
    </td>
  </tr>
<?php
  }
}
?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Catering Packages Section -->
<section id="cateringSection" class="d-none">
  <div class="row mb-4">
    <div class="col text-center">
      <h2>Catering Bookings</h2>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>CP ID</th>
          <th>User</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Venue</th>
          <th>Date</th>
          <th>Notes</th>
          <th>Price (₱)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $cateringBookings = $con->viewCateringBookings();
        if (empty($cateringBookings)) {
        ?>
          <tr>
            <td colspan="9" class="text-center">No catering bookings to display.</td>
          </tr>
        <?php
        } else {
          foreach ($cateringBookings as $cp) {
        ?>
          <tr>
            <td><?php echo htmlspecialchars($cp['cp_id']); ?></td>
            <td><?php echo htmlspecialchars($cp['user_username'] ?? 'Guest'); ?></td>
            <td><?php echo htmlspecialchars($cp['cp_name']); ?></td>
            <td><?php echo htmlspecialchars($cp['cp_phone']); ?></td>
            <td><?php echo htmlspecialchars($cp['cp_place']); ?></td>
            <td><?php echo htmlspecialchars($cp['cp_date']); ?></td>
            <td><?php echo htmlspecialchars($cp['cp_desc']); ?></td>
            <td><?php echo htmlspecialchars($cp['cp_price']); ?></td>
            
          </tr>
        <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</section>


      <!-- Event Bookings Section -->
<section id="eventsSection" class="dashboard-section d-none">
  <div class="row mb-4">
    <div class="col text-center">
      <h2>Event Bookings</h2>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>EB ID</th>
          <th>User</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Type</th>
          <th>Venue</th>
          <th>Date</th>
          <th>Guests</th>
          <th>Order</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $bookings = $con->viewEventBookings();
        if (empty($bookings)) {
        ?>
          <tr>
            <td colspan="12" class="text-center">No event bookings to display.</td>
          </tr>
        <?php
        } else {
          foreach ($bookings as $eb) {
        ?>
          <tr>
            <td><?php echo htmlspecialchars($eb['eb_id']); ?></td>
            <td><?php echo htmlspecialchars($eb['user_username'] ?? 'Guest'); ?></td>
            <td><?php echo htmlspecialchars($eb['eb_name']); ?></td>
            <td><?php echo htmlspecialchars($eb['eb_contact']); ?></td>
            <td><?php echo htmlspecialchars($eb['eb_type']); ?></td>
            <td><?php echo htmlspecialchars($eb['eb_venue']); ?></td>
            <td><?php echo htmlspecialchars($eb['eb_date']); ?></td>
            <td><?php echo htmlspecialchars($eb['eb_guest']); ?></td>
            <td><?php echo htmlspecialchars($eb['eb_order']); ?></td>
            <td><?php echo htmlspecialchars($eb['eb_status']); ?></td>
            <td><?php echo htmlspecialchars($eb['created_at']); ?></td>
            <td>
              <a href="update_eb.php?eb_id=<?php echo $eb['eb_id']; ?>" class="btn btn-sm btn-primary mb-1">
                Update
              </a>
              <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                <input type="hidden" name="delete" value="1">
                <input type="hidden" name="id" value="<?php echo $eb['eb_id']; ?>">
                <input type="hidden" name="table" value="eventbookings">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</section>


      <!-- Food Category Section -->
<section id="categorySection" class="dashboard-section d-none">
  <div class="row mb-4">
    <div class="col text-center">
      <h2>Food Category</h2>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col text-end">
      <a class="btn btn-success" href="add_category.php" id="addCategory">
        <i class='bx bx-plus'></i> Add New Category
      </a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>Category ID</th>
          <th>Category Name</th>
          <th>Food</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $categories = $con->viewCategories();
        if (empty($categories)) {
        ?>
          <tr>
            <td colspan="4" class="text-center">No categories to display.</td>
          </tr>
        <?php
        } else {
          foreach ($categories as $cat) {
            // Get menu items for this category
            $foods = $con->getMenuItemsByCategory($cat['category_id']);
            $foodNames = [];
            foreach ($foods as $food) {
              $foodNames[] = htmlspecialchars($food['menu_name']);
            }
        ?>
          <tr>
            <td><?= $cat['category_id'] ?></td>
            <td><?= htmlspecialchars($cat['category_name']) ?></td>
            <td><?= implode(', ', $foodNames) ?></td>
            <td>
              <a href="update_category.php?id=<?= $cat['category_id'] ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i> Edit
              </a>
            </td>
          </tr>
        <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</section>

      <!-- Employees Section -->
      <section id="employeesSection" class="dashboard-section d-none">
        <div class="row mb-4">
          <div class="col text-center">
            <h2>Employees</h2>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col text-end">
            <a class="btn btn-success" href="add_employee.php" id="addEmployee">
              <i class='bx bx-plus'></i> Add New Employee
            </a>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Sex</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $empData = $con->viewEmployee();
              if (empty($empData)) {
              ?>
                <tr>
                  <td colspan="9" class="text-center">No employees to display.</td>
                </tr>
              <?php
              } else {
                foreach ($empData as $emp) {
              ?>
                <tr>
                  <td><?php echo $emp['emp_id']; ?></td>
                  <td><?php echo $emp['emp_fn']; ?></td>
                  <td><?php echo $emp['emp_ln']; ?></td>
                  <td><?php echo $emp['emp_sex']; ?></td>
                  <td><?php echo $emp['emp_email']; ?></td>
                  <td><?php echo $emp['emp_phone']; ?></td>
                  <td><?php echo $emp['emp_role']; ?></td>
                  <td><?php echo isset($emp['created_at']) ? $emp['created_at'] : ''; ?></td>
                  <td>
                    <div class="btn-group" role="group">
                      <form action="update_employee.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $emp['emp_id']; ?>">
                        <button type="submit" class="btn btn-warning btn-sm">
                          <i class="bi bi-pencil-square"></i> Update
                        </button>
                      </form>
                      <!-- You can add a delete button here if needed -->
                    </div>
                  </td>
                </tr>
              <?php
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Placeholder for other sections (invisible for now) -->
      <section id="otherContent" class="d-none">
        <div class="text-center">
          <h3>Content for this section is not available yet.</h3>
        </div>
      </section>

    </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Toggle Sidebar -->
  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const toggleIcon = toggleBtn.querySelector('i');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('sidebar-collapsed');
      if (sidebar.classList.contains('sidebar-collapsed')) {
        toggleIcon.classList.remove('bx-chevrons-left');
        toggleIcon.classList.add('bx-chevrons-right');
      } else {
        toggleIcon.classList.remove('bx-chevrons-right');
        toggleIcon.classList.add('bx-chevrons-left');
      }
    });
  </script>

  <!-- Navigation Script -->
  <script>
document.addEventListener('DOMContentLoaded', function() {
  // Universal section navigation
  function showSection(section) {
    document.querySelectorAll('section').forEach(s => s.classList.add('d-none'));
    const activeSection = document.getElementById(section + 'Section');
    if (activeSection) activeSection.classList.remove('d-none');
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    const navLink = document.querySelector('.nav-link[data-section="' + section + '"]');
    if (navLink) navLink.classList.add('active');
  }

  // On page load, show section from URL or default to food
  const urlParams = new URLSearchParams(window.location.search);
  let section = urlParams.get('section') || 'food';
  showSection(section);

  // Sidebar navigation click
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
      const section = link.getAttribute('data-section');
      if (section === 'logout') {
        window.location.href = link.getAttribute('href');
        return;
      }
      e.preventDefault();
      showSection(section);
      // Update URL without reloading
      if (history.pushState) {
        const url = new URL(window.location);
        url.searchParams.set('section', section);
        window.history.pushState({}, '', url);
      }
    });
  });
});
</script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get section from URL (default to 'food' if not set)
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'food';

    // Hide all sections, show the selected one
    document.querySelectorAll('section').forEach(s => s.classList.add('d-none'));
    const activeSection = document.getElementById(section + 'Section');
    if (activeSection) activeSection.classList.remove('d-none');

    // Set sidebar active state
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    const navLink = document.querySelector('.nav-link[data-section="' + section + '"]');
    if (navLink) navLink.classList.add('active');
    });
</script>

</body>
</html>