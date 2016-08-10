<?hh //strict
class Theme_Error extends Page {
	public function __construct(): void {

	}
	public function render(): :page {
		$errorCode = App::getInstance()->HTTPCode;
		return <page>
			<page-content>
				HTTP Error {$errorCode}
			</page-content>
		</page>;
	}
}
