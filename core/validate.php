<?hh //strict

class Validate {
  const USERNAME = '/^[a-zA-Z0-9]{3,30}$/';
  const PASSWORD = '/^[a-zA-Z0-9!@#$%^&*()_+-=]{5,50}$/';
  const AUTH_CODE = '/^[0-9]{6}$/';

  public static function go(string $regex, string $value): bool {
    return (bool) preg_match($regex, $value);
  }
}
