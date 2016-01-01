<?hh //strict
class Router<T> {
  public array<HTTP, array<(
    array<string>,
    T,
    bool
  )>> $Callbacks;
  public array<classname<Theme>> $Themes = [];

  public function __construct() {
    // Registry of callbacks
    $this->Callbacks = [
      HTTP::GET => [],
      HTTP::POST => [],
      HTTP::PUT => [],
      HTTP::DELETE => [],
      HTTP::PATCH => []
    ];
  }

  public function get(array<string> $URI, T $Callback, bool $isDirectory = false): this {
    return $this->route(HTTP::GET, $URI, $Callback, $isDirectory);
  }

  public function post(array<string> $URI,  T $Callback, bool $isDirectory = false): this {
    return $this->route(HTTP::POST, $URI, $Callback, $isDirectory);
  }

  public function patch(array<string> $URI, T $Callback, bool $isDirectory = false): this {
    return $this->route(HTTP::PATCH, $URI, $Callback, $isDirectory);
  }

  public function put(array<string> $URI, T $Callback, bool $isDirectory = false): this {
    return $this->route(HTTP::PUT, $URI, $Callback, $isDirectory);
  }

  public function delete(array<string> $URI, T $Callback, bool $isDirectory = false): this {
    return $this->route(HTTP::DELETE, $URI, $Callback, $isDirectory);
  }

  public function route(HTTP $Term, array<string> $URI, T $Callback, bool $isDirectory = false): this {
    $this->Callbacks[$Term][] = tuple($URI, $Callback, $isDirectory);
    return $this;
  }

  public function registerTheme(classname<Theme> $Theme): void {
    $this->Themes[] = $Theme;
  }

  public function executeTheme(string $URL): classname<Theme> {
    foreach($this->Themes as $Theme) {
      $Prefix = $Theme::PREFIX;
      if (strpos($URL, $Prefix) === 0) {
        return $Theme;
      }
    }
    throw new Exception('Theme not found');
  }

  public function execute(HTTP $Term, string $URI, ?array<string> $URIChunks = null, string $PrefixURL = ''): T {
    if ($URIChunks === null) {
      $URIChunks = Helper::uriToChunks($URI);
    }

    $URLIsDirectory = substr($URI, -1, 1) === '/';
    if ($URI !== '/' && $URLIsDirectory) {
      $URI = substr($URI, 0, -1);
    }

    foreach ($this->Callbacks[$Term] as $Entry) {
      list($RequiredURI, $Callback, $isDirectory) = $Entry;
      if ($this->validate($RequiredURI, $URIChunks)) {
        if ($URLIsDirectory !== $isDirectory) {
          $Get = App::getInstance()->Get;
          $NewURL = $PrefixURL . $URI . ($isDirectory ? '/' : '');
          throw new HTTPRedirectException($NewURL);
        } else {
          return $Callback;
        }
      }
    }
    throw new HTTPException(404);
  }

  private function validate(array<string> $RequiredURI, array<string> $Chunks): bool {
    $DB = null;
    if (count($RequiredURI) !== count($Chunks)) {
      return false;
    }
    foreach ($RequiredURI as $Index => $Clause) {
      if ($Clause === '*') {
        continue;
      } else if (strpos($Clause, ':') !== false) {
        list($Table, $Column) = explode(':', $Clause, 2);
        if ($DB === null) {
          $DB = App::getInstance()->getDB();
        }
        $Query = $DB->query("Select 1 from $Table where $Column = :value LIMIT 1", [':value' => $Chunks[$Index]]);
        if (!$Query->rowCount()) {
          return false;
        }
      } else {
        if ($Clause !== $Chunks[$Index]) {
          return false;
        }
      }
    }
    return true;
  }
}
