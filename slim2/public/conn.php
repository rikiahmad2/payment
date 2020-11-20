<?PHP
	class dash{
		function __construct($host,$username,$password,$name){
			#$this->name =$name;
			$this->con = new mysqli($host,$username,$password,$name,3306) or die($this->con->error());
		}
		
		function __destruct(){
			$this->con->close();
		}

		function query($q){
			$this->result = $this->con->query($q);
			
			return $this->result;
		}
	}
	
?>