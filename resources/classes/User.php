<?
    class User
    {
        function __construct($id, $name, $prename, $email, $password)
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