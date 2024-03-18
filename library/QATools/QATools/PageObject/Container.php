<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace QATools\QATools\PageObject;


use mindplay\annotations\AnnotationCache;
use mindplay\annotations\AnnotationManager;
use Pimple\Container as BaseContainer;
use QATools\QATools\PageObject\Config\Config;
use QATools\QATools\PageObject\PageLocator\DefaultPageLocator;
use QATools\QATools\PageObject\PageUrlMatcher\PageUrlMatcherRegistry;
use QATools\QATools\PageObject\Url\Normalizer;
use QATools\QATools\PageObject\Url\UrlFactory;

class Container extends BaseContainer
{

	/**
	 * Instantiate the container.
	 *
	 * Objects and parameters can be passed as argument to the constructor.
	 *
	 * @param array $values The parameters or objects.
	 */
	public function __construct(array $values = array())
	{
		parent::__construct($values);

		$this['config_options'] = array();

		$this['config'] = function ($c) {
			return new Config($c['config_options']);
		};

		$this['annotation_manager'] = function () {
			$annotation_manager = new AnnotationManager();
			$annotation_manager->cache = new AnnotationCache(sys_get_temp_dir());

			return $annotation_manager;
		};

		$this['url_factory'] = function () {
			return new UrlFactory();
		};

		$this['url_normalizer'] = function ($c) {
			/** @var Config $config */
			$config = $c['config'];

			return new Normalizer($config->getOption('base_url'));
		};

		$this['page_locator'] = function ($c) {
			/** @var Config $config */
			$config = $c['config'];

			return new DefaultPageLocator((array)$config->getOption('page_namespace_prefix'));
		};

		$this['page_url_matcher_registry'] = function ($c) {
			$page_url_matcher_registry = new PageUrlMatcherRegistry($c['annotation_manager']);

			/** @var Config $config */
			$config = $c['config'];

			foreach ( $config->getOption('page_url_matchers') as $matcher_class ) {
				$page_url_matcher_registry->add(new $matcher_class());
			}

			return $page_url_matcher_registry;
		};

		$this['selenium_selector'] = function ($c) {
			return new SeleniumSelector();
		};
	}

}
