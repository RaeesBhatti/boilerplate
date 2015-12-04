<?hh //strict

class App {
  public static array<string, string> $Get = [];
  public static array<string, string> $Post = [];
  public static array<string, string> $Server = [];
  public static array<string, string> $Cookie = [];
  public static int $HTTPCode = 200;
  public static string $URL = '';
  public static array<string> $URLChunks = [];
  public static ?PDO $DB;
  public static ?Router<classname<Page>> $Router;
  public static ?Router<(function():array<string, string>)> $RouterAPI;

  public static function query(string $statement, array<string, mixed> $parameters = []): PDOStatement {
    $DB = static::$DB;
    if ($DB === null) {
      throw new Exception('Database connection not established');
    }
    $query = $DB->prepare($statement);
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
    static::$DB = new PDO('mysql:host=localhost;charset=utf8mb4;dbname=' . CONFIG_DB_NAME, CONFIG_DB_USER, CONFIG_DB_PASS);
    static::$Router = new Router();
    static::$RouterAPI = new Router();
  }
  public function setRoutes():void {
    $Router = static::$Router;
    $RouterAPI = static::$RouterAPI;

    invariant($Router !== null, 'Router is null');
    invariant($RouterAPI !== null, 'RouterAPI is null');

    $Router->get([], Theme_Guest_Home::class, true);

    $RouterAPI->post(['api', 'account'], class_meth('Theme_Guest_API', 'Login'));
  }
  public function execute(HTTP $method): string {
    if (APP_ENV === AppEnv::API) {
      $Router = static::$RouterAPI;
      invariant($Router !== null, 'Router is null');
      $Callback = $Router->execute($method, static::$URL, static::$URLChunks);
      return Helper::apiEncode($Callback());
    } else {
      $Router = static::$Router;
      invariant($Router !== null, 'Router is null');
      $ClassName = $Router->execute($method, static::$URL, static::$URLChunks);
      $Content = $ClassName::Render();
      return '<!doctype html>'.$Content;
    }
  }
}
