<?php

require_once __DIR__ . '../../utils/db_connect.php';

class ProductController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Hàm để lấy kiểu dữ liệu của cột 
    public function getColumnTypes($table, $column)
    {
        $stmt = $this->conn->prepare("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND COLUMN_NAME = ?");
        $stmt->bind_param("ss", $table, $column);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['DATA_TYPE'];
    }

    // Hàm để lấy toàn bộ sản phẩm
    public function getAllProducts()
    {
        $products = $this->conn->query("SELECT * FROM products");

        return $products->fetch_all(MYSQLI_ASSOC);
    }

    // Hàm để lấy sản phẩm theo trang
    public function getProductsByPage($offset, $limit, $columns, $query, $sort = 'id', $order = 'ASC')
    {
        // Xử lý query
        $query = '%' . $this->conn->real_escape_string($query) . '%';

        // Lọc các cột rỗng
        $columns = array_filter($columns, fn($col) => !empty($col));

        // Đảm bảo các cột là hợp lệ (chứa các tên cột)
        $columns = array_map(function ($col) {
            return "`" . str_replace("`", "``", $col) . "`"; // Tránh SQL Injection
        }, $columns);
        $columnsList = implode(', ', $columns); // Chuyển thành chuỗi cột

        // Xây dựng điều kiện WHERE
        $searchableColumns  = [
            'title',
            'description',
            'price',
            'discount'
        ];
        $whereClause = implode(' OR ', array_map(fn($col) => "$col LIKE ?", $searchableColumns));

        // Xây dựng câu lệnh SQL
        $sql = "SELECT $columnsList 
            FROM products
            WHERE $whereClause
            ORDER BY $sort $order 
            LIMIT ?, ?
        ";

        // Chuẩn bị và gán tham số
        $stmt = $this->conn->prepare($sql);

        // Tạo mảng các tham số
        $types = str_repeat('s', count($searchableColumns)) . 'ii';
        $params = array_merge(array_fill(0, count($searchableColumns), $query), [$offset, $limit]);

        // Gọi hàm bind_param với các tham số động
        $stmt->bind_param($types, ...$params);

        // Thực thi câu lệnh
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Hàm để lấy sản phẩm theo ID
    public function getProductById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm lấy các thông tin khác của sản phẩm theo ID
    public function getProductDetailsById($id, $tables)
    {
        $results = [];
        foreach ($tables as $table) {
            $sql = "SELECT * FROM $table WHERE product_id = ?";

            if ($table === 'product_platforms') {
                $sql = "SELECT *
                FROM platforms
                JOIN product_platforms ON platforms.id = product_platforms.platform_id
                WHERE product_platforms.product_id = ?";
            }

            if ($table === 'product_genres') {
                $sql = "SELECT *
                FROM genres
                JOIN product_genres ON genres.id = product_genres.genre_id
                WHERE product_genres.product_id = ?";
            }

            if ($table === 'product_tags') {
                $sql = "SELECT * 
                FROM product_tags
                JOIN tags ON product_tags.tag_id = tags.id
                WHERE product_id = ?";
            }

            if ($table === 'product_features') {
                $sql = "SELECT * 
                FROM product_features
                JOIN features ON product_features.feature_id = features.id
                WHERE product_id = ?";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            $results[$table] = $result;
        }

        return $results;
    }

    // Hàm lấy kiểu của sản phẩm
    public function getTypes()
    {
        $sql = "SELECT * FROM types";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Hàm lấy kiểu của sản phẩm theo ID
    public function getTypeById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM types WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm lấy tất cả các thể loại
    public function getGenres()
    {
        $sql = "SELECT * FROM genres";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Hàm lấy thể loại theo ID
    public function getGenreById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM genres WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm lấy tất cả platform
    public function getPlatforms()
    {
        $sql = "SELECT * FROM platforms";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Hàm lấy platform theo ID
    public function getPlatformById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM platforms WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm lấy tất cả tags
    public function getTags()
    {
        $sql = "SELECT t.id, name, COUNT(*) as count
        FROM tags t
        JOIN product_tags pt ON t.id = pt.tag_id
        GROUP BY name";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Hàm lấy tag theo ID
    public function getTagById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM tags WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm lấy tất cả features
    public function getFeatures()
    {
        $sql = "SELECT f.id, name, COUNT(*) as count
        FROM features f
        JOIN product_features pf ON f.id = pf.feature_id
        GROUP BY name";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Hàm lấy feature theo ID
    public function getFeatureById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM features WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm lấy tất cả ngôn ngữ
    public function getLanguages()
    {
        $sql = "SELECT l.id, name, COUNT(*) as count
        FROM languages l
        JOIN product_languages pl ON l.id = pl.language_id
        GROUP BY name";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Hàm lấy tổng số sản phẩm
    public function getTotalProducts($query)
    {
        $query = '%' . $query . '%';

        // Xây dựng điều kiện WHERE (tìm kiếm trên nhiều cột)
        $searchableColumns = ['title', 'description', 'price', 'discount'];
        $whereClause = implode(' OR ', array_map(fn($col) => "$col LIKE ?", $searchableColumns));

        // Câu lệnh SQL
        $countSql = "SELECT COUNT(*) as total FROM products WHERE $whereClause";

        $stmt = $this->conn->prepare($countSql);
        $stmt->bind_param(str_repeat('s', count($searchableColumns)), ...array_fill(0, count($searchableColumns), $query));
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    // Hàm để insert sản phẩm
    public function addProduct(
        $title,
        $type,
        $description,
        $detail,
        $price,
        $discount,
        $discount_start_date = null,
        $discount_end_date = null,
        $release_date,
        $header_image,
        $is_active
    ) {
        $stmt = $this->conn->prepare("INSERT INTO products (
            title,
            type_id,
            description,
            detail,
            price,
            discount,
            discountStartDate,
            discountEndDate,
            releaseDate,
            headerImage,
            isActive
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sisssddsssi",
            $title,           // String
            $type,            // Integer (type)
            $description,     // String
            $detail,          // String
            $price,           // Float
            $discount,        // Float
            $discount_start_date,  // String
            $discount_end_date,    // String
            $release_date,    // String
            $header_image,    // String
            $is_active        // Integer (active status)
        );

        // Thực thi câu lệnh
        $result = $stmt->execute();

        // Trả về ID của sản phẩm vừa thêm
        if ($result) {
            $product_id = $this->conn->insert_id;
            return $product_id;
        } else {
            return false;
        }
    }

    // Hàm để insert thông tin khác của sản phẩm
    public function addProductDetails($tables, $product_id, $data)
    {
        // Kiểm tra dữ liệu của bảng có phải là mảng không
        if (!is_array($tables)) {
            throw new Exception("Tables must be an array.");
        }

        // Duyệt qua từng bảng
        foreach ($tables as $table) {
            // Kiểm tra dữ liệu của bảng có phải là mảng không
            if (!isset($data[$table]) || !is_array($data[$table])) {
                throw new Exception("Data for $table must be an array.");
            }

            // Lấy tên cột và giá trị của bảng
            $columns = array_keys($data[$table]);
            // Tạo chuỗi placeholders
            $placeholders = implode(", ", array_fill(0, count($columns) + 1, "?"));
            // Tạo chuỗi tên cột
            $columnNames = implode(", ", array_merge(['product_id'], $columns));

            // Tạo câu lệnh SQL
            $sql = "INSERT INTO $table ($columnNames) VALUES ($placeholders)";
            // Chuẩn bị câu lệnh SQL
            $stmt = $this->conn->prepare($sql);

            // Kiểm tra câu lệnh SQL
            if (!$stmt) {
                throw new Exception("Prepare failed for table $table: " . $this->conn->error);
            }

            // Duyệt qua từng cột của bảng
            foreach ($data[$table][$columns[0]] as $index => $value) {
                $bindValues = [$product_id];
                $types = "i"; // Kiểu dữ liệu cho product_id là integer

                // Duyệt qua từng cột của bảng
                foreach ($columns as $column) {
                    // Lấy kiểu dữ liệu của cột
                    $columnType = $this->getColumnTypes($table, $column);
                    // Thêm giá trị vào mảng bindValues
                    $bindValues[] = $data[$table][$column][$index] ?? null;

                    // Xác định kiểu dữ liệu của cột
                    switch ($columnType) {
                        case 'int':
                            $types .= "i";
                            break;
                        case 'float':
                            $types .= "d";
                            break;
                        default:
                            $types .= "s";
                            break;
                    }
                }

                // Gán tham số cho câu lệnh SQL
                $stmt->bind_param($types, ...$bindValues);
                
                // Thực thi câu lệnh SQL
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed for table $table: " . $stmt->error);
                }
            }
        }

        return true;
    }


    // Hàm để update sản phẩm
    public function updateProduct(
        $id,
        $name,
        $price,
        $quantity,
        $description,
        $image
    ) {
        $stmt = $this->conn->prepare("UPDATE products SET
            name = :name,
            price = :price,
            quantity = :quantity,
            description = :description,
            image = :image
            WHERE id = :id
        ");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);

        $stmt->execute();
    }

    // Hàm để xóa sản phẩm
    public function deleteProduct($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Hàm để tìm kiếm sản phẩm 
    public function searchProduct($keyword)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE name LIKE ?");
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    // Hàm để lấy sản phẩm theo thể loại
    public function getProductByGenre($genre_id, $offset, $limit)
    {
        $sql = "SELECT * 
        FROM products p JOIN product_genres pg ON p.id = pg.product_id
        WHERE genre_id = ?
        LIMIT ?, ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $genre_id, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
}
