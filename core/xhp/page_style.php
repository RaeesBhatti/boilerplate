<?hh //strict

class :page-style extends :x:primitive {
  public static int $Number = 0;

  attribute
    Set<string> dependencies = Set{},
    Stringish name,
    Stringish src @required;
  public function getName(): string {
    $name = $this->:name;
    if($name === null){
      $name = 'style_'.(++ static::$Number);
    }
    return (string) $name;
  }
  public function getDependencies(): Set<string> {
    return $this->:dependencies;
  }
  public function getSource(): string {
    return (string) $this->:src;
  }
  public function stringify(): string {
    throw new Exception(':page-style should not be renderer');
  }
}
