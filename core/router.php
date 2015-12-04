<?hh //strict
class Router<T> {
  public array<HTTP, array<(
    array<string>,
    T,
    bool
  )>> $Callbacks;

  public function __construct() {
    // Registry of callbacks
    $this->Callbacks = [
      HTTP::GET => [],
      HTTP::POST => [],
      HTTP::PUT => [],
      HTTP::DELETE => [],
    ];
  }

  public function get(array<string> $URI, T $Callback, bool $isDirectory = false): this {
    return $this->route(HTTP::GET, $URI, $Callback, $isDirectory);
  }

  public function post(array<string> $URI,  T $Callback, bool $isDirectory = false): this {
    return $this->route(HTTP::POST, $URI, $Callback, $isDirectory);
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

  public function execute(HTTP $Term, string $URI, ?array<string> $URIChunks = null): T {
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
          $NewURL = $URI . ($isDirectory ? '/' : '') . (count(App::$Get) ? '?'. http_build_query(App::$Get) : '');
          throw new HTTPRedirectException($NewURL);
        } else {
          return $Callback;
        }
      }
    }
    throw new HTTPException(404);
  }

  private function validate(array<string> $RequiredURI, array<string> $Chunks): bool {
    if (count($RequiredURI) !== count($Chunks)) {
      return false;
    }
    foreach ($RequiredURI as $Index => $Clause) {
      if ($Clause === '*') {
        return true;
      } else {
        return $Clause === $Chunks[$Index];
      }
    }
    return true;
  }
}
