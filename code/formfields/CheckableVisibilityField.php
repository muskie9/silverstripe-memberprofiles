<?php
/**
 * A wrapper around a field to add a checkbox to optionally mark it as visible.
 *
 * @package    silverstripe-memberprofiles
 * @subpackage formfields
 */
class CheckableVisibilityField extends FormField {

	protected $child, $checkbox;

	/**
	 * @param FormField $child
	 */
	public function __construct($child) {
		parent::__construct($child->Name());

		$this->child    = $child;
		$this->checkbox = new CheckboxField("Visible[{$this->name}]", '');
	}

	public function setValue($value, $data) {
		$this->child->setValue($value);

		if (is_array($data)) {
			$this->checkbox->setValue((
				isset($data['Visible'][$this->name]) && $data['Visible'][$this->name]
			));
		} else {
			$this->checkbox->setValue(in_array(
				$this->name, $data->getPublicFields()
			));
		}

		return $this;
	}

	public function saveInto($record) {
		$child = clone $this->child;
		$child->setName($this->name);
		$child->saveInto($record);

		$public = $record->getPublicFields();

		if ($this->checkbox->dataValue()) {
			$public = array_merge($public, array($this->name));
		} else {
			$public = array_diff($public, array($this->name));
		}

		$record->setPublicFields($public);
	}

	public function Value() {
		return $this->child->Value();
	}

	public function dataValue() {
		return $this->child->dataValue();
	}

	public function Field() {
		return $this->child->Field() . ' ' . $this->checkbox->Field();
	}

	public function Title() {
		return $this->child->Title();
	}

	public function Message() {
		return $this->child->Message();
	}

	public function MessageType() {
		return $this->child->MessageType();
	}

}