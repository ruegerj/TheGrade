<?
    class RememberMeToken
    {
        function __construct($id, $creation, $token, $privateKey, $userId)
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
        public $PrivateKey;
        public $UserId;
    }
?>