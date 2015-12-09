<?hh //strict
class Helper {
  public static function trim(mixed $Value):string{
    if (is_array($Value)) {
      return '';
    } else if (is_bool($Value)) {
      return $Value ? 'true' : 'false';
    } else {
      return trim((string) $Value);
    }
  }
  public static function Random():int{
    return mt_rand(RAND_MAX/9, RAND_MAX);
  }
  public static function RandomPassword():string{
    return (string) password_hash((string) static::Random(), PASSWORD_DEFAULT);
  }
  public static function RandomCryptic(): string {
    return str_replace(['$', '\\', '/', '.'], '', static::RandomPassword());
  }

  // For API use only
  public static function validateFields(ImmSet<string> $Fields, array<string, string> $Post): void {
    foreach ($Fields as $FieldName) {
      if (!array_key_exists($FieldName, $Post)) {
        throw new APIException();
      }
    }
  }
  public static function uriToChunks(string $URI): array<string> {
    $Chunks = [];
    foreach (explode('/', $URI) as $Chunk) {
      if ($Chunk !== '') {
        $Chunks[] = $Chunk;
      }
    }
    return $Chunks;
  }
  public static function apiEncode(mixed $message): string {
    if ($message instanceof APIException) {
      return json_encode(['status' => false, 'message' => $message->getMessage()]);
    } else if (is_array($message)) {
      return json_encode(array_merge(['status' => true], $message));
    } else throw new Exception('Incorrect API Response');
  }
  public static function go(string $method, array<string, string> $Get, array<string, string> $Post, array<string, string> $Server, array<string, string> $Cookie): string {
    if (!HTTP::isValid($method)) {
      return '';
    }
    $App = new App($Get, $Post, $Server, $Cookie);
    App::$Instance = $App;

    try {
      try {
        $Content = $App->execute(HTTP::assert($method));
      } catch (APIFieldMultiException $e) {
        $Content = json_encode(['status' => false, 'fields' => array_map(function(APIFieldException $Error) {
          return ['name' => $Error->name, 'message' => $Error->getMessage()];
        }, $e->Errors)]);
      } catch (APIException $e) {
        $Content = Helper::apiEncode($e);
      } catch (HTTPException $e) {
        throw $e;
      } catch (Exception $e) {
        error_log($e->getMessage(). "\n" . $e->getTraceAsString());
        throw new HTTPException(500);
      }
    } catch (HTTPException $e) {
      $App->HTTPCode = $e->httpCode;
      if (APP_ENV === AppEnv::API) {
        $Content = json_encode(['status' => false, 'message' => "HTTP Error $e->httpCode", 'type' => 'http']);
      } else {
        $Content = (string) Helper::renderPage(Theme_Error::class);
      }

      if ($e instanceof HTTPRedirectException) {
        header('Location: '.$e->redirectUri);
      }
    }
    http_response_code($App->HTTPCode);
    return $Content;
  }
  public static function organizeDependencies(array<shape(
    'src' => string,
    'dependencies' => Set<string>,
    'name' => string
  )> $dependencies): Vector<shape(
    'src' => string,
    'name' => string,
    'dependents' => int
  )> {
    $resolved = Vector{};
    $unresolved = [];

    foreach($dependencies as $dep) {
      $unresolved[$dep['name']] = shape(
        'name' => $dep['name'],
        'dependencies' => $dep['dependencies'],
        'src' => $dep['src'],
        'disposed' => false,
        'dependents' => 0
      );
    }

    foreach($dependencies as $dep) {
      foreach($dep['dependencies'] as $entry) {
        if (!array_key_exists($entry, $unresolved)) {
          throw new Exception("Can not resolve dependency `$entry` of `$dep[name]`");
        } else {
          $unresolved[$entry]['dependents']++;
        }
      }
    }

    $dependencyCount = count($unresolved);
    $resolvedCount = 0;
    while ($resolvedCount !== $dependencyCount) {
      $foundOne = false;
      foreach ($unresolved as $key => $dep) {
        if (!$dep['disposed']) {
          $allResolved = true;
          foreach($dep['dependencies'] as $dependency) {
            $allResolved = $allResolved && $unresolved[$dependency]['disposed'];
          }
          if ($allResolved) {
            $foundOne = true;
            $resolvedCount++;
            $resolved->add($dep);
            $unresolved[$key]['disposed'] = true;
          }
        }
      }
      if (!$foundOne) {
        throw new Exception('Unable to resolve dependencies');
      }
    }

    return $resolved;
  }
  public static function toAbsolute(string $URL): string {
    if ($URL[0] !== '/') {
      return AppConfig::ASSETS_PREFIX . $URL;
    } else {
      return $URL;
    }
  }
  public static function renderPage(classname<Page> $PageName): Stringish {
    $Page = new $PageName();
    $Content = $Page->render();
    if (!is_string($Content)) {
      if (!($Content instanceof :page)) {
        throw new Exception('Content is neither string nor :page');
      }
      $Content->attachTo($Page);
    }
    return $Content;
  }
}
