<?hh //strict

use steelbrain\MySQL;

class App {
  public static ?App $Instance = null;

  public static function getInstance(): App {
    if (static::$Instance !== null) {
      return static::$Instance;
    }
    throw new Exception('App is not yet initialized');
  }

  // Props
  public array<string, string> $Get;
  public array<string, string> $Post;
  public array<string, string> $Server;
  public array<string, string> $Cookie;
  public int $HTTPCode = 200;
  public string $URL;
  public array<string> $URLChunks;
  public int $UserID = 0;

  // Instances
  private ?User $User;
  private ?MySQL $DB;
  private Session $Session;
  private TRouterWeb $Router;
  private TRouterAPI $RouterAPI;
  public function __construct(array<string, string> $Get, array<string, string> $Post, array<string, string> $Server, array<string, string> $Cookie) {
    $this->Session = new Session();
    $this->Router = new Router();
    $this->RouterAPI = new Router();
    $this->URL = array_key_exists('REQUEST_URI', $Server) ? explode('?', $Server['REQUEST_URI'])[0] : '';
    $this->URLChunks = Helper::uriToChunks($this->URL);
    $this->Get = array_map(class_meth('Helper', 'trim'), $Get);
    $this->Post = array_map(class_meth('Helper', 'trim'), $Post);
    $this->Server = array_map(class_meth('Helper', 'trim'), $Server);
    $this->Cookie = array_map(class_meth('Helper', 'trim'), $Cookie);
  }
  public function setRoutes():void {
    // Website
    $this->Router->get([], Theme_Guest_Home::class, true);

    // API
    $this->RouterAPI->post(['api', 'account'], class_meth('Theme_Guest_API', 'Login'));
  }
  public function execute(HTTP $method): string {
    if (APP_ENV === AppEnv::API) {
      $Callback = $this->RouterAPI->execute($method, $this->URL, $this->URLChunks);
      return Helper::apiEncode($Callback());
    } else {
      $Callback = $this->Router->execute($method, $this->URL, $this->URLChunks);
      return '<!doctype html>'. ((string) $Callback::Render());
    }
  }
  // Getters
  public function getSession(): Session {
    if ($this->Session !== null) {
      return $this->Session;
    }
    return $this->Session = new Session();
  }
  public function getDB(): steelbrain\MySQL {
    if ($this->DB !== null) {
      return $this->DB;
    }
    try {
      return $this->DB = MySQL::create(shape(
        'Host' => 'localhost',
        'User' => CONFIG_DB_USER,
        'Pass' => CONFIG_DB_PASS,
        'Name' => CONFIG_DB_NAME
      ));
    } catch (Exception $e) {
      error_log($e->getTraceAsString());
      throw new HTTPException(500);
    }
  }
  public function getUser(): User {
    $session = $this->getSession();
    if ($session->exists('UserID')) {
      $id = $session->get('UserID', 0);
      $user = $this->getDB()->from('users')->select('id')->where(['id' => $id])->get();
      if ($user !== null) {
        return $this->User = $user;
      } else {
        $session->unset('UserID');
      }
    }
    throw new Exception('User is not logged in');
  }
}
