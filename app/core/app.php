<?hh //strict

class App {
  public static ?App $Instance = null;

  public static function getInstance(): App {
    if (static::$Instance !== null) {
      return static::$Instance;
    }
    throw new Exception('App is not yet initialized');
  }

  // Props
  public ImmMap<string, string> $Get;
  public ImmMap<string, string> $Post;
  public ImmMap<string, string> $Server;
  public ImmMap<string, string> $Cookie;
  public string $LinkHeader;
  public int $HTTPCode = 200;
  public string $URL;
  public array<string> $URLChunks;
  public int $UserID = 0;
  public string $Env;
  public bool $isH2;
	public Vector<Map<string, mixed>> $AcceptLanguage = Vector{};

  // Instances
  private ?User $User;
  private ?MongoDB\Database $DB;
  private Session $Session;
  private ?RedisNG $Redis;
  public function __construct(ImmMap<string, string> $Get, ImmMap<string, string> $Post, ImmMap<string, mixed> $Server, ImmMap<string, string> $Cookie) {
    $this->Session = new Session();
    $this->URL = $Server->contains('REQUEST_URI') ? explode('?', (string) $Server->get('REQUEST_URI'))[0] : '';
    $this->URLChunks = Helper::uriToChunks($this->URL);
		$this->Get = $Get->map(fun('trim'));
    $this->Post = $Post->map(fun('trim'));
    $this->Server = $Server->map(class_meth('Helper', 'trim'));
    $this->Cookie = $Cookie->map(fun('trim'));
    $this->LinkHeader = 'Link: ';
    $this->Env = $this->Server->contains('ENV') && $this->Server->get('ENV') === AppEnv::PRODUCTION ? AppEnv::PRODUCTION : AppEnv::DEVELOPMENT;
    $this->isH2 = $this->Server->contains('H2') && $this->Server->get('H2') !== '' ? true : false;
		if($this->Server->contains('HTTP_ACCEPT_LANGUAGE')){
			foreach(explode(',', substr((string) $this->Server->get('HTTP_ACCEPT_LANGUAGE'), 0, 34)) as $elem){
				$attributes = array_map(function($e){return trim($e);}, explode(';', substr($elem, 0, 15)));
				if(count($attributes) === 1){
					$this->AcceptLanguage->add(Map{'lang' => $attributes[0], 'quality' => 1.0});
				} else {
					$pair = Map{};
					$language = '';
					foreach($attributes as $attrib){
						$parsed = Map{};
						$key = null;
						foreach(explode('=', $attrib) as $entry) {
							if ($key !== null) {
								$parsed->set($key, $entry);
								$key = null;
							} else $key = $entry;
						}
						if(!$parsed->count()){
							$language = $attrib;
						} else {
							$pair->set($language, (float) $parsed->get('q'));
						}
					}
					if($pair->count()) $this->AcceptLanguage->add($pair);
				}
			}
		}
  }
  // Getters
  public function getSession(): Session {
    if ($this->Session !== null) {
      return $this->Session;
    }
    return $this->Session = new Session();
  }
  public function getDB(): MongoDB\Database {
    if ($this->DB !== null) {
      return $this->DB;
    }
    try {
      if(CONFIG_DB_USER !== ''){
        return $this->DB = (new MongoDB\Client('mongodb://'.CONFIG_DB_USER.':'.CONFIG_DB_PASS.'@'.CONFIG_DB_HOST.':'.CONFIG_DB_PORT.'/'))->selectDatabase(CONFIG_DB_NAME, ["readConcern" => new MongoDB\Driver\ReadConcern('local')]);
      } else {
        return $this->DB = (new MongoDB\Client('mongodb://'.CONFIG_DB_HOST.':'.CONFIG_DB_PORT.'/'))->selectDatabase(CONFIG_DB_NAME, ["readConcern" => new MongoDB\Driver\ReadConcern('local')]);
      }
    } catch (Exception $e) {
      error_log($e->getMessage(). "\n" . $e->getTraceAsString());
      throw new HTTPException(500);
    }
  }
  public function getRedis(): RedisNG {
    if ($this->Redis !== null) {
      return $this->Redis;
    }
    try {
      $Redis = $this->Redis = new RedisNG();
      $Redis->connect(CONFIG_REDIS_HOST, CONFIG_REDIS_PORT);
      return $Redis;
    } catch (Exception $e) {
      error_log($e->getMessage(). "\n" . $e->getTraceAsString());
      throw new HTTPException(500);
    }
  }
  public function getUser(): ?User {
    $session = $this->getSession();
    if ($session->exists('UserID')) {
      $id = $session->get('UserID', 0);
      $user = $this->getDB()->selectCollection('users')->findOne(['id' => $id]);
      if ($user !== null) {
        return $this->User = $user;
      } else {
        $session->unset('UserID');
      }
    }
    return null;
  }
	public function addToLinkHeader(string $link, Map<string, string> $attributes): void {
		$this->LinkHeader .= ', <'.$link.'>';
		foreach($attributes as $key => $val){
			$this->LinkHeader .= '; '.$key.'='.(strpos($val, ' ') ? '"'.$val.'"' : $val);
		}
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
