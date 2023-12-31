<?php  
 
class UserManager extends AbstractManager {  
    
    
    
  public function getUserByEmail(string $email) : ?User
{
    $query = $this->db->prepare('SELECT * FROM users WHERE email=:email');
    $parameters = [
        'email' => $email
    ];
    $query->execute($parameters);
    $connect = $query->fetch(PDO::FETCH_ASSOC);
    if ($connect === false) {
        return null;
    } else {

        $user = new User(
            $connect["username"],
            $connect["email"],
            $connect["password"],
            $connect["role"]
       );
        $user->setId($connect["id"]);

        return $user;
    }
}

    
    
    
    
public function createUser(User $user) : User
{
    $query = $this->db->prepare('INSERT INTO users VALUES (:id, :username, :email, :password, :role)');
    $parameters = [
        'id' => $user->getId(), 
        'username' => $user->getUsername(),
        'email' => $user->getEmail(),
        'password' => $user->getPassword(),
        'role' => $user->getRole()
    ];
    $query->execute($parameters);

    $id = $this->db->lastInsertId();
    $user->setId($id);
    
        // var_dump ($user);
    // Pour accéder à la base de données, utilisez $this->db

    return $user;
}


    
    public function getUserById($userId)
{
    $query = $this->db->prepare("SELECT * FROM users WHERE id = :userId");
    $parameters = [
        "userId" => $userId
    ];
    
    $query->execute($parameters);
    $Data = $query->fetch(PDO::FETCH_ASSOC);
    $user = new User($Data['username'], $Data['email'],  $Data['password'],  $Data['role']);
    $user->setId($Data['id']);
    return $user;
}

    
   public function edit(User $user) : void
{
    $query = $this->db->prepare("UPDATE users SET username = :username, email = :email, password = :password, role = :role WHERE users.id = :id");
    $parameters = [
        "id" => $user->getId(),
        "username" => $user->getUsername(),
        "email" => $user->getEmail(),
        "password" => $user->getPassword(),
        "role" => $user->getRole(),
    ];
    $query->execute($parameters);
}

   

 public function getAllUsers(): array
    {
        $query = $this->db->prepare("SELECT * FROM users");
        $parameters = [];
        $query->execute($parameters);
        $users = $query->fetchAll(PDO::FETCH_ASSOC);

        $tab = [];
        foreach ($users as $user) {
            $new = new User(
                $user["username"],
                $user["email"],
                $user["password"],
                $user["role"]
            );
            $new->setId($user["id"]);

            array_push($tab, $new);
        }
        return $tab;
        var_dump($tab);
    }
    
    public function deleteUserById(string $id): void{

        $query= $this->db->prepare("DELETE FROM users WHERE id=:value");
        $parameters = [
        'value' => $id,
        ];
        $query->execute($parameters);


    }
  
}