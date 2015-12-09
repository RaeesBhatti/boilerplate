<?hh //strict

class :page extends :x:element {
  attribute
    Stringish lang = 'en',
    Stringish class;
  children (:page-title*, :page-script*, :page-style*, :page-content*);
  private ?Page $Page = null;
  public function attachTo(Page $Page): void {
    $this->Page = $Page;
  }
  protected function render(): XHPRoot{
    $Page = $this->Page;
    invariant($Page !== null, 'No Page attached to :page');

    $Content = '';
    $Scripts = [];
    $Styles = [];
    $Header = $Page->Header;
    $Footer = $Page->Footer;

    foreach($this->getChildren() as $child) {
      if ($child instanceof :page-script) {
        $Scripts[] = shape(
          'name' => $child->getName(),
          'src' => $child->getSource(),
          'dependencies' => $child->getDependencies()
        );
      } else if ($child instanceof :page-style) {
        $Styles[] = shape(
          'name' => $child->getName(),
          'src' => $child->getSource(),
          'dependencies' => $child->getDependencies()
        );
      } else if ($child instanceof :page-content) {
        $Content = (string) :xhp::renderChild($child);
      } else if ($child instanceof :page-title) {
        $Page->Title = $child->stringify();
      }
    }

    foreach(Helper::organizeDependencies($Scripts) as $Script) {
      $Footer[] = <script async={$Script['dependents'] === 0 && !$Script['dependencies']->count()} src={Helper::toAbsolute($Script['src'])}></script>;
    }
    foreach(Helper::organizeDependencies($Styles) as $Style) {
      $Header[] = <link rel="stylesheet" type="text/css" href={Helper::toAbsolute($Style['src'])} />;
    }

    return
      <x:doctype>
        <html lang={$this->:lang}>
          <head>
            <title>{$Page->Title}</title>
            <meta charset="UTF-8" />
            {$Header}
          </head>
          <body class={$this->:class}>
            <string content={$Content} />
            {$Footer}
          </body>
        </html>
      </x:doctype>;
  }
}
