<?php
class User
{
    private $conn;
    private $table_name = "users";

    public $userId;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $created;
    public $modified;
    public $rolesId;

    //constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
        SET
            firstName = :firstName,
            lastName = :lastName,
            email = :email,
            password = :password,
            rolesId = 1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->firstName = htmlspecialchars(strip_tags($this->firstName));
        $this->lastName = htmlspecialchars(strip_tags($this->lastName));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));


        // bind the values
        $stmt->bindParam(':firstName', $this->firstName);
        $stmt->bindParam(':lastName', $this->lastName);
        $stmt->bindParam(':email', $this->email);


        //hash password, before saving
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        // execute the query, also check if query was successful
        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }

    function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . ";";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        try {
            // execute the query
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }

    function getById()
    {
        $query = "SELECT * 
            FROM 
                " . $this->table_name . " 
            WHERE 
                userId= ?
            LIMIT
                0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->userId = htmlspecialchars(strip_tags($this->userId));

        // bind the values
        $stmt->bindParam(1, $this->userId);

        // execute the query
        try {
            $stmt->execute();

            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        // set values to object properties
        $this->firstName = $row['firstName'];
        $this->lastName = $row['lastName'];
        $this->email = $row['email'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
        $this->rolesId = $row['rolesId'];
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . "
        SET
            rolesId = :rolesId,
            firstName = :firstName,
            lastName = :lastName,
            email = :email
        WHERE userId = :userId";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        //sanitize
        //$this->userId = htmlspecialchars(strip_tags($this->userId));
        $this->rolesId = htmlspecialchars(strip_tags($this->rolesId));
        $this->firstName = htmlspecialchars(strip_tags($this->firstName));
        $this->lastName = htmlspecialchars(strip_tags($this->lastName));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind the values
        $stmt->bindParam(':userId', $this->userId);
        $stmt->bindParam(':rolesId', $this->rolesId);
        $stmt->bindParam(':firstName', $this->firstName);
        $stmt->bindParam(':lastName', $this->lastName);
        $stmt->bindParam(':email', $this->email);

        // execute the query, also check if query was successful
        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }

    // check if given email exist in the database
    function emailExists()
    {
        // query to check if email exists
        $query = "SELECT userId, firstName, lastName, email, password, rolesId
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute the query
        try {
            $stmt->execute();

            // get number of rows
            $num = $stmt->rowCount();

            // if email exists, assign values to object properties for easy access and use for php sessions
            if ($num > 0) {

                // get record details / values
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // assign values to object properties
                $this->userId = $row['userId'];
                $this->firstName = $row['firstName'];
                $this->lastName = $row['lastName'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->rolesId = $row['rolesId'];

                /* session_name("konsulent_huset");
                session_start();
                $_SESSION["userId"] = $this->userId;
                $_SESSION["firstName"] = $this->firstName;
                $_SESSION["lastName"] = $this->lastName;
                $_SESSION["email"] = $this->email;
                $_SESSION["rolesId"] = $this->rolesId; */
                // return true because email exists in the database
                return true;
            }

            // return false if email does not exist in the database
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
        return false;
    }

    function delete()
    {
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE userId = ?";
        print_r($query);
        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        //$this->productId = htmlspecialchars(strip_tags($this->productId));

        // bind id of record to delete
        $stmt->bindParam(1, $this->userId);

        // execute query
        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }
}