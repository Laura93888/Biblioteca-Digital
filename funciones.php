<?php
class db
{
    public $pdo;

    public function __construct($host, $port, $db, $user, $pass)
    {
        $this->pdo = new PDO(
            "mysql:host=".$host.";port=".$port.";dbname=".$db.";charset=utf8",
            $user,
            $pass
        );
    }

function catlibros($categoria, $libros)
{
    $arraylibros = [];

    if ($categoria == "") {
        $arraylibros = $libros;
    } else {
        foreach ($libros as $libro) {
            if ($libro["categoria"] == $categoria) {
                $arraylibros[] = $libro;
            }
        }
    }

    return $arraylibros;
}


function cmpnombreasc($a, $b)
{
    if ($a["titulo"] == $b["titulo"]) {
        return 0;
    }

    if ($a["titulo"] < $b["titulo"]) {
        return -1;
    }

    return 1;
}


function cmpnombredesc($a, $b)
{
    return cmpnombreasc($b, $a);
}


function obtenerLibros(): array
{

    $sql = "
        SELECT
            l.id,
            l.titulo,
            l.autor,
            l.descripcion,
            l.anio,
            l.imagen,
            c.nombre AS categoria
        FROM libros l
        INNER JOIN categorias c ON c.id = l.id_categoria
        ORDER BY l.id ASC
    ";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->fetchAll();
}


function obtenerLibroPorId(int $id): ?array
{

    $sql = "
        SELECT
            l.id,
            l.titulo,
            l.autor,
            l.descripcion,
            l.anio,
            l.imagen,
            c.nombre AS categoria
        FROM libros l
        INNER JOIN categorias c ON c.id = l.id_categoria
        WHERE l.id = :id
        LIMIT 1
    ";

        $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $libro = $stmt->fetch();

    return $libro ?: null;
}

function crearUsuario($nombre, $email, $contraseñaHash)
{

    $sql = "
        INSERT INTO usuarios (nombre, email, contrasena)
        VALUES (:nombre, :email, :contrasena)
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ":nombre" => $nombre,
        ":email" => $email,
        ":contrasena" => $contraseñaHash
    ]);
}

function buscarusuario($email){ // Buscamos al usuario por email
            
            $sql = "
                SELECT id, nombre, email, contrasena, rol
                FROM usuarios
                WHERE email = :email
                LIMIT 1
            ";

                                $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ":email" => $email
            ]);

            $usuario = $stmt->fetch();

            return $usuario ?: null;
}

function cargarprestamos($idusuario)
{

    $sql = "
        SELECT
            prestamos.id,
            libros.titulo,
            libros.autor,
            prestamos.fecha_prestamo,
            prestamos.fecha_devolucion,
            prestamos.fuera_de_plazo,
            prestamos.estado
        FROM prestamos
        INNER JOIN libros
            ON prestamos.id_libro = libros.id
        WHERE prestamos.id_usuario = ?
        ORDER BY prestamos.fecha_prestamo DESC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$idusuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function comprobarusuario($email){
   
            // Comprobar si ya existe un usuario
            $sql = "
                SELECT id
                FROM usuarios
                WHERE email = :email
                LIMIT 1
            ";

    $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ":email" => $email
            ]);

           return $stmt->fetch(PDO::FETCH_ASSOC);
}

    function emailExisteOtroUsuario(string $email, int $idUsuario): bool
    {
        $sql = "
            SELECT id
            FROM usuarios
            WHERE email = :email
            AND id != :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":email" => $email,
            ":id" => $idUsuario
        ]);

        return $stmt->fetch() !== false;
    }


    function actualizarDatosUsuario(
        int $idUsuario,
        string $nombre,
        string $email
    ): bool
    {
        $sql = "
            UPDATE usuarios
            SET nombre = :nombre,
                email = :email
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":nombre" => $nombre,
            ":email" => $email,
            ":id" => $idUsuario
        ]);
    }


    function obtenerContrasenaUsuario(int $idUsuario): ?string
    {
        $sql = "
            SELECT contrasena
            FROM usuarios
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $idUsuario
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            return null;
        }

        return $usuario["contrasena"];
    }


    function actualizarContrasenaUsuario(
        int $idUsuario,
        string $nuevaContrasena
    ): bool
    {
        $nuevaContrasenaHash = password_hash(
            $nuevaContrasena,
            PASSWORD_DEFAULT
        );

        $sql = "
            UPDATE usuarios
            SET contrasena = :contrasena
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":contrasena" => $nuevaContrasenaHash,
            ":id" => $idUsuario
        ]);
    }


function comprobarDisponibilidadLibro($id_libro)
{
 
    $sql = "
        SELECT COUNT(*)
        FROM prestamos
        WHERE id_libro = :id_libro
        AND estado = 'activo'
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ":id_libro" => $id_libro
    ]);

    $cantidad = $stmt->fetchColumn();

    if ($cantidad > 0) {
        return false;
    } else {
        return true;
    }
}

function crearPrestamo(int $idUsuario, int $idLibro): bool
{

    // Comprobamos que el libro siga disponible
    if (!comprobarDisponibilidadLibro($idLibro)) {
        return false;
    }

    $sql = "
        INSERT INTO prestamos (
            id_usuario,
            id_libro,
            fecha_prestamo,
            fecha_devolucion,
            estado
        )
        VALUES (
            :id_usuario,
            :id_libro,
            CURDATE(),
            DATE_ADD(CURDATE(), INTERVAL 15 DAY),
            'activo'
        )
    ";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ":id_usuario" => $idUsuario,
        ":id_libro" => $idLibro
    ]);
}

function obtenerPrestamosActivos(): array
{

    $sql = "
        SELECT
            prestamos.id,
            prestamos.id_usuario,
            prestamos.id_libro,
            prestamos.fecha_prestamo,
            prestamos.fecha_devolucion,
            usuarios.nombre AS usuario,
            libros.titulo,
            libros.autor,
            libros.imagen
        FROM prestamos
        INNER JOIN usuarios
            ON prestamos.id_usuario = usuarios.id
        INNER JOIN libros
            ON prestamos.id_libro = libros.id
        WHERE prestamos.estado = 'activo'
        ORDER BY prestamos.fecha_prestamo DESC
    ";

     $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function devolverPrestamo(int $idPrestamo): bool
{
 
    $sql = "
        UPDATE prestamos
        SET
            estado = 'devuelto',
            fuera_de_plazo = IF(CURDATE() > fecha_devolucion, 1, 0)
        WHERE id = :id
        AND estado = 'activo'
    ";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ":id" => $idPrestamo
    ]);
}
}

?>