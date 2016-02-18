<?hh //strict
class Theme_Guest_Home extends Page {
  public function __construct() {
    
  }
  public function render(): :page {
    return <page>
      <page-title>Hello there!</page-title>
      <page-content>Hey!</page-content>
    </page>;
  }
}
