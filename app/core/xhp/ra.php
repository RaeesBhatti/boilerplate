<?hh //strict

class :ra extends :a {
  public function stringify(): string {
    $this->setAttribute('href', 'https://'.HOSTNAME.$this->getAttribute('href'));
    return parent::stringify();
  }
}
