<?php
class database {
    private PDO $pdo;

    public function __construct(
        string $host = 'localhost',
        string $db = 'sandokdb',
        string $user = 'root',
        string $pass = '',
        string $charset = 'utf8mb4'
    ) {
        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->pdo = new PDO($dsn, $user, $pass, $opts);
    }

    // Expose raw PDO when needed by legacy code
    public function opencon(): PDO { return $this->pdo; }

    /* ========== Categories ========== */
    public function viewCategories(): array {
        $sql = "SELECT category_id, category_name FROM category ORDER BY category_name ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /* ========== Menu lookup/update ========== */
    public function viewMenuID(int $menu_id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
        $stmt->execute([$menu_id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function setMenuAvailability(int $menu_id, int $avail): bool {
        $stmt = $this->pdo->prepare("UPDATE menu SET menu_avail = ? WHERE menu_id = ?");
        return $stmt->execute([$avail, $menu_id]);
    }

    public function archiveMenu(int $menu_id): bool {
        // Hard delete as schema doesn't have is_deleted
        $stmt = $this->pdo->prepare("DELETE FROM menu WHERE menu_id = ?");
        return $stmt->execute([$menu_id]);
    }

    public function updateMenu(
        int $menu_id,
        string $name,
        string $desc,
        string $pax,
        float $price,
        int $avail,
        ?string $pic
    ): bool {
        $fields = [
            'menu_name' => $name,
            'menu_desc' => $desc,
            'menu_pax'  => $pax,
            'menu_price'=> $price,
            'menu_avail'=> $avail,
        ];
        if ($pic !== null && $pic !== '') {
            $fields['menu_pic'] = $pic;
        }
        $sets = [];
        $vals = [];
        foreach ($fields as $k => $v) { $sets[] = "$k = ?"; $vals[] = $v; }
        $vals[] = $menu_id;
        $sql = "UPDATE menu SET " . implode(', ', $sets) . " WHERE menu_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($vals);
    }

    // Granular updaters (used if updateMenu signature isn't assumed)
    public function updateMenuName(int $id, string $v): bool { return $this->pdo->prepare("UPDATE menu SET menu_name=? WHERE menu_id=?")->execute([$v, $id]); }
    public function updateMenuDesc(int $id, string $v): bool { return $this->pdo->prepare("UPDATE menu SET menu_desc=? WHERE menu_id=?")->execute([$v, $id]); }
    public function updateMenuPax(int $id, string $v): bool { return $this->pdo->prepare("UPDATE menu SET menu_pax=? WHERE menu_id=?")->execute([$v, $id]); }
    public function updateMenuPrice(int $id, float $v): bool { return $this->pdo->prepare("UPDATE menu SET menu_price=? WHERE menu_id=?")->execute([$v, $id]); }
    public function updateMenuPic(int $id, string $v): bool { return $this->pdo->prepare("UPDATE menu SET menu_pic=? WHERE menu_id=?")->execute([$v, $id]); }

    /* ========== Menu filtering helpers ========== */
    private function buildMenuWhere(?int $category, $avail, ?string $q, array &$params): string {
        $w = [];
        if ($category !== null) {
            $w[] = 'mc.category_id = ?';
            $params[] = $category;
        }
        if ($avail !== null && $avail !== '') {
            $w[] = 'm.menu_avail = ?';
            $params[] = (int)$avail;
        }
        if ($q !== null && $q !== '') {
            $w[] = 'm.menu_name LIKE ?';
            $params[] = '%' . $q . '%';
        }
        return $w ? ('WHERE ' . implode(' AND ', $w)) : '';
    }

    private function buildOrderBy(?string $sort): string {
        switch ($sort) {
            case 'alpha_asc': return 'ORDER BY m.menu_name ASC';
            case 'alpha_desc': return 'ORDER BY m.menu_name DESC';
            case 'price_asc': return 'ORDER BY m.menu_price ASC';
            case 'price_desc': return 'ORDER BY m.menu_price DESC';
            default: return 'ORDER BY m.created_at DESC, m.menu_id DESC';
        }
    }

    public function countFilteredMenu(?int $category, $avail, ?string $q): int {
        $params = [];
        $where = $this->buildMenuWhere($category, $avail, $q, $params);
        $join = $category !== null ? 'JOIN menucategory mc ON mc.menu_id = m.menu_id' : '';
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS c FROM menu m {$join} {$where}");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getFilteredMenuPaged(?int $category, $avail, ?string $sort, int $limit, int $offset, ?string $q): array {
        $params = [];
        $where = $this->buildMenuWhere($category, $avail, $q, $params);
        $join = $category !== null ? 'JOIN menucategory mc ON mc.menu_id = m.menu_id' : '';
        $order = $this->buildOrderBy($sort);
        $sql = "SELECT m.* FROM menu m {$join} {$where} {$order} LIMIT ? OFFSET ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge($params, [$limit, $offset]));
        return $stmt->fetchAll();
    }

    public function addMenu(string $name, string $desc, string $pax, $price, string $pic, int $avail): bool {
        $sql = "INSERT INTO menu (menu_name, menu_desc, menu_pax, menu_price, menu_pic, menu_avail, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $desc, $pax, (float)$price, $pic, $avail]);
    }

    public function getFilteredMenuOOP(?int $category, $avail, ?string $sort): array {
        $params = [];
        $where = $this->buildMenuWhere($category, $avail, null, $params);
        $join = $category !== null ? 'JOIN menucategory mc ON mc.menu_id = m.menu_id' : '';
        $order = $this->buildOrderBy($sort);
        $sql = "SELECT m.* FROM menu m {$join} {$where} {$order}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /* ========== Orders/Payments for user/save_payment.php ========== */
    public function addOrder(int $user_id, string $order_date, string $order_status, float $order_amount, string $order_needed) {
        $sql = "INSERT INTO orders (user_id, order_date, order_status, order_amount, order_needed, created_at, updated_at, is_deleted)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW(), 0)";
        $stmt = $this->pdo->prepare($sql);
        $ok = $stmt->execute([$user_id, $order_date, $order_status, $order_amount, $order_needed]);
        return $ok ? (int)$this->pdo->lastInsertId() : 0;
    }

    public function savePayment($order_id, $cp_id, $user_id, $pay_date, $pay_amount, $pay_method, $pay_status) {
        try {
            $sql = "INSERT INTO payments (order_id, cp_id, user_id, pay_date, pay_amount, pay_method, pay_status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$order_id, $cp_id, $user_id, $pay_date, $pay_amount, $pay_method, $pay_status]);
            return true;
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}