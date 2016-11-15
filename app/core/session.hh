<?hh

class Session {
	public function get<T>(string $key, T $default): T {
		if (array_key_exists($key, $_SESSION)) {
			return $_SESSION[$key];
		} else {
			return $default;
		}
	}
	public function set<T>(string $key, T $value): void {
		$_SESSION[$key] = $value;
	}
	public function exists(string $key): bool {
		return array_key_exists($key, $_SESSION);
	}
	public function unset(string $Key): void {
		unset($_SESSION[$Key]);
	}
}
