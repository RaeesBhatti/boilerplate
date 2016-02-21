<?hh //strict

class :ra extends :a {
  public function stringify(): string {
    $this->setAttribute('href', Helper::isSecure() ? 'https://'.HOSTNAME.$this->getAttribute('href') : '//'.HOSTNAME.$this->getAttribute('href')
    );
    return parent::stringify();
  }
}
