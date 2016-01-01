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
  public function __construct(array<string, string> $Get, array<string, string> $Post, array<string, string> $Server, array<string, string> $Cookie) {
    $this->Session = new Session();
    $this->URL = array_key_exists('REQUEST_URI', $Server) ? explode('?', $Server['REQUEST_URI'])[0] : '';
    $this->URLChunks = Helper::uriToChunks($this->URL);
    $this->Get = array_map(class_meth('Helper', 'trim'), $Get);
    $this->Post = array_map(class_meth('Helper', 'trim'), $Post);
    $this->Server = array_map(class_meth('Helper', 'trim'), $Server);
    $this->Cookie = array_map(class_meth('Helper', 'trim'), $Cookie);
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
      error_log($e->getMessage(). "\n" . $e->getTraceAsString());
      throw new HTTPException(500);
    }
  }
  public function getUser(): ?User {
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
    return null;
  }
  public function execute(HTTP $Method): string {
    $RouterTheme = new Router();
    $RouterTheme->registerTheme(Theme_Guest_Main::class);

    $RelativeURL = $this->URL;
    if (APP_ENV === AppEnv::API) {
      // Trim "/api" from the beginning
      $RelativeURL = substr($RelativeURL, 4);
    }

    $ThemeName = $RouterTheme->executeTheme($RelativeURL);
    $Theme = new $ThemeName();

    $PrefixURL = (string) substr($RelativeURL, 0, strlen($Theme::PREFIX));
    $ChoppedURL = substr($RelativeURL, strlen($Theme::PREFIX));
    if ($ChoppedURL === false) {
      if (substr($this->URL, 0 -1) !== '/') {
        throw new HTTPRedirectException($this->URL . '/');
      }
      $ChoppedURL = '/';
    }

    if (APP_ENV === AppEnv::API) {
      $Router = new Router();
      $Theme->registerAPI($Router);
      $Callback = $Router->execute($Method, $ChoppedURL, null, $PrefixURL);
      return Helper::apiEncode($Callback());
    } else {
      $Router = new Router();
      $Theme->registerWeb($Router);
      $PageName = $Router->execute($Method, $ChoppedURL, null, $PrefixURL);
      return (string) Helper::renderPage($PageName);
    }
  }
}
