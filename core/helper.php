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
}
