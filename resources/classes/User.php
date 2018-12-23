<?
    class User
    {
        function __construct(int $id, string $name, string $prename, string $email, string $password)
        {
            $this->Id = $id;
            $this->Name = $name;
            $this->Prename = $prename;
            $this->Email = $email;
            $this->Password = $password;
        }        
        
        public $Id;
        public $Name;
        public $Prename;
        public $Email;
        public $Password;
    }
?>