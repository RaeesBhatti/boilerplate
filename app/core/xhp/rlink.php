<?hh //strict

class :rlink extends :link {
  public function stringify(): string {
    $this->setAttribute('href', Helper::toAssets($this->getAttribute('href')));
    return parent::stringify();
  }
}
