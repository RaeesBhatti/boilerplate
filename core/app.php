<?hh //strict

class App {
  public static array<string, string> $Get = [];
  public static array<string, string> $Post = [];
  public static array<string, string> $Server = [];
  public static array<string, string> $Cookie = [];
  public static int $HTTPCode = 200;
  public static string $URL = '';
  public static array<string> $URLChunks = [];
  public static int $UserID = 0;
  public static User $User = shape(
    'id' => 0
  );
  public static ?Session $Session;
  public static ?PDO $DB;
  public static ?Router<classname<Page>> $Router;
  public static ?Router<(function():array<string, string>)> $RouterAPI;

  public static function getSession(): Session {
    if (static::$Session !== null) {
      return static::$Session;
    }
    return static::$Session = new Session();
  }
  public static function getDB(): PDO {
    if (static::$DB !== null) {
      return static::$DB;
    }
    try {
      return static::$DB = new PDO('mysql:host=localhost;charset=utf8mb4;dbname=' . CONFIG_DB_NAME, CONFIG_DB_USER, CONFIG_DB_PASS);
    } catch (Exception $e) {
      error_log($e->getTraceAsString());
      throw new HTTPException(500);
    }
  }
  public static function getRouter(): Router<classname<Page>> {
    if (static::$Router !== null) {
      return static::$Router;
    }
    return static::$Router = new Router();
  }
  public static function getRouterAPI(): Router<(function():array<string, string>)> {
    if (static::$RouterAPI !== null) {
      return static::$RouterAPI;
    }
    return static::$RouterAPI = new Router();
  }

  public static function query(string $statement, array<string, mixed> $parameters = []): PDOStatement {
    $query = static::getDB()->prepare($statement);
    $query->execute($parameters);
    return $query;
  }
  public static function go(string $method, string $URL, array<string, string> $Get, array<string, string> $Post, array<string, string> $Server, array<string, string> $Cookie): string {
    if (!HTTP::isValid($method)) {
      return '';
    }
    $App = new App();
    $App->initialize($URL, $Get, $Post, $Server, $Cookie);
    $App->setRoutes();
    try {
      try {
        $Content = $App->execute(HTTP::assert($method));
      } catch (APIException $e) {
        $Content = Helper::apiEncode($e);
      } catch (HTTPException $e) {
        throw $e;
      } catch (Exception $e) {
        error_log($e->getTraceAsString());
        throw new HTTPException(500);
      }
    } catch (HTTPException $e) {
      static::$HTTPCode = $e->httpCode;
      if (APP_ENV === AppEnv::API) {
        $Content = json_encode(['status' => false, 'message' => "HTTP Error $e->httpCode", 'type' => 'http']);
      } else {
        $Content = (string) Theme_Error::Render();
      }
    }
    http_response_code(static::$HTTPCode);
    return $Content;
  }

  public function initialize(string $URL, array<string, string> $Get, array<string, string> $Post, array<string, string> $Server, array<string, string> $Cookie): void {
    static::$URL = $URL;
    static::$URLChunks = Helper::uriToChunks($URL);
    static::$Get = array_map(class_meth('Helper', 'trim'), $Get);
    static::$Post = array_map(class_meth('Helper', 'trim'), $Post);
    static::$Server = array_map(class_meth('Helper', 'trim'), $Server);
    static::$Cookie = array_map(class_meth('Helper', 'trim'), $Cookie);
    static::$Router = new Router();
    static::$RouterAPI = new Router();
    if (static::getSession()->exists('UserID')) {
      $ID = static::getSession()->get('UserID', 0);
      $query = static::query('Select id from users where id = :id LIMIT 1', [':id' => $ID]);
      if ($query->rowCount()) {
        static::$User = $query->fetch(PDO::FETCH_ASSOC);
      } else {
        static::getSession()->unset('UserID');
      }
    }
  }
  public function setRoutes():void {
    $Router = static::getRouter();
    $RouterAPI = static::getRouterAPI();

    // Website
    $Router->get([], Theme_Guest_Home::class, true);

    // API
    $RouterAPI->post(['api', 'account'], class_meth('Theme_Guest_API', 'Login'));
  }
  public function execute(HTTP $method): string {
    if (APP_ENV === AppEnv::API) {
      $Router = static::getRouterAPI();
      $Callback = $Router->execute($method, static::$URL, static::$URLChunks);
      return Helper::apiEncode($Callback());
    } else {
      $Router = static::getRouter();
      $ClassName = $Router->execute($method, static::$URL, static::$URLChunks);
      $Content = $ClassName::Render();
      return '<!doctype html>'.$Content;
    }
  }
}
