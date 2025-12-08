<?php
require_once dirname(__FILE__, 1) . '/config/db.php';

class User
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Buscar usuario por nombre
    public function getByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = :username AND isActive = 1 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Validar usuario y contraseña (sin hash)
    public function verifyLogin($username, $password)
    {
        $user = $this->getByUsername($username);

        // Comparación simple (solo para desarrollo)
        if ($user && $password === $user['password']) {
            return $user;
        }
        return false;
    }

    // Obtener todos los usuarios
    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT id, username, role, isActive, createdAt FROM users ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener un usuario por ID
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT id, username, role, isActive, createdAt FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crear usuario
    public function create($data)
    {
        $sql = "INSERT INTO users (username, password, role, isActive) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        // $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([
            $data['username'],
            $data['password'],
            $data['role'],
            $data['isActive'] ?? 1
        ]);
    }

    // Actualizar usuario
    public function update($id, $data)
    {
        $sql = "UPDATE users SET username=?, role=?, isActive=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['username'],
            $data['role'],
            $data['isActive'],
            $id
        ]);
    }

    // Cambiar contraseña
    public function updatePassword($id, $newPassword)
    {
        $sql = "UPDATE users SET password=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $stmt->execute([$hashedPassword, $id]);
    }

    // Eliminar usuario
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Código SQLSTATE 23000 → error de integridad referencial
            if ($e->getCode() === '23000') {
                return 'constraint_error';
            } else {
                // Opcional: puedes loguearlo para debugging
                error_log("Error eliminando usuario: " . $e->getMessage());
                return false;
            }
        }
    }

    // Verificar credenciales (para login)
    public function verifyCredentials($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username=? AND isActive=1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function search($filters)
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        // Filtro por nombre
        if (!empty($filters['searchName'])) {
            $sql .= " AND username LIKE :username";
            $params[':username'] = "%" . $filters['searchName'] . "%";
        }

        // Filtro por rol
        if (!empty($filters['role'])) {
            $sql .= " AND role = :role";
            $params[':role'] = $filters['role'];
        }

        // Filtro por estado (activo / inactivo)
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $sql .= " AND isActive = 1";
            } elseif ($filters['status'] === 'inactive') {
                $sql .= " AND isActive = 0";
            }
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
