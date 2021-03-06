<?
    /**
     * Represents a Remember-Me Token from the DB in the application
     */
    class RememberMeToken
    {
        function __construct(int $id, int $creation, string $token, string $privateKey, int $userId)
        {
            $this->Id = $id;            
            $this->Creation = $creation;            
            $this->Token = $token;
            $this->PrivateKey = $privateKey;            
            $this->UserId = $userId;
        }

        public $Id;
        public $Creation;
        public $Token;
        public $PrivateKey; //Cryptographic secret as a string
        public $UserId; //FK User
    }
?>