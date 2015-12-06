<?hh //strict

use steelbrain\MySQL;

class AppMain {
  public static ?AppMain $Instance = null;

  public static function getInstance(): AppMain {
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
  private Router<classname<Page>> $Router;
  private Router<(function():array<string, string>)> $RouterAPI;
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
}
