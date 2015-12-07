<?hh //strict

class :page-script extends :x:primitive {
  public static int $Number = 0;

  attribute
    Set<string> dependencies = Set{},
    Stringish name,
    Stringish src @required;
  public function getName(): string {
    $name = $this->:name;
    if($name === null){
      $name = 'script_'.(++ static::$Number);
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
    throw new Exception(':page-script should not be renderer');
  }
}
