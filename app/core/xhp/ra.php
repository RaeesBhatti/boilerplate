<?hh //strict

class :ra extends :a {
  public function stringify(): string {
    $this->setAttribute('href', array_key_exists('HTTPS', App::getInstance()->Server) ?
                                'https://'.HOSTNAME.$this->getAttribute('href') :
                                '//'.HOSTNAME.$this->getAttribute('href')
    );
    return parent::stringify();
  }
}
