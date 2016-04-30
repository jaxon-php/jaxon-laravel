<?php namespace \Xajax\Laravel\Pagination;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Pagination\BootstrapThreeNextPreviousButtonRendererTrait;
use Illuminate\Pagination\BootstrapThreePresenter;
use Illuminate\Pagination\UrlWindowPresenterTrait;

class Presenter extends BootstrapThreePresenter
{
    use BootstrapThreeNextPreviousButtonRendererTrait, UrlWindowPresenterTrait;

	/**
	 * The xajax request.
	 *
	 * @var \\Xajax\Laravel\Request
	 */
	protected $xajaxRequest = '';

	/**
	 * Create a new Presenter instance.
	 *
	 * @param  \Illuminate\Pagination\LengthAwarePaginator  $paginator
	 * @param  \\Xajax\Laravel\Request						 $xajaxRequest
	 * @return void
	 */
	public function __construct(Paginator $paginator, $xajaxRequest)
	{
		parent::__construct($paginator);
		$this->xajaxRequest = $xajaxRequest;
	}

	/**
	 * Get HTML wrapper for an available page link.
	 *
	 * @param  string  $url
	 * @param  int  $page
	 * @param  string|null  $rel
	 * @return string
	 */
	public function getAvailablePageWrapper($url, $page, $rel = null)
	{
		if($page == '&laquo;') // Prev page
			$number = $this->paginator->currentPage() - 1;
		else if($page == '&raquo;') // Next page
			$number = $this->paginator->currentPage() + 1;
		else
			$number = $page;
		return '<li><a href="javascript:;" onclick="' .
			$this->xajaxRequest->setPageNumber($number)->getScript() .
			';return false;">' . $page . '</a></li>';
	}

	/**
	 * Get HTML wrapper for disabled text.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function getDisabledTextWrapper($text)
	{
		return '<li class="disabled"><span>' . $text . '</span></li>';
	}

	/**
	 * Get HTML wrapper for active text.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function getActivePageWrapper($text)
	{
		return '<li class="active"><span>' . $text . '</span></li>';
	}
}
