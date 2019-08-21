 <?php 
// Réplica da Produção AFD
define('HOST', '10.209.42.80');  
define('DBNAME', 'sei');  
define('CHARSET', 'utf8');  
define('USER', 'leitura');  
define('PASSWORD', 'leituraafd');  

// Produção SIAPE CADASTRO Servidor 131
define('HOST2', '10.209.9.131');  
define('DBNAME2', 'carga_afd');  
define('USER2', 'cgdms');  
define('PASSWORD2', 'senhacgdms');  

 class Conexao {  

	/*  
	* Atributo estático para instância do PDO  
	*/  
	private static $pdo;
	private static $pdo2;

	/*  
	* Escondendo o construtor da classe  
	*/ 
	private function __construct() {  
	 //  
	} 

	/*  
	* Método estático para retornar uma conexão válida  
	* Verifica se já existe uma instância da conexão, caso não, configura uma nova conexão  
	*/  
	public static function getInstance() {  
		if (!isset(self::$pdo)) {  
			try {  
				$opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', PDO::ATTR_PERSISTENT => TRUE);  
				self::$pdo = new PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . "; charset=" . CHARSET . ";", USER, PASSWORD, $opcoes); 
				// self::$pdo = new PDO("mysql:host=" . HOST . "; port=3307; dbname=" . DBNAME . "; charset=" . CHARSET . ";", USER, PASSWORD, $opcoes); 
			} catch (PDOException $e) {  
				print "Erro: " . $e->getMessage(); 
			}  
		}  
		return self::$pdo;  
	} 

	public static function getInstance2() {  
		if (!isset(self::$pdo)) {  
			try {  
				self::$pdo = new PDO("pgsql:host=".HOST2.";port=5432;dbname=".DBNAME2.";user=".USER2.";password=".PASSWORD2);
			} catch (PDOException $e) {  
				print "Erro: " . $e->getMessage();  
			}  
		}  
		return self::$pdo;  
	} 
 }