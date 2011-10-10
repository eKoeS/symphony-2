<?php


	/**
	 * @package toolkit
	 */
	/**
	 * Widget is a utility class that offers a number miscellaneous of
	 * functions to help generate common HTML Forms elements as XMLElement
	 * objects for inclusion in Symphony backend pages.
	 */
	Class UI{

		public static function HorizontalGrid() {
			$group = new XMLElement('div', NULL, array('class' => 'horizontal'));

			foreach(func_get_args() as $el){
				$div = new XMLElement('div', NULL, array('class' => 'row'));
				$div->appendChild($el);
				$group->appendChild($div);
			}

			return $group;
		}

		public static function Frame(XMLElement $element, $condition = true) {
			$span = new XMLElement('span', NULL, array('class' => 'frame'));

			if($condition){
				$span->appendChild($element);
			}

			return $span;
		}

		public static function Tags(array $tags, $singular = false) {
			$ul = new XMLElement('ul', NULL, array('class' => 'tags' . ($singular ? ' singular' : '')));

			foreach($tags as $tag) {
				$ul->appendChild(new XMLElement('li', $tag));
			}

			return $ul;
		}

		public static function TwoColumnsGrid(XMLElement $left, XMLElement $right) {
			$grid = new XMLElement('div', NULL, array('class' => 'group'));

			$grid->appendChild($left);
			$grid->appendChild($right);

			return $grid;
		}

		public static function ThreeColumnsGrid(XMLElement $left, XMLElement $center, XMLElement $right) {
			$grid = new XMLElement('div', NULL, array('class' => 'group'));

			$grid->appendChild($left);
			$grid->appendChild($center);
			$grid->appendChild($right);

			return $grid;
		}

		public static function Setting($label, XMLElement $contents) {
			$group = new XMLElement('fieldset');

			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', $label));
			$group->appendChild($contents);

			return $group;
		}

		public static function Pagination($current_page, $total_pages, $entries_per_page, $total_entries, $url_parameters = ''){
			$ul = new XMLElement('ul');
			$ul->setAttribute('class', 'page');

			## First
			$li = new XMLElement('li');

			if($current_page > 1){
				$li->appendChild(Widget::Anchor(
					__('First'),
					Administration::instance()->getCurrentPageURL(). '?pg=1' . $url_parameters
				));
			} else {
				$li->setValue(__('First'));
			}

			$ul->appendChild($li);

			## Previous
			$li = new XMLElement('li');

			if($current_page > 1){
				$li->appendChild(Widget::Anchor(
					__('&larr; Previous'),
					Administration::instance()->getCurrentPageURL(). '?pg=' . ($current_page - 1) . $url_parameters
				));
			} else {
				$li->setValue(__('&larr; Previous'));
			}

			$ul->appendChild($li);

			## Summary
			$li = new XMLElement('li', __('Page %1$s of %2$s', array($current_page, max($current_page, $total_pages))));

			$li->setAttribute('title', __('Viewing %1$s - %2$s of %3$s entries', array(
				($entries_per_page * $current_page) + 1,
				($current_page != $total_pages) ? $current_page * $entries_per_page : $total_entries,
				$total_entries
			)));

			$ul->appendChild($li);

			## Next
			$li = new XMLElement('li');

			if($current_page < $total_pages){
				$li->appendChild(Widget::Anchor(
					__('Next &rarr;'),
					Administration::instance()->getCurrentPageURL(). '?pg=' . ($current_page + 1) . $url_parameters)
				);
			} else {
				$li->setValue(__('Next &rarr;'));
			}

			$ul->appendChild($li);

			## Last
			$li = new XMLElement('li');

			if($current_page < $total_pages){
				$li->appendChild(Widget::Anchor(
					__('Last'),
					Administration::instance()->getCurrentPageURL(). '?pg=' . $total_pages . $url_parameters)
				);
			} else {
				$li->setValue(__('Last'));
			}

			$ul->appendChild($li);

			return $ul;
		}

		public static function HelpText($message) {
			return new XMLElement('p', $message, array('class' => 'help'));
		}

		public static function Duplicator($id, array $data, $function, array $parameters) {
			$ol = new XMLElement('ol', NULL, array('id' => $id, 'class' => 'duplicator'));

			foreach($data as $d){
				$li = new XMLElement('li');
				call_user_func($function, $d, $li, $parameters);
				$ol->appendChild($li);
			}

			return $ol;
		}

		public static function BottomActions($save_message = '', $show_delete = true, $delete_title = '', $delete_message = '') {
			$div = new XMLElement('div', NULL, array('class' => 'actions'));

			$div->appendChild(Widget::Input(
				'action[save]',
				trim($save_message) != '' ? $save_message : __('Save Changes'),
				'submit',
				array('accesskey' => 's')
			));

			if($show_delete && trim($delete_title) != '' && trim($delete_message) != ''){
				$div->appendChild(new XMLElement('button', __('Delete'), array(
					'name' => 'action[delete]',
					'class' => 'button confirm delete',
					'title' => $delete_title,
					'type' => 'submit',
					'accesskey' => 'd',
					'data-message' => $delete_message
				)));
			}

			return $div;
		}

		public static function WithSelected(array $options) {
			$div = new XMLElement('div', NULL, array('class' => 'actions'));

			$div->appendChild(Widget::Select('with-selected', $options));
			$div->appendChild(Widget::Input('action[apply]', __('Apply'), 'submit'));

			return $div;
		}

		/**
		 * Will wrap a `<div>` around a desired element to trigger the default
		 * Symphony error styling.
		 *
		 * @param XMLElement $element
		 *	The element that should be wrapped with an error
		 * @param string $message
		 *	The text for this error. This will be appended after the $element,
		 *  but inside the wrapping `<div>`
		 * @return XMLElement
		 */
		public static function Error(XMLElement $element, $message, $condition = true){
			if(!$condition) return $element;

			$div = new XMLElement('div');
			$div->setAttributeArray(array('id' => 'error', 'class' => 'invalid'));

			$div->appendChild($element);
			$div->appendChild(new XMLElement('p', $message));

			return $div;
		}

	}

