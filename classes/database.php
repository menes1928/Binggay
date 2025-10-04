<?php
class database {

    function opencon() {
        return new PDO(
            dsn: 'mysql:host=localhost;dbname=sandok',
            username: 'root',
            password: ''
        );
    }

    // MENU

    function addMenu($name, $desc, $pax, $price, $avail) {
        try {
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("INSERT INTO menu (menu_name, menu_desc, menu_pax, menu_price, menu_avail) VALUES (?, ?, ?, ?, ?)");
            $query->execute([$name, $desc, $pax, $price, $avail]);
            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function viewMenu() {
        $con = $this->opencon();
        return $con->query("SELECT * FROM menu WHERE is_deleted = 0")->fetchAll();
    }

    function viewMenuID($id) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Menu WHERE menu_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateMenu($id, $name, $desc, $pax, $price, $avail, $menu_pic) {
    try {
        $con = $this->opencon();
        $con->beginTransaction();
        $query = $con->prepare("UPDATE menu SET menu_name = ?, menu_desc = ?, menu_pax = ?, menu_price = ?, menu_avail = ?, menu_pic = ? WHERE menu_id = ?");
        $query->execute([$name, $desc, $pax, $price, $avail, $menu_pic, $id]);
        $con->commit();
        return true;
    } catch (PDOException $e) {
        $con->rollBack();
        return false;
    }
}

     function archiveMenu($id) {
            $con = $this->opencon();
            $stmt = $con->prepare("UPDATE Menu SET is_deleted = 1 WHERE menu_id = ?");
            return $stmt->execute([$id]);
        }


        // EMPLOYEE
    function viewEmployee() {
        $con = $this->opencon();
        return $con->query("SELECT * FROM employee")->fetchAll();
    }

    function viewEmployeeID($id) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM employee WHERE emp_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateEmployee($id, $fn, $ln, $sex, $email, $phone, $role) {
        try {
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("UPDATE employee SET emp_fn = ?, emp_ln = ?, emp_sex = ?, emp_email = ?, emp_phone = ?, emp_role = ? WHERE emp_id = ?");
            $query->execute([$fn, $ln, $sex, $email, $phone, $role, $id]);
            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }

    }

    function addEmployee($fn, $ln, $sex, $email, $phone, $role) {
        try {
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("INSERT INTO employee (emp_fn, emp_ln, emp_sex, emp_email, emp_phone, emp_role) VALUES (?, ?, ?, ?, ?, ?)");
            $query->execute([$fn, $ln, $sex, $email, $phone, $role]);
            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function addPromotion($name, $desc, $disc, $start, $end) {
    try {
        $con = $this->opencon();
        $con->beginTransaction();

        $query = $con->prepare("INSERT INTO promotion (promotion_name, promotion_desc, promotion_disc, promotion_start, promotion_end)
                                VALUES (?, ?, ?, ?, ?)");

        $query->execute([$name, $desc, $disc, $start, $end]);

        $con->commit();
        return true;
    } catch (PDOException $e) {
        if (isset($con)) {
            $con->rollBack();
        }
        // Optional: Log error for debugging
        // error_log("Add Promotion Error: " . $e->getMessage());
        return false;
    }
}


// USER
function signupUser($firstname, $lastname, $sex, $email, $phone, $username, $password, $user_photo, $user_type) {
        try {
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare(
                "INSERT INTO users (user_fn, user_ln, user_sex, user_email, user_phone, user_username, user_password, user_photo, user_type, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
            );
            $query->execute([
                $firstname,
                $lastname,
                $sex,
                $email,
                $phone,
                $username,
                $password,
                $user_photo,
                $user_type
            ]);
            $userId = $con->lastInsertId();
            $con->commit();
            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function loginUser($username, $password) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM users WHERE user_username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['user_password'])) {
            return $user;
        }
        return false;
    }

    function getUserByUsername($username) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM users WHERE user_username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getUserById($user_id) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    function getGroupedMenuWithCategories() {
    $con = $this->opencon();
    $sql = "
        SELECT 
            m.menu_name,
            GROUP_CONCAT(m.menu_id) AS menu_ids,
            GROUP_CONCAT(m.menu_pax) AS menu_pax,
            GROUP_CONCAT(m.menu_price) AS menu_price,
            GROUP_CONCAT(m.menu_pic) AS menu_pics,
            GROUP_CONCAT(DISTINCT c.category_name) AS categories,
            MIN(m.menu_desc) AS menu_desc
        FROM menu m
        LEFT JOIN menucategory mc ON m.menu_id = mc.menu_id
        LEFT JOIN category c ON mc.category_id = c.category_id
        WHERE m.menu_avail = 1 AND m.is_deleted = 0
        GROUP BY m.menu_name
        ORDER BY m.menu_name ASC
    ";
    $result = $con->query($sql);
    $menuItems = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $row['categories'] = array_filter(explode(',', $row['categories']));
        $row['menu_ids'] = explode(',', $row['menu_ids']);
        $row['pax_options'] = explode(',', $row['menu_pax']);
        $row['price_options'] = explode(',', $row['menu_price']);
        $row['menu_pics'] = explode(',', $row['menu_pics']);
        $menuItems[] = $row;
    }
    return $menuItems;
}

function getAllCategories() {
    $con = $this->opencon();
    $cat_sql = "SELECT * FROM category ORDER BY category_name ASC";
    $cat_result = $con->query($cat_sql);
    return $cat_result->fetchAll(PDO::FETCH_ASSOC);
}


function getMenuItemsByCategory($category_id) {
    $con = $this->opencon();
    $sql = "
        SELECT 
            m.menu_name,
            GROUP_CONCAT(m.menu_id) AS menu_ids,
            GROUP_CONCAT(m.menu_pax) AS menu_pax,
            GROUP_CONCAT(m.menu_price) AS menu_price,
            GROUP_CONCAT(m.menu_pic) AS menu_pics,
            MIN(m.menu_desc) AS menu_desc
        FROM menu m
        LEFT JOIN menucategory mc ON m.menu_id = mc.menu_id
        WHERE m.menu_avail = 1 AND m.is_deleted = 0 AND mc.category_id = ?
        GROUP BY m.menu_name
        ORDER BY m.menu_name ASC
    ";
    $stmt = $con->prepare($sql);
    $stmt->execute([$category_id]);
    $menuItems = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['menu_ids'] = explode(',', $row['menu_ids']);
        $row['pax_options'] = explode(',', $row['menu_pax']);
        $row['price_options'] = explode(',', $row['menu_price']);
        $row['menu_pics'] = explode(',', $row['menu_pics']);
        $menuItems[] = $row;
    }
    return $menuItems;
}


// category

function addCategory($category_name) {
    $con = $this->opencon();
    $stmt = $con->prepare("INSERT INTO category (category_name) VALUES (?)");
    return $stmt->execute([$category_name]);
}

function viewCategories() {
    $con = $this->opencon();
    $stmt = $con->query("SELECT * FROM category ORDER BY category_id ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function emailExists($email) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT user_email FROM users WHERE user_email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() ? true : false;
}


function addOrder($user_id, $order_date, $order_status, $order_amount, $order_needed) {
    try {
        $con = $this->opencon();
        $stmt = $con->prepare("INSERT INTO orders (user_id, order_date, order_status, order_amount, order_needed) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $order_date, $order_status, $order_amount, $order_needed]);
        return $con->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

function savePayment($order_id, $cp_id, $user_id, $pay_date, $pay_amount, $pay_method, $pay_status) {
    try {
        $con = $this->opencon();
        $stmt = $con->prepare("INSERT INTO payments (order_id, cp_id, user_id, pay_date, pay_amount, pay_method, pay_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $cp_id, $user_id, $pay_date, $pay_amount, $pay_method, $pay_status]);
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function viewOrders() {
    $con = $this->opencon();
    $sql = "SELECT o.*, u.user_fn, u.user_ln
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.user_id
            ORDER BY o.order_date DESC";
    $stmt = $con->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderItems($order_id) {
    $con = $this->opencon();
    $sql = "SELECT oi.*, m.menu_name 
            FROM orderitems oi
            LEFT JOIN menu m ON oi.menu_id = m.menu_id
            WHERE oi.order_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderAddress($order_id) {
    $con = $this->opencon();
    $sql = "SELECT * FROM orderaddress WHERE order_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$order_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// EVENT BOOKINGS
function addEventBooking($user_id, $eb_name, $eb_contact, $eb_type, $eb_venue, $eb_date, $eb_guest, $eb_order) {
        try {
            $con = $this->opencon();
            $con->beginTransaction();
            $query = $con->prepare("INSERT INTO eventbookings (user_id, eb_name, eb_contact, eb_type, eb_venue, eb_date, eb_guest, eb_order, eb_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");
            $query->execute([$user_id, $eb_name, $eb_contact, $eb_type, $eb_venue, $eb_date, $eb_guest, $eb_order]);
            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollBack();
            error_log("Event Booking Error: " . $e->getMessage());
            return false;
        }
    }
 
    function viewEventBookings() {
        $con = $this->opencon();
        $sql = "SELECT eb.*, u.user_username
                FROM eventbookings eb
                LEFT JOIN users u ON eb.user_id = u.user_id
                ORDER BY eb.eb_id DESC";
        return $con->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
 
function getEventBookingById($eb_id) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT eb.*, u.user_username FROM eventbookings eb LEFT JOIN users u ON eb.user_id = u.user_id WHERE eb.eb_id = ?");
    $stmt->execute([$eb_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
 
function updateEventBookings($eb_id, $eb_status) {
    try {
        $con = $this->opencon();
        $stmt = $con->prepare("UPDATE eventbookings SET eb_status = ? WHERE eb_id = ?");
        return $stmt->execute([$eb_status, $eb_id]);
    } catch (PDOException $e) {
        return false;
    }
}
 
// catering
    function bookCateringPackage($userId, $fullName, $venueName, $venueStreet, $venueCity, $venueProvince, $phone, $eventDate, $package, $note) {
        $cp_place = $venueName . ', ' . $venueStreet . ', ' . $venueCity . ', ' . $venueProvince;
        $priceMap = [
            "50 PAX" => 35000,
            "100 PAX" => 55000,
            "150 PAX" => 78000,
            "200 PAX" => 99000
        ];
        $cp_price = $priceMap[$package] ?? 0;
        try {
            $con = $this->opencon();
            $stmt = $con->prepare("INSERT INTO cateringpackages (user_id, cp_name, cp_phone, cp_place, cp_date, cp_desc, cp_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $fullName, $phone, $cp_place, $eventDate, $note, $cp_price]);
            return $con->lastInsertId();
        } catch (PDOException $e) {
            error_log("Catering Booking Error: " . $e->getMessage());
            return false;
        }
    }
 
    function viewCateringBookings() {
    $con = $this->opencon();
    $sql = "SELECT cp.*, u.user_username
            FROM cateringpackages cp
            LEFT JOIN users u ON cp.user_id = u.user_id
            ORDER BY cp.cp_id DESC";
    return $con->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
 
function getCateringPackage($cp_id) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM cateringpackages WHERE cp_id = ?");
        $stmt->execute([$cp_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
 
// payment
  function addCateringPayment($cp_id, $user_id, $pay_amount, $pay_method) {
        try {
            $con = $this->opencon();
            $pay_date = date('Y-m-d');
            // Get package price
            $stmt = $con->prepare("SELECT cp_price FROM cateringpackages WHERE cp_id = ?");
            $stmt->execute([$cp_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $cp_price = $row ? floatval($row['cp_price']) : 0;
 
            // Determine payment status for varchar ('Fully Paid', 'Partial')
            if ($pay_amount >= $cp_price) {
                $pay_status = 'Fully Paid';
            } elseif ($pay_amount >= ($cp_price / 2)) {
                $pay_status = 'Partial';
            } else {
                $pay_status = 'Pending';
            }
 
            // Only allow 'Online', 'Credit', or 'Cash'
            if (!in_array($pay_method, ['Online', 'Credit', 'Cash'])) {
                $pay_method = 'Cash';
            }
 
            $query = $con->prepare("INSERT INTO payment (cp_id, user_id, pay_date, pay_amount, pay_method, pay_status) VALUES (?, ?, ?, ?, ?, ?)");
            $query->execute([$cp_id, $user_id, $pay_date, $pay_amount, $pay_method, $pay_status]);
            return true;
        } catch (PDOException $e) {
            error_log("Payment Insert Error: " . $e->getMessage());
            return false;
        }
    }
 
 
    // Get payment summary for a catering package
function getCateringPaymentSummary($cp_id) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT SUM(pay_amount) as total_paid FROM payment WHERE cp_id = ?");
    $stmt->execute([$cp_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // Get package price
    $stmt2 = $con->prepare("SELECT cp_price FROM cateringpackages WHERE cp_id = ?");
    $stmt2->execute([$cp_id]);
    $pkg = $stmt2->fetch(PDO::FETCH_ASSOC);
    $cp_price = $pkg ? floatval($pkg['cp_price']) : 0;
 
    $total_paid = $row ? floatval($row['total_paid']) : 0;
    if ($total_paid >= $cp_price) {
        $status = 'Fully Paid';
    } elseif ($total_paid >= ($cp_price / 2)) {
        $status = 'Partial';
    } else {
        $status = 'Pending';
    }
    return [
        'total_paid' => $total_paid,
        'status' => $status
    ];
}

// Get all categories
// Get all menu items in a category
function getFoodsByCategory($category_id) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT m.menu_id, m.menu_name FROM menu m
        INNER JOIN menucategory mc ON m.menu_id = mc.menu_id
        WHERE mc.category_id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 
// Get all menu items (for searching)
function getAllMenuItems() {
    $con = $this->opencon();
    $stmt = $con->query("SELECT menu_id, menu_name FROM menu");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 
// Update foods in a category (clear and insert new)
function updateCategoryFoods($category_id, $food_ids) {
    $con = $this->opencon();
    $con->beginTransaction();
    // Remove all current foods
    $con->prepare("DELETE FROM menucategory WHERE category_id = ?")->execute([$category_id]);
    // Insert new foods
    if (!empty($food_ids)) {
        $stmt = $con->prepare("INSERT INTO menucategory (menu_id, category_id) VALUES (?, ?)");
        foreach ($food_ids as $menu_id) {
            $stmt->execute([$menu_id, $category_id]);
        }
    }
    $con->commit();
    return true;
}
 
// sorting admin
// filter and sort menu items
function getFilteredMenuOOP($category_id = null, $pax = null, $avail = null, $sort = null) {
    $con = $this->opencon();
    $params = [];
    $join = '';
    $where = ['m.is_deleted = 0'];
    if ($category_id) {
        $join .= " INNER JOIN menucategory mc ON m.menu_id = mc.menu_id ";
        $where[] = "mc.category_id = ?";
        $params[] = $category_id;
    }
 
    // Pax filter logic
    if ($pax == '6-8') {
        $where[] = "(m.menu_pax BETWEEN 6 AND 8)";
    } elseif ($pax == '10-15') {
        $where[] = "(m.menu_pax BETWEEN 10 AND 15)";
    } elseif ($pax == '20-30') {
        $where[] = "(m.menu_pax BETWEEN 20 AND 30)";
    } elseif ($pax == '50-100') {
        $where[] = "(m.menu_pax BETWEEN 50 AND 100)";
    } elseif ($pax == 'piece') {
        $where[] = "(LOWER(m.menu_pax) LIKE '%pc%' OR LOWER(m.menu_pax) LIKE '%piece%')";
    } elseif ($pax !== null && $pax !== '') {
        $where[] = "m.menu_pax = ?";
        $params[] = $pax;
    }
 
    if ($avail !== null && $avail !== '') {
        $where[] = "m.menu_avail = ?";
        $params[] = $avail;
    }
 
    $whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";
 
    $sortSql = "ORDER BY m.menu_id DESC";
    if ($sort) {
        switch ($sort) {
            case 'price_asc': $sortSql = "ORDER BY m.menu_price ASC"; break;
            case 'price_desc': $sortSql = "ORDER BY m.menu_price DESC"; break;
            case 'alpha_asc': $sortSql = "ORDER BY m.menu_name ASC"; break;
            case 'alpha_desc': $sortSql = "ORDER BY m.menu_name DESC"; break;
            case 'pax_asc': $sortSql = "ORDER BY m.menu_pax ASC"; break;
            case 'pax_desc': $sortSql = "ORDER BY m.menu_pax DESC"; break;
        }
    }
 
    $sql = "SELECT m.* FROM menu m $join $whereSql $sortSql";
    $stmt = $con->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function isDateBooked($date) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) FROM eventbookings WHERE eb_date = ?");
    $stmt->execute([$date]);
    return $stmt->fetchColumn() > 0;
}

function isCateringDateBooked($date) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) FROM cateringpackages WHERE cp_date = ?");
    $stmt->execute([$date]);
    return $stmt->fetchColumn() > 0;
}

function updateUserPhoto($user_id, $filename) {
    $con = $this->opencon();
    $stmt = $con->prepare("UPDATE users SET user_photo = ? WHERE user_id = ?");
    return $stmt->execute([$filename, $user_id]);
}

}
?>
